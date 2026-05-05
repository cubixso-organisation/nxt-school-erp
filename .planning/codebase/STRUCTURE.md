# Codebase Structure

**Analysis Date:** 2026-04-28

## Directory Layout

```
Nxt_backend/
├── index.php                   # Web application entry point (bootstraps Yii)
├── yii                         # Console command runner script
├── composer.json               # PHP dependency manifest
├── composer.lock               # Locked dependency versions
│
├── config/                     # Application configuration files
│   ├── web.php                 # Web app config (routing, components, modules, mailer)
│   ├── console.php             # Console app config (CLI commands)
│   ├── rbac.php                # Role-based access control rules
│   ├── db.php                  # Database connection configuration
│   ├── params.php              # Application parameters
│   └── test.php                # Test environment configuration
│
├── controllers/                # Root-level HTTP controllers
│   ├── Controller.php          # Base controller class (extends \yii\web\Controller)
│   ├── SiteController.php      # Homepage, auth (login/register)
│   ├── UserController.php      # User account operations
│   ├── AuthController.php      # Authentication flows
│   ├── SettingController.php   # Application settings
│   └── CentralDbController.php # Central database operations
│
├── models/                     # Root-level data models
│   ├── ActiveRecord.php        # Base ActiveRecord with shared logic
│   ├── Model.php               # Base form/model class with helper methods
│   ├── User.php                # User identity model (implements IdentityInterface)
│   ├── LoginForm.php           # Login form validation model
│   ├── Auth.php                # OAuth/Auth provider model
│   ├── AuthSession.php         # Session tracking model
│   ├── CentralDb.php           # Central database configuration model
│   ├── CentralDbQuery.php      # Query builder for CentralDb
│   └── [40+ additional models] # Other domain models (see file count)
│
├── views/                      # Root-level view templates
│   ├── layouts/
│   │   └── main.php            # Main layout template (HTML structure)
│   ├── site/
│   │   ├── index.php           # Homepage view
│   │   ├── login.php           # Login form view
│   │   ├── register.php        # Registration form view
│   │   ├── error.php           # Error page view
│   │   └── [other site pages]
│   ├── auth/                   # Authentication-related views
│   ├── hostels/                # Hostel-related root views
│   ├── partials/               # Reusable partial templates
│   └── includes/               # Common includes (header, footer, sidebar)
│
├── modules/                    # Feature modules (self-contained units)
│   ├── admin/                  # Core admin module (highest traffic)
│   │   ├── Module.php          # Module bootstrap (configures routing, containers)
│   │   ├── controllers/        # 103+ admin controllers (DashboardController, StudentDetailsController, etc.)
│   │   ├── models/             # 222+ admin models (Campus, StudentDetails, ClassSections, etc.)
│   │   ├── views/              # 103+ views organized by controller
│   │   ├── widgets/            # Admin-specific reusable components
│   │   ├── forms/              # Admin form models for validation
│   │   ├── config/
│   │   │   ├── main.php        # Controller namespace, view paths, widget configs
│   │   │   ├── container.php   # Service container bindings for admin module
│   │   │   └── [other configs]
│   │   ├── assets/             # CSS/JS bundles for admin module
│   │   └── migrations/         # Admin-specific database schema changes
│   │
│   ├── api/                    # REST API endpoints module
│   │   ├── Module.php
│   │   ├── controllers/        # API controllers (RESTful endpoints)
│   │   ├── models/
│   │   └── [standard module structure]
│   │
│   ├── exammanagement/         # Exam & grading functionality
│   │   ├── Module.php
│   │   ├── controllers/        # Exam controllers
│   │   ├── models/             # Exam-related models
│   │   ├── views/
│   │   ├── widgets/
│   │   └── [standard module structure]
│   │
│   ├── staffmanagement/        # Staff/teacher operations
│   ├── leavemanagement/        # Leave request & approval workflows
│   ├── librarymanagement/      # Library book tracking
│   ├── hostelmanagement/       # Hostel & dormitory operations
│   ├── childassessment/        # Student merit/assessment system
│   ├── inventory/              # Inventory tracking
│   ├── documentgenerator/      # Document generation (reports, certificates)
│   ├── media/                  # Media/file management
│   ├── support/                # Support/help module
│   ├── comingsoon/             # Coming soon placeholder pages
│   └── [all others follow same pattern]
│
├── components/                 # Application services and utilities
│   ├── BaseController.php      # Base controller functionality
│   ├── BaseActiveRecord.php    # Base model functionality
│   ├── BaseWidget.php          # Base widget functionality
│   ├── SettingConfig.php       # Dynamic settings service
│   ├── AccessRule.php          # Custom access rule logic
│   ├── AuthHandler.php         # OAuth/auth provider handling
│   ├── AuthSettings.php        # Auth configuration service
│   ├── FirebaseNotification.php # Firebase push notifications
│   ├── BrevoEmail.php          # Brevo email service integration
│   ├── RazorPay.php            # Razorpay payment processing
│   ├── SendOtp.php             # OTP sending service
│   ├── DrivingDistance.php     # Distance calculation service
│   ├── Toast.php               # Toast notification rendering
│   ├── documentGenerator.php   # Document generation wrapper
│   ├── OrderStats.php          # Order statistics calculations
│   ├── Dashboard.php           # Dashboard data aggregation
│   ├── BasePageHeader.php      # Page header component
│   ├── BaseGridView.php        # Data grid display component
│   ├── BaseUserAction.php      # User action handling
│   ├── BaseActionColumn.php    # Action column for grids
│   ├── forms/                  # Form helpers and builders
│   ├── views/                  # Component view templates
│   ├── widgets/                # Component-level widgets
│   └── [additional services]
│
├── widgets/                    # Reusable view components (application-wide)
│   ├── Alert.php               # Alert/notification widget
│   ├── FlashAlert.php          # Flash message widget
│   └── Block.php               # Content block widget
│
├── forms/                      # Root-level form models (validation only)
│   ├── LoginForm.php           # Login validation
│   ├── RegisterForm.php        # Registration validation
│   ├── LoginForm.php           # Password request validation
│   ├── PasswordUpdateForm.php  # Password change validation
│   └── ContactForm.php         # Contact form validation
│
├── migrations/                 # Database schema versioning
│   ├── m180121_180103_add_email_template.php
│   ├── m180429_172439_create_coupons_applied_table.php
│   ├── m180501_160231_create_auth_table.php
│   ├── m180526_174633_create_whishlist_table.php
│   ├── [80+ additional migrations spanning 2018-2025]
│   └── [each file: CREATE/ALTER/DROP table operations with up/down methods]
│
├── commands/                   # Console command controllers
│   └── HelloController.php     # Example console command
│
├── traits/                     # Reusable behavior mixins
│   ├── controllers/            # Controller traits
│   │   └── [custom controller behaviors]
│   ├── models/                 # Model traits
│   │   └── WithStatus.php      # Status constants and helpers (used by User)
│   └── migrations/             # Migration helper traits
│
├── i18n/                       # Internationalization (translations)
│   ├── app.php                 # Application translation strings
│   └── [language-specific translations]
│
├── themes/                     # Theme assets and helpers
│   ├── jpgraph/                # JPGraph charting library
│   │   └── src/
│   │       ├── jpgraph.php     # Core charting library
│   │       └── jpgraph_bar.php # Bar chart component
│   └── [other theme files]
│
├── web/                        # Public web root (static files)
│   ├── index.php               # Alternative web entry point
│   ├── css/                    # Stylesheets (generated/compiled)
│   ├── js/                     # JavaScript (generated/compiled)
│   └── images/                 # Static image assets
│
├── assets/                     # Compiled asset bundles (auto-generated)
│   ├── [auto-generated during publish-asset]
│   └── [CSS, JS bundles published from components and modules]
│
├── uploads/                    # User-uploaded files (runtime)
│   ├── [dynamically created by application]
│   └── [organized by upload type/date]
│
├── runtime/                    # Application-generated runtime files
│   ├── logs/                   # Application logs (errors, warnings, debug)
│   │   └── app.log             # Main application log
│   ├── cache/                  # File-based cache data
│   ├── gii/                    # Gii code generator temporary files
│   └── [other temporary runtime data]
│
├── vendor/                     # Composer-installed dependencies (DO NOT EDIT)
│   ├── yiisoft/                # Yii framework files
│   ├── phpoffice/phpexcel/     # Excel export library
│   ├── mpdf/mpdf/              # PDF generation library
│   ├── swift_mailer/           # Email sending library
│   ├── kartik-v/               # Kartik widgets (grid, date pickers, etc.)
│   └── [80+ other composer packages]
│
└── .planning/codebase/         # GSD planning documentation
    ├── ARCHITECTURE.md         # This layer/component explanation
    ├── STRUCTURE.md            # This file directory layout
    ├── CONVENTIONS.md          # Code style & naming rules (future)
    ├── TESTING.md              # Testing patterns (future)
    ├── STACK.md                # Technology stack (future)
    ├── INTEGRATIONS.md         # External service integrations (future)
    └── CONCERNS.md             # Technical debt & issues (future)
```

