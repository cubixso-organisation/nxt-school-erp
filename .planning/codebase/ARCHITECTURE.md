# Architecture

**Analysis Date:** 2026-04-28

## Pattern Overview

**Overall:** Yii2 MVC (Model-View-Controller) with Modular Architecture

**Key Characteristics:**
- Multi-module system for feature isolation (admin, api, exammanagement, staffmanagement, etc.)
- ActiveRecord ORM for database access
- RESTful routing with action-based controllers
- Role-based access control (RBAC) integrated at controller level
- Component-based extensibility with custom service components
- Migration-based database schema management

## Layers

**Controller Layer:**
- Purpose: Handle HTTP requests, route to actions, manage request/response lifecycle
- Location: `controllers/` (frontend), `modules/*/controllers/` (module-specific)
- Contains: Action handlers, behavior filters (access control, verb validation), request validation
- Depends on: Models, Forms, Services (components)
- Used by: Web server (index.php entry point)
- Example: `modules/admin/controllers/DashboardController.php` — dashboard and admin operations

**Model Layer:**
- Purpose: Encapsulate business logic, database operations, validation rules
- Location: `models/` (application-wide), `modules/*/models/` (module-specific)
- Contains: ActiveRecord classes (extending `yii\db\ActiveRecord`), form models, search models
- Depends on: Database (via Yii ORM), Migrations (schema definitions)
- Used by: Controllers, Services, Forms
- Pattern: Base models in `models/ActiveRecord.php` and `models/Model.php` provide shared functionality

**View Layer:**
- Purpose: Render HTML output for web requests
- Location: `views/` (application-wide), `modules/*/views/` (module-specific)
- Contains: PHP template files organized by controller action names
- Depends on: Models (for data), Layouts (for structure), Widgets (for reusable components)
- Used by: Controllers via `render()` method
- Layout hierarchy: `views/layouts/` → `modules/*/layouts/` → per-action templates

**Service/Component Layer:**
- Purpose: Provide cross-cutting services (auth, email, notifications, payments, file handling)
- Location: `components/` — custom services like `SettingConfig.php`, `FirebaseNotification.php`, `RazorPay.php`, `BrevoEmail.php`
- Depends on: External APIs/services, Configuration
- Used by: Controllers, Models, other components
- Registration: Configured in `config/web.php` under `components` section

**Database/Migration Layer:**
- Purpose: Define and evolve database schema through timestamped migrations
- Location: `migrations/` — numbered migration files like `m180501_160231_create_auth_table.php`
- Contains: Schema up/down operations using Yii migration API
- Used by: Yii migration runner (`yii migrate` command)

## Data Flow

**Request → Response Cycle:**

1. **Entry Point:** `index.php` → Initializes Yii application with merged `config/web.php` and `config/rbac.php`
2. **Routing:** Request routed by Yii's `yii\web\Application` to controller action
3. **Access Control:** `behaviors()` in Controller checks RBAC rules (admin, campus admin, teacher, parent, student roles)
4. **Controller Action:** Prepares data, instantiates models, calls business logic
5. **Model Query:** ActiveRecord models fetch/manipulate data via `find()`, `save()`, `delete()` methods
6. **Response:** Controller calls `render()` to render view template with model data
7. **Output:** View template renders HTML using layouts and widgets

**Example: Admin Dashboard:**
- Request: `GET /admin/dashboard/index`
- Routing: Matches module `admin`, controller `dashboard`, action `index`
- Access Check: `DashboardController::behaviors()` verifies user is admin/institute-admin/campus-admin
- Action: `actionIndex()` queries multiple models (BusDetails, StudentClass, TeacherAttendance, etc.)
- View: Renders `modules/admin/views/dashboard/index.php` with aggregated data

**State Management:**
- **User Session:** Stored via `User` model implementing `yii\web\IdentityInterface`, stored in `auth` table
- **Authentication:** LoginForm validates credentials, session managed by Yii's User component
- **Module State:** Each module initializes in `Module::init()`, can configure service container
- **Cache:** File-based cache (`yii\caching\FileCache`) for performance, controlled by `config/web.php`

## Key Abstractions

**ActiveRecord Models:**
- Purpose: Map database tables to PHP objects with CRUD operations
- Examples: `models/User.php`, `modules/admin/models/StudentDetails.php`, `modules/admin/models/Campus.php`
- Pattern: Extend `app\models\ActiveRecord` or `yii\db\ActiveRecord`, define property phpdoc, validation rules in `rules()`, scenarios
- Query builders: Auto-generated Query classes like `StudentDetailsQuery.php` for fluent SQL building

