# AI Automations — Timetables & Fee Transactions

> Companion to `.planning/AI-ENHANCEMENTS.md` — deep-dive on two high-leverage subsystems.
> Project: Nxt_backend (Yii2 2.0.5 PHP school ERP)
> Date: 2026-05-13

---

## 0. Why These Two Modules

Both are **rule-heavy, repetitive, error-prone, and parent-visible.** They consume disproportionate admin time today and are exactly the kind of work LLMs + classic optimisation can collapse from hours to seconds.

- **Timetables** already has a `TimetableErrorReports` model — the codebase itself admits this is a pain point.
- **Fees** is the revenue path. Razorpay is wired up; payment workflow exists; what's missing is *intelligence around* the workflow (predicting defaulters, reconciling payments, writing the reminder).

---

## Part A — Timetables

### A.1 What Exists Today

**Models (Yii2 ActiveRecord, in `modules/admin/models/` and `modules/exammanagement/models/`):**

| File | Purpose |
|---|---|
| `SubjectTimetable.php` | Master class-subject schedule (period × day × class × teacher × subject) |
| `AttendanceTimeTables.php` | Per-period attendance configuration |
| `TimetableErrorReports.php` | **Existing pain signal** — manual conflict reports |
| `ExamSchedules.php` (exammanagement) | Exam timetable |
| `ScheduledExamMarksDevision.php` | Internal/external marks split |

**Controllers:** `SubjectTimetableController`, `AttendanceTimeTablesController`, `TimetableErrorReportsController`, `ExamSchedulesController`.

**Views:** `subject-timetable/index_teacher_time_table.php`, `_time_table_form.php`, `exam-schedules/_form_create_time_table.php`.

**Conclusion:** Schedule entry today is form-by-form. Conflict detection is reactive (`TimetableErrorReports` is a *post-hoc* complaint inbox, not a preventer).

### A.2 AI Automations for Timetables

#### A.2.1 ★ Auto-Generated Master Timetable (highest impact)
Treat timetable generation as a **constraint satisfaction problem with an LLM-friendly intake layer**, not as a pure LLM task.

- **Intake (LLM):** Admin describes in natural language: *"Class 7 needs 6 maths, 5 english, 4 science, 3 social, 2 PE, 1 art per week. Mr. Rao only teaches mornings. No more than 2 maths periods on the same day. Friday last period is assembly."* LLM converts this to a structured JSON constraint set.
- **Solver (CP-SAT / OR-Tools, *not* LLM):** Generates a valid timetable. Use Google OR-Tools via a small Python sidecar called from `commands/GenerateTimetableController.php`.
- **Explanation (LLM):** When the solver succeeds — narrate the choices. When it fails — pinpoint the conflict in plain English ("Mr. Rao has 30 morning periods to fill but only 24 morning slots exist").
- **Wire-up:** New `components/ai/TimetableComposer.php` + console command `php yii timetable/generate --campus_id=1 --class_id=7A`.

Why this matters: today the admin builds this by hand in `_time_table_form.php` field-by-field. A typical campus loses 2-3 staff-days per term to this.

#### A.2.2 Conflict Pre-flight on Save
Hook into `SubjectTimetable::beforeSave()` (Yii2 lifecycle hook, already idiomatic in this codebase per CONVENTIONS.md):

- Detect teacher double-booking, class double-booking, room double-booking, exceeded daily subject cap, lunch/assembly overlap.
- This is **pure SQL/PHP, no LLM** — but pair it with an LLM that *explains* the conflict and *suggests a fix* ("Swap period 4 Tuesday with period 2 Thursday — both are free for both teachers").
- Result: `TimetableErrorReports` becomes mostly empty because errors never get saved.

#### A.2.3 Substitute Teacher Auto-Assignment
When a teacher applies leave (`leavemanagement/`):

- Query `SubjectTimetable` for that teacher's affected periods.
- Rank substitutes by: free period overlap, same-subject competence, recent substitution load (fairness), preference.
- LLM drafts a **substitute assignment email** in the campus language with the specific period list.
- Output: a one-click "Approve & Notify" workflow for the admin.

