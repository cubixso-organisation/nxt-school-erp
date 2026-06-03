# AI Features & Enhancements — Nxt_backend (Yii2 School ERP)

> Source of truth: `.planning/codebase/{STACK,ARCHITECTURE,STRUCTURE,INTEGRATIONS,CONVENTIONS,TESTING,CONCERNS}.md`
> Date: 2026-05-13
> Audience: Engineering / Product owners deciding where AI/LLM capability adds the most leverage.

---

## 1. Executive Summary

**What this product is:** A Yii2 2.0.5 (PHP 7.0+) full-stack school ERP serving multi-tenant institutes/campuses. Roles: admin, institute-admin, campus-admin, teacher, parent, student. Modules cover admissions, attendance, exams, fees/orders (Razorpay), library, hostel, transport, inventory, leave, child-assessment, document generation, and an `api/` module for mobile clients via FCM push.

**Where AI creates the most value here (in order):**

1. **Document & report generation** — codebase already centers on PDF/QR/Excel output (`kartik-v/yii2-mpdf`, `endroid/qr-code`, `moonlandsoft/yii2-phpexcel`); LLMs reduce the manual narrative work (report cards, leave letters, parent communication).
2. **Conversational data access** — admins/teachers/parents currently drill through dashboards (`modules/admin/views/dashboard/index.php` aggregates BusDetails, StudentClass, TeacherAttendance, etc.); a "natural-language → SQL/JSON answer" assistant collapses that.
3. **Risk/early-warning analytics** — attendance + marks + fee data already exists; a model that predicts dropout/at-risk students is high-impact, low-data-cost.
4. **Multi-channel comms intelligence** — three messaging integrations exist (SendGrid, Brevo, FCM); AI can author, translate, segment, and time these.
5. **OCR + structured intake** — admissions, library, fee receipts, ID cards: all today rely on raw `$_POST` and "No File Upload Validation" (per CONCERNS.md). OCR + LLM extraction fixes both UX and data quality.

**Reality check:** The codebase has known foundational risk — **zero automated tests, hardcoded credentials, N+1 queries, raw `Yii::$app->db` access in 16 places in `modules/api/`, exposed binary archives, deprecated SwiftMailer, jQuery 1.11.2.** AI features must not be layered on top of those. Section 6 lays out what to fix first.

---

## 2. Module-by-Module AI Recommendations

Modules confirmed present: `admin`, `api`, `childassessment`, `comingsoon`, `documentgenerator`, `exammanagement`, `hostelmanagement`, `inventory`, `leavemanagement`, `librarymanagement`, `media`, `staffmanagement`, `support`.

### 2.1 `exammanagement/` — Exam Management
**Today:** Generates certificates with QR codes, exports PDF. Marks/grade entry is manual per row.

**AI features:**
- **Auto-generated report card narratives.** Given `student_marks`, `subject`, `class_average`, produce a 2-3 sentence per-subject teacher comment. Falls back to a template if LLM fails. *Effort: S. Impact: H.*
- **Grade boundary recommender.** Suggest grade cutoffs given a mark distribution (statistical, not LLM — but worth bundling under "AI").
- **Question paper drafter.** Teacher selects topic + Bloom's-level mix + difficulty; LLM drafts MCQ/short/long set, teacher edits, system saves to a `question_bank` table. *Effort: M. Impact: H — biggest teacher time-saver.*
- **Plagiarism / answer similarity scan** across submitted answer sheets (embedding-based cosine similarity on text answers). *Effort: M.*

### 2.2 `childassessment/` — Holistic / Merit Assessment
**Today:** `ChildMerit` model with `max_marks`, name, description, status. QR certificates.

**AI features:**
- **Behavior/observation summarizer.** Teachers log short observations across a term; LLM produces a structured "Strengths / Growth Areas / Recommendations" paragraph for the parent-facing report. *Effort: S. Impact: H.*
- **Skill-tag extractor.** Free-text observations → tags from a fixed taxonomy (`collaboration`, `curiosity`, `self-regulation`…). Enables analytics.
- **Trend insight per student** ("Riya's writing fluency improved 2 levels this term").