## Directory Purposes

**config/**
- Purpose: Application configuration across environments (web, console, test, RBAC)
- Contains: Database credentials, component definitions, module declarations, routing setup
- Key files: `web.php` (main), `db.php` (database), `rbac.php` (access control), `params.php` (global params)

**controllers/**
- Purpose: Handle root-level HTTP requests not belonging to any module
- Contains: Site (homepage/auth), User account, Central database, Settings
- Key files: `SiteController.php`, `AuthController.php`, `UserController.php`

**models/**
- Purpose: Root-level domain models for application-wide entities
- Contains: User, Auth, LoginForm, and 40+ other models
- Key files: `ActiveRecord.php` (base), `User.php` (identity), `Model.php` (form base)

**views/**
- Purpose: Render HTML output for root-level controllers
- Contains: Layouts (main.php), auth views, site pages, partials
- Key files: `layouts/main.php` (base layout), `site/login.php`, `site/index.php`

**modules/**
- Purpose: Self-contained feature modules with MVC structure
- Contains: 14 modules, each with controllers (up to 103), models (up to 222), views (up to 103)
- Key modules: `admin/` (largest - main application), `api/` (REST endpoints), exam/staff/leave/library/hostel management
- Pattern: Each module bootstraps via `Module.php`, configures namespace/layout in `config/main.php`

**components/**
- Purpose: Reusable application services and utilities
- Contains: Email (BrevoEmail), Payments (RazorPay), Notifications (Firebase), Widgets (Grid, Header), OTP, Distance calc
- Registration: Declared in `config/web.php` under `components` key, accessible via `\Yii::$app->componentName`

**widgets/**
- Purpose: Reusable view components for common UI patterns
- Contains: Alert, FlashAlert, Block widgets
- Usage: Embedded in views via `<?= Alert::widget([...]) ?>`

**forms/**
- Purpose: Form validation models (separate from persistence)
- Contains: LoginForm, RegisterForm, PasswordRequestForm, PasswordUpdateForm, ContactForm
- Pattern: Extend `yii\base\Model`, implement `load()` + `validate()` in controller

**migrations/**
- Purpose: Database schema versioning and evolution
- Contains: 80+ timestamped migration files (m18xxxxx_xxxxxx_*.php)
- Pattern: Each file extends `yii\db\Migration`, implements `safeUp()` (create) and `safeDown()` (revert)
- Usage: Run with `php yii migrate` command, tracked in `migration` table

**commands/**
- Purpose: Console (CLI) command controllers
- Contains: HelloController.php (example)
- Pattern: Extend `yii\console\Controller`, implement action methods, run via `php yii namespace/action`

**traits/**
- Purpose: Reusable behavior mixins across models and controllers
- Contains: WithStatus (User status constants), custom controller/model behaviors
- Location: `traits/models/`, `traits/controllers/`, `traits/migrations/`

**i18n/**
- Purpose: Internationalization and translations
- Contains: `app.php` (translation messages)
- Usage: Referenced in `config/web.php` i18n component, accessed via `\Yii::t('app', 'message')`

**themes/**
- Purpose: Theme assets and charting libraries
- Contains: JPGraph charting library (jpgraph.php, jpgraph_bar.php)
- Usage: Required in `index.php`, used for server-side chart rendering

**web/**
- Purpose: Public web root for static assets
- Contains: Compiled CSS/JS, images
- Served by: Web server directly (static file serving)

**assets/**
- Purpose: Compiled asset bundles (auto-generated, DO NOT COMMIT)
- Generated by: Yii asset publishing when components/modules define AssetBundle classes
- Includes: Vendor CSS/JS from node_modules, component-specific bundles

**uploads/**
- Purpose: User-uploaded files at runtime (DO NOT COMMIT)
- Generated by: Application when users upload documents, images, etc.
- Path constant: `UPLOAD_PATH` defined in `index.php`

**runtime/**
- Purpose: Application-generated files at runtime (DO NOT COMMIT)
- Contains: Logs (app.log), cache files, Gii temporary files
- Folders: `logs/` (application logs), `cache/` (file cache), `gii/` (code generator)

**vendor/**
- Purpose: Composer-installed PHP dependencies (DO NOT EDIT/COMMIT)
- Contains: Yii framework, form builders, charting, PDF/Excel, email libraries, Kartik widgets
- Generated by: `composer install` / `composer update`

## Key File Locations

**Entry Points:**
- `index.php`: Web application bootstrap (initializes Yii, loads config, runs app)
- `yii`: Console command entry point (bootstraps console app)
- `web/index.php`: Alternative web entry point

**Configuration:**
- `config/web.php`: Main web application config (components, modules, routing)
- `config/db.php`: Database connection credentials
- `config/rbac.php`: Role-based access control definitions
- `config/console.php`: Console application config
- `config/params.php`: Global application parameters

**Core Logic:**
- `models/ActiveRecord.php`: Base database model class with shared ORM logic
- `models/User.php`: User identity model (session, roles, auth)
- `components/SettingConfig.php`: Dynamic application settings service
- `modules/admin/Module.php`: Admin module bootstrap (largest module)

**Testing:**
- `config/test.php`: Test environment configuration
- No detected test directories (test coverage needs assessment)

## Naming Conventions

**Files:**
- **Controllers:** PascalCase + `Controller` suffix (e.g., `DashboardController.php`, `StudentDetailsController.php`)
- **Models:** PascalCase (e.g., `User.php`, `StudentDetails.php`, `ClassSections.php`)
- **Query classes:** Model name + `Query` suffix (e.g., `UserQuery.php`, `StudentDetailsQuery.php`)
- **Search models:** Model name + `Search` suffix (e.g., `StudentDetailsSearch.php`)
- **Migrations:** `m` + timestamp + `_` + description (e.g., `m180501_160231_create_auth_table.php`)
- **Views:** snake_case (e.g., `student-details.php`, `class-sections.php`)
- **Widgets:** PascalCase (e.g., `Alert.php`, `FlashAlert.php`)
- **Forms:** PascalCase + `Form` suffix (e.g., `LoginForm.php`, `RegisterForm.php`)

**Directories:**
- **Controllers:** `controllers/`, `modules/{module}/controllers/`
- **Models:** `models/`, `modules/{module}/models/`
- **Views:** `views/`, `modules/{module}/views/{controller-name}/`
- **Modules:** snake_case lowercase (e.g., `exammanagement/`, `staffmanagement/`)
- **Database tables:** snake_case lowercase (from migrations, e.g., `student_details`, `class_sections`)
- **Database columns:** snake_case lowercase (e.g., `first_name`, `user_id`, `created_at`)

**Classes:**
- **Namespace:** `app\Controllers`, `app\Models`, `app\modules\{ModuleName}\controllers`, `app\modules\{ModuleName}\models`
- **Base classes:** Prefixed with `Base` (e.g., `BaseController.php`, `BaseActiveRecord.php`)
- **Traits:** CamelCase describing behavior (e.g., `WithStatus`)
- **Properties:** camelCase (e.g., `$firstName`, `$userId`, `$createdAt`)

## Where to Add New Code

**New Admin Feature (CRUD):**
- Primary code: `modules/admin/controllers/NewFeatureController.php`
- Data model: `modules/admin/models/NewFeature.php`
- Query builder: `modules/admin/models/NewFeatureQuery.php` (auto-generated by Gii or manual)
- Views: `modules/admin/views/new-feature/{action}.php` (index, view, create, update, delete)
- Form model (if separate): `modules/admin/forms/NewFeatureForm.php`
- Tests: No existing test directory; add to `tests/` (future)
- Migration: `migrations/m{timestamp}_create_new_feature_table.php`

**New API Endpoint:**
- Controller: `modules/api/controllers/ResourceController.php`
- Model: Reuse existing `models/` or create module-specific in `modules/api/models/`
- Response formatting: Yii's `yii\rest\ActiveController` for REST conventions
- Tests: No existing API tests; add to `tests/api/` (future)

**New Root Controller:**
- Controller: `controllers/NewFeatureController.php`
- Views: `views/new-feature/{action}.php`
- Routes automatically configured in `config/web.php` default routing

**Shared Service/Component:**
- Implementation: `components/NewService.php` (extends any base needed)
- Registration: Add to `components` array in `config/web.php`
- Usage: Access via `\Yii::$app->newService`

**Shared Model/Entity:**
- Root-level model: `models/NewEntity.php` (extends `app\models\ActiveRecord`)
- Module-specific: `modules/{module}/models/NewEntity.php`
- Query builder: `models/NewEntityQuery.php` (auto-generated)
- Database: Create migration in `migrations/` folder

**Reusable Widget:**
- Application-wide: `widgets/NewWidget.php`
- Module-specific: `modules/{module}/widgets/NewWidget.php`
- Views: Accompanying `views/new-widget.php` if needed
- Usage: `<?= NewWidget::widget(['param' => $value]) ?>`

**Form Validation:**
- Root-level form: `forms/NewForm.php`
- Module-specific form: `modules/{module}/forms/NewForm.php`
- Extend `yii\base\Model`, define `rules()` with validators

**Database Change:**
- Create migration: `migrations/m{timestamp}_{description}.php`
- Pattern: Extend `yii\db\Migration`, implement `safeUp()` and `safeDown()`
- Run: `php yii migrate`
- Never manually edit tables; use migrations for version control

## Special Directories

**vendor/:**
- Purpose: Composer-managed PHP dependencies
- Generated: Yes (by `composer install`)
- Committed: No (.gitignore includes vendor/)
- Size: 59 subdirectories (framework, libraries, plugins)
- Update: Run `composer update` or `composer require package-name`

**runtime/:**
- Purpose: Application-generated files at runtime
- Generated: Yes (by application on first run)
- Committed: No (.gitignore includes runtime/)
- Contents: Logs (logs/app.log), cache (cache/), Gii temporary files
- Cleanup: Safe to delete; application recreates as needed

**uploads/:**
- Purpose: User-uploaded files
- Generated: Yes (by application when users upload)
- Committed: No (.gitignore includes uploads/)
- Path: Defined by `UPLOAD_PATH` constant in `index.php` as `__DIR__ . '/uploads'`
- Organization: Can organize by type, date, or user subdirectories

**assets/:**
- Purpose: Published asset bundles (CSS, JS, images) from components
- Generated: Yes (by Yii's asset publisher)
- Committed: No (.gitignore includes assets/)
- Contents: Vendor CSS/JS, component-specific bundles with hashed names for cache-busting
- Republish: Run `php yii asset` command to re-publish all bundles

---

*Structure analysis: 2026-04-28*