#### A.2.4 Natural-Language Schedule Queries (parent & teacher app)
In `modules/api/`:

- Teacher: *"What's my schedule next Tuesday?"*
- Parent: *"When does my child have music class?"*
- Student: *"What's my next period?"*
- Implementation: LLM extracts intent + entity → calls a whitelisted tool `GetTimetable(user_id, date_range)` — **never raw SQL**. Tool reads via `SubjectTimetable::find()->with('teacher','subject','classSection')` so it also fixes the N+1 risk flagged in CONCERNS.md.

#### A.2.5 Exam Schedule Sanity Checker
For `ExamSchedules.php`:

- Detect cognitively-heavy back-to-back papers (e.g. maths + physics same day).
- Detect insufficient gap before practical exams.
- Detect overlap with planned holidays / festivals from a configurable calendar.
- LLM authors the *recommendation note* to the academic head with reasoning.

#### A.2.6 Smart Period-Attendance Anomaly Watch
Today `AttendanceTimeTables` records per-period attendance. Add a nightly job:

- Compare each period's marked attendance against expected enrollment.
- Flag periods with suspicious patterns (entire class marked present in 5 seconds, identical mark-times suggesting a stamp).
- LLM drafts the inquiry message to the period teacher / class teacher.

### A.3 Implementation Sketch — Timetable AI Layer

```
modules/admin/
├── controllers/
│   ├── SubjectTimetableController.php       ← unchanged signatures
│   └── (new) TimetableComposerController.php
├── models/
│   ├── SubjectTimetable.php                 ← add beforeSave() conflict check
│   └── (new) TimetableConstraint.php        ← JSON constraints from LLM intake
components/ai/
├── TimetableComposer.php                    ← orchestrates intake → solver → narrate
├── prompts/
│   ├── timetable_intake.txt
│   ├── timetable_conflict_explainer.txt
│   └── timetable_substitute_email.txt
└── tools/
    └── TimetableQuery.php                   ← safe tool-call for chat/api
commands/
└── TimetableController.php                  ← php yii timetable/generate
```