### 2.3 `leavemanagement/`
**Today:** Leave requests + SendGrid email notifications.

**AI features:**
- **Leave-letter drafter** for parents/staff (input: reason, dates, child name → polite letter in chosen language).
- **Pattern detection.** Flag staff/students whose leave pattern is anomalous (Mondays-only, exam-eve clustering) — rule + simple model, surfaced to admin.
- **Auto-classify reason** (medical / personal / bereavement) for analytics + faster approval routing.

### 2.4 `librarymanagement/`
**Today:** PDF/file handling, manual catalog entry.

**AI features:**
- **Book ingestion via ISBN/cover photo.** Image → OCR/vision → autofill title, author, ISBN, subject tags. *Effort: M. Impact: H — kills the worst manual entry workflow in this product.*
- **Personalized reading recommendations** per student (collaborative filter on `loan_history` + content-based on subject embedding).
- **Reading-level matcher** (Lexile-like score from book blurb, matched to student grade).
- **Semantic search over the catalog.** Embedding index in MySQL or pgvector sidecar; "books about Mughal architecture for class 7" works.

### 2.5 `hostelmanagement/`
**Today:** Location picker, driving distance via Google Maps (currently commented out per INTEGRATIONS.md).

**AI features:**
- **Roommate matching.** Survey + interest embeddings → cluster compatible pairs, present admin with ranked suggestions.
- **Mess menu / dietary planner.** LLM proposes weekly menu obeying constraints (veg %, allergens, budget, prior-week diversity).
- **Complaint triage.** Free-text complaints from students → category (plumbing / food / discipline) + urgency + assigned warden.

### 2.6 `inventory/`
**Today:** Excel import/export via `kartik-v/yii2-export`.

**AI features:**
- **Reorder forecasting.** ARIMA/Prophet over `stock_movements`; flag low items before runout. *Effort: M. Pure ML, not LLM.*
- **PO drafting** — given a low-stock list, LLM drafts purchase orders to known vendors, including standard T&Cs.
- **Invoice OCR → stock receipt** — vendor invoice photo/PDF → parsed line items posted to `inventory_in`.

### 2.7 `staffmanagement/` + `admin/` HR
**AI features:**
- **JD generator + screener.** Job description from a short brief; resume parser + ranker against the JD.
- **Performance review summarizer** from peer / supervisor entries.
- **Salary slip / appointment letter generator** with bilingual output (English + regional language).

### 2.8 `support/`
**Today:** Ticket-style support module.

**AI features:**
- **Ticket auto-classification & routing** (category, urgency, owner team).
- **First-response draft** posted as private comment, support agent edits & sends.
- **Knowledge-base RAG.** Ingest past resolved tickets + FAQ → answer assistant for new tickets and for parent-facing chat widget.

### 2.9 `documentgenerator/`
**Today:** PDF + QR generator, template-driven.

**AI features:**
- **Natural-language template authoring** — admin types "give me a bonafide certificate template with school header and principal signature line"; system produces the editable template.
- **Bulk variable filler** with sanity checks (LLM cross-checks variable types — date looks like a date, amount has currency).

### 2.10 `api/` (mobile / parent-student app)
**Today:** 16 raw `Yii::$app->db` direct accesses (CONCERNS.md). Push notifications via FCM.

**AI features:**
- **Parent chatbot ("ask about my child").** Single endpoint that resolves intent → calls existing typed query methods (NOT raw SQL). E.g. "What's Aarav's attendance this month?" → calls a whitelisted, RBAC-checked function. *Effort: M. Impact: H. Critical: never let the LLM emit SQL; use a tool-call schema mapped to safe PHP methods.*
- **Smart-push composer.** Same FCM pipe, but message body authored by LLM with a per-parent tone profile (English / Hindi / Telugu / Tamil).
- **Voice notes → text** for low-literacy parents sending complaints.

### 2.11 Cross-module: `admin/dashboard`
**Today:** Aggregates many models in a single action.

