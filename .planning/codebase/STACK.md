# Technology Stack

**Analysis Date:** 2026-06-04

## Languages

**Primary:**
- PHP 7.0+ - All backend business logic, controllers, models, components

**Secondary:**
- JavaScript - Asset management and jQuery via CDN
- SQL - MySQL queries via Yii2 ORM

## Runtime

**Environment:**
- PHP 7.0+ (minimum required per `composer.json`)

**Package Manager:**
- Composer - PHP dependency management
- Lockfile: `composer.lock` present

## Frameworks

**Core:**
- Yii2 (~2.0.5) - Full-stack web application framework
- Yii2 Bootstrap 4 (~2.0) - Bootstrap integration

**UI Components:**
- Kartik Yii2 Extensions (multiple):
  - `kartik-v/yii2-widget-activeform` - Form rendering
  - `kartik-v/yii2-widget-select2` - Select2 integration
  - `kartik-v/yii2-grid` - Grid widget
  - `kartik-v/yii2-export` - Data export (to Excel/PDF)
  - `kartik-v/yii2-mpdf` - MPDF integration for PDF generation
  - `kartik-v/yii2-tabs-x` - Tab widget
  - `kartik-v/yii2-widgets` - General widget set
  - `kartik-v/yii2-date-range` - Date range picker
  - `kartik-v/yii2-datecontrol` - Date control widget
  - `kartik-v/yii2-tree-manager` - Tree widget
  - `kartik-v/yii2-editable` - Inline editable cells
  - `kartik-v/yii2-checkbox-x` - Enhanced checkbox
  - `kartik-v/yii2-field-range` - Field range control

**Admin Theme:**
- AdminLTE (~3.0) - Admin dashboard theme

**Database:**
- Yii2 ORM - `yii\db\ActiveRecord` pattern

**Testing:**
- Codeception ^2.2.3 - BDD/Acceptance testing
- Codeception Verify ~0.3.1
- Codeception Specify ~0.4.3
- Yii2 Faker ~2.0.0 - Test data generation

**Development:**
- Yii2 Debug (~2.0.0) - Development debugging toolbar
- Yii2 Gii (~2.0.0) - Code generation

**Build/Dev:**
- mootensai/yii2-relation-trait - Model relations helper
- warrence/yii2-kartikgii - Extended Gii for Kartik
- mootensai/yii2-enhanced-gii - Enhanced code generation

## Key Dependencies

**Critical:**
- `google/apiclient` (2.0) - Google APIs (Maps, Firebase, Cloud services)
- `2amigos/yii2-google-maps-library` - Google Maps integration
- `miloschuman/yii2-highcharts-widget` (^8.0) - Charting
- `kartik-v/yii2-mpdf` - PDF generation via mPDF

**Infrastructure:**
- `yiisoft/yii2-bootstrap4` (^2.0) - Bootstrap 4 components
- `yiisoft/yii2-swiftmailer` (~2.0.0) - Email via SwiftMailer
- `yiisoft/yii2-authclient` (*) - OAuth/social auth client
- `unclead/yii2-multiple-input` (~2.0) - Multiple input widget
- `yii2tech/embedded` (^1.0) - Embedded model support
- `wbraganca/yii2-dynamicform` - Dynamic form generation
- `endroid/qr-code` (^4.6) - QR code generation
- `moonlandsoft/yii2-phpexcel` - Excel file generation
- `hscstudio/yii2-export` (1.0.0) - Data export utility
- `kriss/yii2-log-reader` (2.*) - Log viewing
- `sjaakp/yii2-loadmore` (^1.0) - Infinite scroll
- `pigochu/yii2-jquery-locationpicker` (>=0.2.0) - Location picker

**RBAC & Security:**
- `yiisoft/yii2-authclient` - Authentication/authorization

**Editor:**
- `mihaildev/yii2-ckeditor` - Rich text editor

## Configuration

**Environment:**
- Custom dotenv loader at `config/env.php` - Minimal dependency-free loader
- Reads from `.env` file (gitignored)
- Variables available via `getenv()`, `$_ENV`, `$_SERVER`
- No external dotenv library dependency

**Key configs:**
- `config/web.php` - Application configuration (routes, services, mailer)
- `config/db.php` - Database connection (MySQL)
- `config/params.php` - Application parameters
- `config/rbac.php` - Role-based access control rules
- `config/console.php` - Console application config

**Build:**
- Composer post-install hooks in `composer.json`
- Cookie validation key generation on install

## Platform Requirements

**Development:**
- PHP 7.0+
- MySQL database
- Composer for dependency management

**Production:**
- PHP 7.0+
- MySQL 5.7+ (charset: utf8mb4)
- Web server (Apache/Nginx with mod_rewrite or equivalent)
- SSL/TLS for secure connections
- File upload path: `./uploads` (must be writable)
- Cache directory: `./runtime/cache` (must be writable)
- Log directory: `./runtime/logs` (must be writable)

---

*Stack analysis: 2026-06-04*
