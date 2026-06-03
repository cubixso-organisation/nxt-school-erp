# Modules Overview — Nxt_backend

> Brief reference of every module in the Yii2 school ERP, derived from `.planning/codebase/*` and verified against `modules/` on disk.
> Date: 2026-05-13
> Stack: PHP 7.0+ · Yii2 2.0.5 · MySQL/MariaDB · AdminLTE 3 · jQuery 1.11.2

---

## Module Map at a Glance

| # | Module | Controllers | Models | Role |
|---|---|---:|---:|---|
| 1 | `admin/` | 101 | 218 | Core back-office — runs the whole school |
| 2 | `api/` | 17 | 0 | REST endpoints for mobile / web clients |
| 3 | `childassessment/` | 4 | 5 | Holistic merit & behavioural scoring |
| 4 | `comingsoon/` | 1 | 0 | Placeholder / "module coming soon" landing |
| 5 | `documentgenerator/` | 5 | 8 | PDF certificates, ID cards, bonafides |
| 6 | `exammanagement/` | 9 | 23 | Exams, schedules, grades, marksheets |
| 7 | `hostelmanagement/` | 9 | 13 | Hostels, rooms, wardens, hostel attendance |
| 8 | `inventory/` | 7 | 12 | Stock items, suppliers, issue/return |
| 9 | `leavemanagement/` | 3 | 6 | Staff leave types + applications |
| 10 | `librarymanagement/` | 6 | 12 | Books, racks, members, issue/return |
| 11 | `media/` | 1 | 2 | Image / media asset handling |
| 12 | `staffmanagement/` | 10 | 20 | Staff, payroll, attendance, designations |
| 13 | `support/` | 3 | 4 | Helpdesk: categories + solutions |
| — | `controllers/` (root) | 7 | — | Site, auth, user, settings (cross-cutting) |

Total: **~177 controllers**, **~323 module-scoped models** (excluding base/ subclasses and root `models/`).

---

## 1. `admin/` — Back-Office Core
**Scale:** by far the largest module — ~101 controllers, ~218 models.

**Covers:** admissions, students, classes, sections, teachers, attendance (class + bus), fees, payments, exams config, transport, dairy/diary, notices, notifications, dashboards, agents/leads, daycare, RBAC operations.

**Notable controllers:**
- Students/Classes: `StudentDetailsController`, `StudentClassController`, `ClassSectionsController`, `ClassRoomsController`, `ClassTeacherController`
- Attendance: `AttendanceSettingsController`, `AttendanceTimeTablesController`, `StudentClassAttendanceController`, `StudentAttendanceBusController`, `DaycareAttendanceController`
- Fees: `FeesTypsController`, `FeeStructuresController`, `AssignFeeToStudentController`, `PayFeesController`, `PaymentDetailsController`
- Transport: `BusDetailsController`, `BusRouteController`, `BusStatusController`, `StudentHasBusController`, `DriverHasBusController`
- Admissions: `AdmissionEnquirieController`, `AgentStudentJoinController`, `StudentDetailsAgentLeadController`
- Comms: `NotificationController`, `FcmNotificationController`, `EventNotificationSettingsController`
- Dashboards: `DashboardController` — aggregates BusDetails, StudentClass, TeacherAttendance, etc.

**Key concerns:** God-class controllers, N+1 query patterns in `StudentDetailsController`, large views.

---

## 2. `api/` — Mobile & External REST API
**Scale:** 17 controllers, **0 module-local models** (reuses root + admin models).

**Audience:** parent app, student app, teacher app, agent app, driver, accountant, chief warden, etc. — one controller per persona.

**Controllers (by persona):** `ParentController`, `TeacherController`, `AgentController`, `AccountantController`, `BusDriverController`, `BusCoOrdinatorController`, `ChiefWardenController`, plus module bridges (`ExamManagementController`, `HostelManagementController`, `LeaveManagementController`, `LibraryManagementController`, `ChildAssessmentController`, `PaymentController`, `StudentcertificatesController`), and `ManagementController`, `BKController`, `DefaultController`.

**Integration surface:** Firebase FCM device-token registration, Razorpay payment callbacks.

**Key concerns:** 16 instances of raw `Yii::$app->db` direct access — bypasses ORM, no input validation, hard to test (per CONCERNS.md).

---

## 3. `childassessment/` — Holistic Assessment
**Scale:** 4 controllers, 5 models.

**Covers:** non-academic merits/skills, assignment of merits to classes, per-student merit scores, certificate generation with QR codes.

**Controllers:** `ChildMeritController`, `MeritsAssignedToClassController`, `StudentMeritMarksController`, `DefaultController`.

**Use case:** teachers score students on traits (`max_marks`, `name`, `description`, `status`); QR-stamped certificates generated for parents.

---

## 4. `comingsoon/` — Placeholder
**Scale:** 1 controller, 0 models. Only a `DefaultController` rendering a "module coming soon" page. Likely a stub for unreleased features.

---

## 5. `documentgenerator/` — Certificates & ID Cards
**Scale:** 5 controllers, 8 models.

**Covers:** bonafide certificates, student certificates, ID card template designer (note: includes a `IdCardTemplateController copy.php` — leftover, flagged in CONCERNS.md as suspicious file).

**Stack:** `kartik-v/yii2-mpdf` for PDFs, `endroid/qr-code` for QR.

**Controllers:** `BonafideCertificateController`, `IdCardTemplateController`, `StudentcertificatesController`, `DefaultController`.

---

## 6. `exammanagement/` — Exams & Marksheets
**Scale:** 9 controllers, 23 models.