Sidecar: small Python service running OR-Tools, called over HTTP from the PHP composer (or a CLI subprocess from `exec()` if the deployment can't run a sidecar). Keep the LLM and the solver decoupled.

### A.4 Effort & Sequencing

| Feature | Effort | Risk | Sequence |
|---|---|---|---|
| Conflict pre-flight on save (A.2.2) | S | Low | **First** — pure backend, no model risk |
| NL schedule queries (A.2.4) | M | Low | Second — reuses parent chatbot infra |
| Substitute teacher auto-assignment (A.2.3) | M | Medium | Third — needs leavemanagement integration |
| Exam schedule sanity (A.2.5) | S | Low | Fourth |
| Period-attendance anomaly (A.2.6) | M | Low | Fifth |
| Master timetable generator (A.2.1) | L | Medium | Last — needs OR-Tools sidecar; biggest payoff |

---

## Part B — Fee Transactions

### B.1 What Exists Today

**Models (Yii2 ActiveRecord, in `modules/admin/models/`):**

| File | Purpose |
|---|---|
| `FeesTyps.php` | Catalogue of fee types (tuition / transport / library / lab / hostel) |
| `FeeStructures.php` | Fee plan tied to class/section/academic year |
| `AssignFeeToStudent.php` | Mapping of fee plan → individual student |
| `PayFees.php` | Payment intent / submission |
| `PaymentDetails.php` | Recorded payments (mode, txn id, status) |

**Controllers:** `FeesTypsController`, `FeeStructuresController`, `AssignFeeToStudentController`, `PayFeesController`, `PaymentDetailsController`, plus `modules/api/controllers/PaymentController.php` for mobile and `OrdersController.php` for the Razorpay e-commerce flow.

**External integration:** `components/RazorPay.php` (cURL → `api.razorpay.com/v1/orders`). Webhooks "implied" but verification is only basic-auth, per INTEGRATIONS.md.

**Templates:** `migrations/mail/layouts/invoice.php`, views `fees_reports.php`, `payment_history_of_student.php`, `payment_details_pending.php`.

**Conclusion:** The plumbing works. What's missing: anticipation, personalisation, reconciliation, and intelligent collections.

### B.2 AI Automations for Fees

#### B.2.1 ★ Defaulter Prediction & Early Intervention
The single highest-ROI item in the whole product.

- **Input features:** historical `PaymentDetails` (days-late distribution per student), `AssignFeeToStudent` totals, sibling status, attendance trend, change-of-address events, last-year recovery effort.
- **Model:** gradient-boosted tree (XGBoost) trained offline. Not an LLM — but the **next step is**: for each predicted high-risk student, an LLM authors a context-aware, tone-graded outreach plan ("first nudge: WhatsApp soft reminder; if no reply in 5 days: call from class teacher with this script").
- **Surface:** Admin dashboard widget "Fee Risk — Next 30 Days" with ranked list. One-click "send first nudge" wires through to the existing Brevo + FCM pipes.
- **Why now:** every model needed already exists; only the join + feature table is new.

#### B.2.2 Smart Reminder Composer
Replace the templated reminder with a **per-family LLM-authored message**:

- Inputs: family name, due amount, days overdue, prior reminder history, prior responses, preferred language, prior payment mode.
- Outputs: 3 variants — gentle / firm / final — for admin pick.
- Bilingual default (English + campus regional language).
- Channels: SendGrid (long form), Brevo (transactional), FCM (push), and **WhatsApp Business API once added**.
- Hooks into existing `PaymentDetailsController::actionPendingReport`.

#### B.2.3 Razorpay Webhook Auto-Reconciliation
Today `OrdersController` and `modules/api/controllers/PaymentController.php` handle Razorpay callbacks with "basic auth checks" (INTEGRATIONS.md flags this as weak). Beyond fixing the security gap:

- LLM-assisted **mismatch resolution**: when a Razorpay payment lands without a clean `AssignFeeToStudent.id` reference (UTR mismatch, partial payment, payment for multiple kids), the LLM proposes the most-likely allocation across one or many students, with a confidence score.
- Admin sees ranked suggestions, approves with one click.
- This is the single most painful manual workflow in school finance offices.

#### B.2.4 Receipt / UTR OCR Intake
For offline payments (cash, cheque, NEFT receipts that parents upload):

- `endroid/qr-code` + `kartik-v/yii2-mpdf` already in stack → vision LLM (Claude with vision, or Tesseract + LLM cleanup) reads uploaded receipts.
- Extracts: amount, date, UTR/cheque number, payee, payer.
- Drafts a `PaymentDetails` record, surfaces it for accountant approval.
- **Foundation work needed first:** file upload validation (CONCERNS §Missing File Upload Validation).

#### B.2.5 Dynamic Fee Concession / Scholarship Suggester
- Inputs: family income disclosure, sibling discount eligibility, merit (link to `childassessment.ChildMerit`), staff-ward status.
- LLM applies the campus's documented concession policy (uploaded once as `concession_policy.md`) and **proposes a concession**, with reasoning, for principal approval.
- Replaces a current ad-hoc, principal-only decision loop.

#### B.2.6 Plain-Language Fee Statement
- Replace the dense `payment_history_of_student.php` view with an LLM-authored summary:
  > *"For 2026-27: Aarav's annual tuition is ₹84,000. Paid so far: ₹56,000 across 4 instalments. Next due: ₹28,000 by 30-Jun-2026. Late fee after that is ₹50/day. You qualify for the second-sibling 10% discount on Riya's fees."*
- Generated on-demand, cached per `(student_id, term_id, balance_version)` hash.
- Embedded in parent app + email.

#### B.2.7 Conversational Fee Queries (parent app, modules/api/)
- *"How much do I owe?"* / *"Why was a late fee added?"* / *"Can I split the next instalment into two?"*
- Tool-call schema: `GetFeeBalance(student_id)`, `GetFeeHistory(student_id)`, `ProposeInstallmentPlan(student_id, plan_id)`.
- Last one drafts a plan but only books it after admin approval.

#### B.2.8 Fraud / Anomaly Detection on Transactions
- Patterns to watch: repeated reversals on the same student, payments from a new device + new IP, mode-mismatch (cash receipt issued same day as Razorpay credit for same line item — double-counting).
- Rule engine + LLM that **writes the alert** in plain English to the bursar.

#### B.2.9 Cash Flow Forecast for the Bursar
- Project month-end / quarter-end fee collection given current paid + predicted defaulters + historical seasonality.
- LLM authors the weekly bursar brief: *"Expected collection by 30-Jun: ₹42L (±₹3L). Risk: 18 families totalling ₹6L overdue >30 days. Recommended action: principal call list (top 5)."*

#### B.2.10 GST / Tax / Statutory Report Generator
- For institutes that bill GSTable services (transport, food, books):
- LLM takes the period's `PaymentDetails` + `FeesTyps.gst_rate` (add a column) and drafts the filing-ready summary + the cover narrative for the accountant.

### B.3 Critical Guardrails (Fees is Where Trust Lives or Dies)

| Rule | Why |
|---|---|
| LLM **never** writes to `PaymentDetails` directly | Every proposal needs human approval before persistence |
| All amounts shown in LLM output must trace back to a database read, not a token-predicted number | Stop hallucinated balances at the source |
| Every reminder/communication is **logged with the exact prompt + model version** | Disputes will happen |
| The fee chatbot is **read-only** in v1 | Writes (plan proposals, concessions) go to an approval queue, not the database |
| Provider keys (Razorpay, LLM) move to `.env` + secrets manager **before** any of this ships | CONCERNS.md §Critical Security |

### B.4 Implementation Sketch — Fees AI Layer

```
modules/admin/
├── controllers/
│   ├── PaymentDetailsController.php          ← add LLM-drafted reminder action
│   ├── PayFeesController.php                 ← unchanged
│   └── (new) FeeRiskController.php           ← defaulter dashboard
├── models/
│   ├── PaymentDetails.php                    ← afterSave hook → reconcile worker
│   └── (new) FeeRiskScore.php                ← cached predictions
modules/api/controllers/
└── PaymentController.php                     ← + chat intents for fee queries
components/
├── RazorPay.php                              ← + webhook HMAC verification (foundation)
└── ai/
    ├── ReminderComposer.php
    ├── ReceiptOCR.php
    ├── ReconciliationProposer.php
    ├── FeeStatementNarrator.php
    └── prompts/
        ├── reminder_gentle.txt
        ├── reminder_firm.txt
        ├── reconciliation.txt
        └── fee_statement.txt
commands/
├── FeeRiskController.php                     ← php yii fee-risk/recompute (nightly)
└── FeeReminderController.php                 ← php yii fee-reminder/send-due
```

ML training: separate Python repo, exports a pickled model + a versioned JSON of feature column order; PHP loads via `php-ml` or calls a tiny FastAPI sidecar. Pick by ops appetite.

### B.5 Effort & Sequencing

| Feature | Effort | Risk | Revenue impact | Sequence |
|---|---|---|---|---|
| Razorpay webhook hardening + HMAC (foundation) | S | — | Trust | **First — security gate** |
| Plain-language fee statement (B.2.6) | S | Low | Parent NPS | Second — read-only, hard to break |
| Smart reminder composer (B.2.2) | M | Medium | Direct ↑ collection | Third |
| Defaulter prediction (B.2.1) | M | Low | Highest ROI | Fourth |
| Conversational fee queries (B.2.7) | M | Medium | Support load ↓ | Fifth |
| Receipt OCR intake (B.2.4) | M | Medium | Office time saved | Sixth — needs upload validation done |
| Reconciliation proposer (B.2.3) | L | High | Bursar time saved | Seventh |
| Cash flow forecast (B.2.9) | M | Low | Leadership | Eighth |
| Concession suggester (B.2.5) | M | High (policy) | Equity | Ninth |
| Fraud/anomaly (B.2.8) | M | Medium | Loss prevention | Tenth |
| GST/tax helper (B.2.10) | M | High (legal) | Compliance | Last |

---

## Part C — Cross-Cutting (Both Modules Share These)

### C.1 Shared Data Foundations

- **`ai_invocations` table** — every LLM call, with `module`, `feature`, `user_id`, `campus_id`, `prompt_hash`, `model`, `input_tokens`, `output_tokens`, `latency_ms`, `cost_inr`, `approved_by`, `approved_at`. Mandatory.
- **`ai_proposals` table** — for actions awaiting human approval (reminders, reconciliation suggestions, substitute assignments). Status: `pending → approved → applied | rejected`.
- **`embeddings` table** — for fee-statement caching keyed by content, and timetable constraint reuse across terms.

### C.2 Shared Components

Build once, use in both modules:

- `components/ai/AIClient.php` — provider abstraction (default: Claude Sonnet 4.6; upgrade to Opus 4.7 for nuanced collections language and reconciliation reasoning).
- `components/ai/PiiRedactor.php` — strips parent phone, UPI ID, account number, child DOB from any prompt going out.
- `components/ai/ApprovalQueue.php` — generic "LLM proposed X; show admin Y; apply on click" pattern. Both substitute-teacher and reconciliation flows use this.
- `components/ai/AuditLogger.php` — writes `ai_invocations` rows.

### C.3 Foundation Blockers (Must Land First)

Pulled from `.planning/codebase/CONCERNS.md` — these block both modules:

1. Hardcoded secrets (SendGrid, Razorpay, Google) → `.env` + secrets manager.
2. Webhook verification (Razorpay) → HMAC instead of basic auth.
3. File upload validation → required for receipt OCR.
4. Raw input sanitization → required for any free-text LLM input (parent chat, admin NL timetable intake).
5. At least snapshot tests for fee math (CONCERNS.md flags zero test coverage).
6. Migrate the 16 raw `Yii::$app->db` accesses in `modules/api/` to ActiveRecord so the chat tool-calls can reuse them safely.

---

## Part D — 90-Day Plan

**Days 1-30 (foundation + first wins):**
- Land §C.3 items 1, 2, 3.
- Build `components/ai/` skeleton (§C.2).
- Ship: timetable conflict pre-flight (A.2.2) + plain-language fee statement (B.2.6).

**Days 31-60 (intelligence layer):**
- Ship: NL schedule queries (A.2.4), smart reminder composer (B.2.2), substitute teacher assignment (A.2.3).
- Train defaulter model offline; deploy as dashboard widget (B.2.1).

**Days 61-90 (collections + ops):**
- Ship: conversational fee queries (B.2.7), receipt OCR (B.2.4), exam schedule sanity (A.2.5).
- Start reconciliation proposer (B.2.3) — high effort, ship in next quarter.
- Begin master timetable generator (A.2.1) — OR-Tools sidecar build, ship next term.

---

## Part E — One-Liner Summary Per Feature

**Timetables:**
- `A.2.1` Auto-generate the whole timetable from a paragraph of constraints.
- `A.2.2` Block conflicting saves; explain in plain English.
- `A.2.3` Auto-pick + notify substitute teachers on leave approval.
- `A.2.4` "When is my next period?" works for teachers, parents, students.
- `A.2.5` Warn before scheduling maths + physics back-to-back.
- `A.2.6` Catch fake / mass-stamped attendance overnight.

**Fees:**
- `B.2.1` Predict who won't pay; rank for intervention.
- `B.2.2` Per-family LLM reminders in their language.
- `B.2.3` Auto-propose which student a stray payment belongs to.
- `B.2.4` Photograph an offline receipt; system books it.
- `B.2.5` Suggest concessions consistent with campus policy.
- `B.2.6` Parent-readable balance summary, not a Yii GridView.
- `B.2.7` Parent app answers fee questions safely (read-only).
- `B.2.8` Flag double-counted / suspicious transactions to bursar.
- `B.2.9` Weekly cash-flow brief for the principal/bursar.
- `B.2.10` Drafts GST / tax filing summaries.