**AI features:**
- **Natural-language dashboard query bar.** "How many class-7 students were absent on Monday and have unpaid May fees?" → structured filter, executed against existing search models with `with()` eager loading (also fixes N+1 along the way).
- **Anomaly digest.** Daily summary email to principal of unusual events (absent-spike in a section, fee collection drop, late-night logins).

---

## 3. Cross-Cutting AI Enhancements

| # | Capability | Why it fits this codebase | Effort |
|---|---|---|---|
| 1 | **Multilingual transactional email + push** (English + IN regional langs) | SendGrid + Brevo + FCM already in place; add an LLM translation step gated by template tag | S |
| 2 | **PII redaction layer** before any LLM call | CONCERNS.md flags raw input handling + no sanitization; an "outbound LLM gateway" is also the right place to scrub PII | M |
| 3 | **Embedding-based search over student/staff/document tables** | MySQL has FULLTEXT but it's keyword-only; embeddings unlock "find similar disciplinary cases", "duplicate parent profiles" | M |
| 4 | **Audit/AI-action log** | Every LLM call written to a `ai_invocations` table: who, model, prompt hash, cost, latency. Mandatory for a school context | S |
| 5 | **Per-tenant model & quota** | Multi-tenant via `campus_id`; each tenant gets its own monthly token budget + opt-out | S |
| 6 | **Caching layer for LLM responses** | FileCache already configured; reuse for prompt-hash → response caching where prompts are templated | S |

---

## 4. Architecture: Where AI Should Plug In

Yii2 already has the right seam — the **service/component layer in `components/`** (where `BrevoEmail.php`, `FirebaseNotification.php`, `RazorPay.php` live). Add:

```
components/
├── ai/
│   ├── AIClient.php           # LLM provider abstraction (Anthropic + fallback)
│   ├── PromptRegistry.php     # versioned, file-based prompts (no inline strings)
│   ├── PiiRedactor.php        # runs BEFORE every outbound call
│   ├── ResponseCache.php      # FileCache-backed
│   ├── AuditLogger.php        # writes ai_invocations table
│   └── tools/                 # safe PHP tool-call handlers for chat features
│       ├── StudentLookup.php
│       ├── AttendanceQuery.php
│       └── FeeStatement.php
```

Registration: `config/web.php` `components` array, same pattern as existing services. Access: `\Yii::$app->ai->complete(...)`.

**Hard rules baked in:**

- LLM never receives a database handle. Tool-call schema only.
- All prompts checked into git under `components/ai/prompts/`.
- Every call: redactor → cache check → provider → audit log → return.
- Server-side only. No API key ever ships to the browser.
- Default model: Claude Sonnet 4.6 (fast/cheap, capable); upgrade specific prompts (report-card narrative, complaint triage with nuance) to Opus 4.7.

---

## 5. Data Foundations Already in Place / Missing

**Already exists (good):**
- Rich relational schema (student_details, class_sections, attendance, marks, fees, leaves, library, inventory, hostel).
- Multi-tenant `campus_id` scoping.
- Role model + RBAC config.
- Migration-based schema evolution.

**Missing / needs adding before serious AI work:**
- A canonical **`students_denormalized` view or read-model** so the chatbot doesn't traverse 6 joins per question.
- An **`events` / activity stream** table — needed for the early-warning model and anomaly digest.
- A **document store abstraction** (currently `uploads/` is world-readable per CONCERNS — both a security and AI-ingestion blocker).
- **Embeddings table(s)** — `entity_type`, `entity_id`, `vector`, `model_version`. MySQL 8 can hold `JSON`; for cosine search use a sidecar (pgvector / Qdrant) or precompute kNN nightly.

---

## 6. Foundation Work to Do BEFORE Shipping AI Features

Lifted from `CONCERNS.md`. Every one of these is a blocker for "ship LLM-touching code to a school product":

