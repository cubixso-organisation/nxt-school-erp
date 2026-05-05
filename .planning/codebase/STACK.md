# Technology Stack

**Analysis Date:** 2026-04-28

## Languages

**Primary:**
- PHP 7.0+ - Server-side application logic, controllers, models, API endpoints

**Secondary:**
- JavaScript - Frontend interactions, jQuery (via CDN)

## Runtime

**Environment:**
- PHP-FPM or Apache with PHP module
- Apache (.htaccess support required)

**Package Manager:**
- Composer 1.x/2.x - PHP dependency management
- Lockfile: `composer.lock` present
- npm 6+ - Node.js package manager for mail/PDF utilities
- Lockfile: `package-lock.json` (implicit)

## Frameworks

**Core:**
- Yii2 ~2.0.5 - Full-stack PHP framework
- Yii2-Bootstrap ~2.0.0 - Bootstrap integration
- Yii2-Bootstrap4 ^2.0 - Bootstrap 4 UI components

**UI & Widgets:**
- AdminLTE ~3.0 - Admin dashboard template
- Kartik Yii2 Widgets (GridView, Select2, DateControl, Export, Tabs, DateRange, TabsX, Bootstrap4 Dropdown, Editable, TreeManager, CheckboxX, FieldRange)
- Yii2-JUI ^2.0 - jQuery UI integration
- yii2-mdbootstrap ^1.0 - Material Design Bootstrap

**Testing (Development):**
- Codeception ^2.2.3 - BDD/functional testing framework
- Codeception/verify ~0.3.1 - Assertion library
- Codeception/specify ~0.4.3 - Test organization

**Development Tools:**
- Yii2-Debug ~2.0.0 - Debugging toolbar
- Yii2-Gii ~2.0.0 - Code generator
- Yii2-Faker ~2.0.0 - Fake data generation
- Yii2-Log-Reader 2.* - Log viewer

## Key Dependencies

**Critical (Project-Specific):**
- `endroid/qr-code` ^4.6 - QR code generation for certificates/documents
- `google/apiclient` 2.0 - Google APIs (Firebase, Google Maps)
- `yii2tech/embedded` ^1.0 - Embedded model relationships

**PDF & Document Generation:**
- `kartik-v/yii2-mpdf` dev-master - mPDF wrapper for PDF generation
- `moonlandsoft/yii2-phpexcel` - Excel file handling
- `hscstudio/yii2-export` 1.0.0 - Data export functionality
- `pdfkit` ^0.15.1 (npm) - PDF generation on Node.js side

**Email & Notifications:**
- `yiisoft/yii2-swiftmailer` ~2.0.0 - SwiftMailer (configured with SendGrid SMTP)
- `nodemailer` ^6.9.16 (npm) - Node.js email sending

**UI & Forms:**
- `mihaildev/yii2-ckeditor` * - WYSIWYG editor
- `wbraganca/yii2-dynamicform` * - Dynamic form fields
- `unclead/yii2-multiple-input` ~2.0 - Multiple input handling
- `yiisoft/yii2-authclient` * - OAuth/social authentication
- `pigochu/yii2-jquery-locationpicker` >=0.2.0 - Location picker

**Data Visualization:**
- `miloschuman/yii2-highcharts-widget` ^8.0 - Highcharts.js integration

**Maps & Geolocation:**
- `2amigos/yii2-google-maps-library` * - Google Maps integration
- Google Maps API Key: `<REDACTED — set GOOGLE_MAPS_API_KEY in .env>` (loaded via env in `config/web.php`)

**Utilities:**
- `mootensai/yii2-relation-trait` dev-master - Relationship helpers
- `mootensai/yii2-enhanced-gii` dev-master - Enhanced code generator
- `warrence/yii2-kartikgii` dev-master - Gii customization
- `sjaakp/yii2-loadmore` ^1.0 - Infinite scroll
- `kriss/yii2-log-reader` 2.* - Log viewing
- `lo/yii2-noty` (implied) - Notification system

**Email Templates:**
- `bl/yii2-email-templates` - Email template management

## Configuration

**Environment:**
- Configuration-driven via PHP files in `config/` directory
- Environment detection: `YII_ENV` and `YII_DEBUG` constants
- Timezone: Asia/Kolkata (hardcoded in `config/web.php`)

**Database:**
- Connection: `config/db.php`
- Credentials: Username and password hardcoded (SECURITY CONCERN)
- Database name: `nxt_backend`
- Charset: `utf8mb4` with `utf8mb4_unicode_ci` collation
- Schema caching: Enabled (3600s duration)

**Caching:**
- Cache type: File-based (`yii\caching\FileCache`)

**Web Configuration:**
- Pretty URLs enabled (no `index.php` in routes)
- Cookie validation key: `<REDACTED — set COOKIE_VALIDATION_KEY in .env>`

**Key Config Files:**
- `config/web.php` - Web application configuration, module routes, component setup
- `config/db.php` - Database connection
- `config/console.php` - Console commands configuration
- `config/params.php` - Application parameters
- `config/rbac.php` - Role-based access control

## Platform Requirements

**Development:**
- PHP 7.0 or higher
- MySQL/MariaDB server (local or remote)
- Composer installed
- Node.js 6+ for npm dependencies (Nodemailer, PDFKit)
- Apache with mod_rewrite for URL routing
- Extension: `cURL` (used throughout for API calls)
- Extension: `OpenSSL` (for SMTP encryption)
- Extension: `GD or ImageMagick` (for QR code, image manipulation)

**Production:**
- PHP-FPM or Apache module
- MySQL 5.7+ or MariaDB 10.2+
- Writable directories: `runtime/`, `uploads/`, `web/assets/`
- SendGrid SMTP access (for email)
- Firebase credentials (for push notifications)
- Razorpay API keys (for payment processing)
- Brevo (Sendinblue) API key (for transactional email)

---

*Stack analysis: 2026-04-28*