**Forms (Form Models):**
- Purpose: Separate form validation/submission from persistence
- Examples: `forms/LoginForm.php`, `forms/RegisterForm.php`, `forms/PasswordUpdateForm.php`
- Pattern: Extend `yii\base\Model`, define `rules()` for validation, implement submission in controller
- Usage: Controllers instantiate, validate with `load()` + `validate()`, delegate persistence to ActiveRecord

**Modules:**
- Purpose: Namespace features into self-contained units (admin, exammanagement, staffmanagement, leavemanagement, librarymanagement, hostelmanagement, childassessment, inventory, documentgenerator, api, media, support, comingsoon)
- Structure: Each module has `Module.php` (bootstrap), `controllers/`, `models/`, `views/`, `widgets/`, `forms/`, `assets/`, `config/`
- Module.php: Defines `$layout`, `$defaultRoute`, loads module config + container config
- Registration: Declared in `config/web.php` under `modules` key
- Access: Routes like `/admin/controller/action` invoke `modules/admin/controllers/ControllerController`

**Traits:**
- Purpose: Reusable behavior mixins across models and controllers
- Examples: `app\traits\models\WithStatus` (used in User model for status constants)
- Location: `traits/controllers/`, `traits/models/`

**Widgets:**
- Purpose: Reusable view components for common UI patterns
- Examples: `Alert.php`, `FlashAlert.php`, `Block.php` in `widgets/`
- Module widgets: Each module has `widgets/` subdirectory for domain-specific widgets
- Usage: Rendered in views via `<?= Widget::widget(['param' => $value]) ?>`

## Entry Points

**Web Entry Point:**
- Location: `index.php` and `web/index.php`
- Triggers: HTTP requests to application root
- Responsibilities:
  - Load composer autoloader and Yii framework
  - Define constants (YII_DEBUG, YII_ENV, STATUS_SUCCESS, UPLOAD_PATH)
  - Load and merge web config with RBAC config
  - Instantiate `yii\web\Application` and invoke `run()`
  - Catch uncaught exceptions and errors

**Console Entry Point:**
- Location: `yii` (executable script in project root)
- Triggers: CLI commands like `php yii migrate`
- Responsibilities: Bootstrap console app with `config/console.php`, run command controllers in `commands/`
- Commands: Database migrations, background tasks, batch operations

**Module Entry Points:**
- Each module's `Module.php` boots module configuration
- Module config files (e.g., `modules/admin/config/main.php`) define controller namespace, view paths, widget namespace
- Container config files (e.g., `modules/admin/config/container.php`) register service bindings

## Error Handling

**Strategy:** Centralized error handling with custom error pages per environment

**Patterns:**
- `config/web.php` registers `errorHandler` component pointing to `site/error` action
- `admin` module overrides to `admin/dashboard/error` for custom admin error pages
- Controllers catching exceptions can `throw new \yii\web\NotFoundHttpException()`, `\yii\web\BadRequestHttpException()`, etc.
- Behavior filters (AccessControl) throw `\yii\web\ForbiddenHttpException` for unauthorized access
- Database errors caught and logged to `runtime/logs/`

## Cross-Cutting Concerns

**Logging:**
- Framework: Yii's `\yii\log\FileTarget`
- Location: `runtime/logs/` directory
- Configured in `config/web.php` under `log` component
- Usage: `\Yii::error()`, `\Yii::warning()`, `\Yii::info()` for application logs
- Example in code: DashboardController logs access checks with `\Yii::error()`

**Validation:**
- Model validation: `rules()` method defines validation on ActiveRecord and Form models
- Built-in validators: required, string, integer, email, unique, date, range
- Custom validators: Can extend `yii\validators\Validator` and reference in rules
- Trigger: Auto-validated in `load()` + `validate()` or explicit `validate()` call

**Authentication & Authorization:**
- Auth: User component configured with `User` model as `identityClass`
- Session: Yii session storage, cookie-based authentication with auth key
- RBAC: Configured in `config/rbac.php` file (separate config loaded in index.php)
- Access Control: Implemented in controller `behaviors()` using `yii\filters\AccessControl`
- User roles: Constants defined in `User` model (ROLE_ADMIN, ROLE_TEACHER, ROLE_PARENT, ROLE_STUDENT, etc.)
- Helper methods: `User::isAdmin()`, `User::isTeacher()`, etc. check role from `user_role` field

**Database Connections:**
- Configuration: `config/db.php` loads DSN, username, password from environment or hardcoded
- Connection pool: Single DB component accessible via `\Yii::$app->db`
- Query builder: `ModelClass::find()` returns query builder for fluent SQL construction
- Transactions: Can wrap operations in `\Yii::$app->db->transaction()` for ACID guarantees

---

*Architecture analysis: 2026-04-28*