**Covers:** exam definitions, schedules (`ExamSchedules`), grade taxonomies (`Grade`, `GradeDefination`), marks division (internal/external), final marksheet generation, marksheet settings.

**Controllers:** `ExamSchedulesController`, `GradeController`, `GradeDefinationController`, `MarksDivitionController`, `ExamStudentMarksheetController`, `FinalMarksheetController`, `MarksheetSettingController`, plus default.

**Output:** PDF marksheets via mPDF, optional QR for verification.

---

## 7. `hostelmanagement/` — Boarding
**Scale:** 9 controllers, 13 models.

**Covers:** hostels, floors, rooms, hostel residents (`Hostellers`), warden + hosteller attendance, attendance settings.

**Controllers:** `HostelsController`, `FloorController`, `RoomsController`, `HostellersController`, `HostellersAttandanceController`, `WardenAttandanceController`, `HostlerAttendanceSettingsController`, plus default.

**Integrations:** `pigochu/yii2-jquery-locationpicker`, `components/DrivingDistance.php` (Google Maps — currently commented out).

---

## 8. `inventory/` — Stock
**Scale:** 7 controllers, 12 models.

**Covers:** inventory items, item categories, item stores, suppliers, stock-in (`AddItemStock`), issue/return tracking.

**Controllers:** `InventoryItemsController`, `ItemCategoryController`, `ItemStoreController`, `ItemSupplierListController`, `AddItemStockController`, `IssueReturnInventoryController`, plus default.

**Export:** Excel via `kartik-v/yii2-export`, `hscstudio/yii2-export`.

---

## 9. `leavemanagement/` — Staff Leave
**Scale:** 3 controllers, 6 models.

**Covers:** leave type catalogue, staff leave applications + approvals, email notifications.

**Controllers:** `StaffLeaveTypesController`, `StaffLeaveAppliedController`, default.

**Comms:** SendGrid email on leave events.

---

## 10. `librarymanagement/` — Library
**Scale:** 6 controllers, 12 models.

**Covers:** books catalogue, racks, members, issue/return, school-wise library segregation.

**Controllers:** `LibraryBooksController`, `LibraryRacksController`, `LibraryMembersController`, `IssueBooksController`, `LibrarySchoolsWiseController`, default.

---

## 11. `media/` — Media Assets
**Scale:** 1 controller, 2 models. Minimal — likely centralises image/file handling for other modules.

---

## 12. `staffmanagement/` — HR & Payroll
**Scale:** 10 controllers, 20 models.

**Covers:** staff designations, monthly payrolls, salary components, salary groups + component mapping, staff attendance, attendance settings.

**Controllers:** `StaffDesignationsController`, `MonthlyPayrollsController`, `SalaryComponentsController`, `SalaryGroupsController`, `SalaryGroupComponentsController`, `StaffAttendenceController`, `StaffAttendenceSettingsController`, plus default. (Note: spelling `Attendence` is a project-wide artefact.)

---

## 13. `support/` — Helpdesk
**Scale:** 3 controllers, 4 models.

**Covers:** ticket categories and solutions (knowledge base entries).

**Controllers:** `CategoryController`, `SolutionController`, `DefaultController`.

**Use case:** internal helpdesk; ripe for a RAG-style auto-answer layer.

---

## Root Controllers (`controllers/`)
Not a module, but the application-wide entry surface:

| Controller | Role |
|---|---|
| `SiteController.php` | Home, login, error pages, generic dashboards |
| `AuthController.php` | Login / logout / session bootstrap |
| `UserController.php` | User CRUD |
| `SettingController.php` | Global / dynamic settings (paired with `components/SettingConfig.php`) |
| `CentralDbController.php` | Multi-tenant / central database operations |
| `Controller.php` | Base controller (RBAC, behaviors) |
| `SiteController copy.php` | Leftover duplicate — flagged in CONCERNS.md |

---

## Cross-Module Themes

- **Multi-tenancy:** every business model carries `campus_id` (and `institute_id` upstream) — scoping is applied via `behaviors()`/search models, not at the DB level.
- **Naming:** `Controller` suffix; `BaseX` for Gii-generated parents; `XSearch` for grid search; `XQuery` for query builders. snake_case views.
- **External integrations:** SendGrid (email), Brevo (transactional email), Firebase FCM (push), Razorpay (payments), Google Maps (distance) — all in `components/`.
- **Output formats:** PDF (mPDF), Excel (PHPExcel), QR (endroid/qr-code), HTML via AdminLTE 3 + Kartik widgets.

## Cross-Module Concerns (from `.planning/codebase/CONCERNS.md`)

- Zero automated tests across the entire codebase.
- Hardcoded API keys (SendGrid, Razorpay, Google Maps) committed in `config/`.
- Raw input handling, missing file upload validation, weak CSRF.
- N+1 query patterns concentrated in `admin/StudentDetails*` and `api/`.
- Large binary archives in repo (`backend.zip` 1.75 GB, `vendor.zip` 228 MB).
- Deprecated SwiftMailer, jQuery 1.11.2.

---

## Companion Documents

- `.planning/codebase/STACK.md`, `ARCHITECTURE.md`, `STRUCTURE.md`, `CONVENTIONS.md`, `INTEGRATIONS.md`, `TESTING.md`, `CONCERNS.md`
- `.planning/AI-ENHANCEMENTS.md` — module-by-module AI feature recommendations
- `.planning/AI-AUTOMATIONS-TIMETABLES-FEES.md` — deep-dive on the two highest-leverage subsystems