| Priority | Issue | Why it blocks AI | Source |
|---|---|---|---|
| P0 | Hardcoded SendGrid + Razorpay + Google Maps keys in repo | Adding an LLM key here would leak immediately. Move all to `.env` + secrets manager first. | CONCERNS §Critical Security |
| P0 | No file upload validation | OCR / vision features require uploads. Validate MIME, size, scan before passing to any AI. | CONCERNS §Missing File Upload Validation |
| P0 | Raw `$_POST` handling, no input sanitization | Prompt injection becomes trivial. Add a sanitation layer before the LLM gateway. | CONCERNS §Raw Input Handling |
| P0 | Zero automated tests | Cannot ship AI features safely without at least snapshot tests on prompts + tool-call handlers. | CONCERNS §Missing Test Coverage |
| P1 | 16 raw SQL accesses in `modules/api/` | The chatbot will live in `api/`. Migrate these to ActiveRecord first, otherwise the chatbot can't share query methods. | CONCERNS §Direct Database Access |
| P1 | N+1 query patterns | Dashboard NL-query feature will multiply these. Add `with()` / `indexBy()`. | CONCERNS §N+1 Query Problems |
| P1 | 1.75 GB `backend.zip` + 228 MB `vendor.zip` in repo | Will bloat any CI for AI eval pipelines. Purge via BFG, add to `.gitignore`. | CONCERNS §Large Binary Archives |
| P2 | jQuery 1.11.2, deprecated SwiftMailer | Replace before adding a chat widget to the frontend (XSS risk on rendered LLM output). | CONCERNS §Dependencies at Risk |
| P2 | World-readable `uploads/` | OCR pipeline must store source images securely. | CONCERNS §Fragile Areas |

---

## 7. Suggested Roadmap (4 Phases)

**Phase 0 — Foundation (4-6 weeks).** Knock out P0 items in §6. Stand up `.env` + secrets, file upload validation, input sanitization, baseline Codeception unit tests on auth + fees + attendance. Done = safe to add an outbound network call.

**Phase 1 — Low-risk, high-leverage wins (4 weeks).**
- Report-card narrative generator (exammanagement).
- Leave-letter & email drafter (leavemanagement + comms).
- Ticket auto-classify + first-response draft (support).
- Build the `components/ai/` skeleton + audit log + cache.

**Phase 2 — Workflow accelerators (6-8 weeks).**
- Library ISBN/cover ingestion + semantic catalog search.
- Question-paper drafter + question bank.
- Inventory invoice OCR + PO drafter.
- Behavior observation summarizer (childassessment).

**Phase 3 — Conversational + predictive (8-10 weeks).**
- Parent chatbot in `api/` with whitelisted tool calls.
- Admin dashboard NL query bar.
- At-risk-student early-warning model + weekly principal digest.
- Anomaly digest job (console command under `commands/`).

---

## 8. Risks & Open Questions

1. **Data residency.** Indian school data — verify regulator stance + pick provider region accordingly. Affects whether you can call Anthropic / OpenAI directly or need a regional gateway.
2. **Parent consent for AI-authored comms.** Need an opt-in toggle on `parent` / `student` profile, surfaced before any AI-generated message goes out.
3. **Hallucination cost.** A wrong attendance number to a parent is worse than no answer. Tool-call architecture (§4) is non-negotiable; the LLM must not invent numbers.
4. **Cost.** Multi-tenant fan-out (many campuses, many users) can balloon token spend. Per-campus monthly quota + cache hit metrics required from day one.
5. **Teacher trust.** Roll out AI-authored narratives as *drafts that require teacher edit before save*, not auto-published.
6. **Language quality.** Verify LLM output quality in Hindi/Telugu/Tamil/Kannada/Marathi if those are target markets — sample-eval before launch.

---

## 9. Quick-Win Shortlist (if you only do 5 things)

1. Report-card narrative generator → `modules/exammanagement` (Phase 1).
2. Support ticket auto-classify + draft reply → `modules/support` (Phase 1).
3. Library ISBN/cover ingestion → `modules/librarymanagement` (Phase 2).
4. Parent chatbot over a whitelisted toolset → `modules/api` (Phase 3).
5. At-risk-student weekly digest to principals → cron under `commands/` (Phase 3).

These five touch every audience (teacher, admin, support staff, parent, principal), exercise every architectural seam, and each one is independently shippable.
