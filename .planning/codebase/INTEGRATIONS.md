# External Integrations

**Analysis Date:** 2026-04-28

## APIs & External Services

**Email Services:**
- SendGrid - Outgoing email via SMTP
  - SDK/Client: `yii\swiftmailer\Mailer` with Swift_SmtpTransport
  - Config: `config/web.php` lines 81-93
  - Host: `smtp.sendgrid.net`, Port: 587, Encryption: TLS
  - Auth: `username: "apikey"`, `password: "[SENDGRID_API_KEY]"`
  - Status: HARDCODED API KEY (SECURITY RISK) - line 89

- Brevo (formerly Sendinblue) - Transactional email API
  - SDK/Client: cURL (raw HTTP)
  - Implementation: `components/BrevoEmail.php`
  - Endpoint: `https://api.brevo.com/v3/smtp/email`
  - Auth: API key from `WebSetting::getSettingBykey('brevo_email_api_key')`
  - Usage: Sending templated emails with custom sender names per campus/institute

**Push Notifications:**
- Firebase Cloud Messaging (FCM)
  - SDK/Client: `google/apiclient` 2.0
  - Implementation: `components/FirebaseNotification.php`
  - Uses Google Access Token flow for authentication
  - Keys stored in `WebSetting` table:
    - `driver_notification_key`
    - `restaurant_notification_key`
    - `user_notification_key`
  - Device tokens stored per user in `AuthSession` model

**Payment Processing:**
- Razorpay - Payment gateway
  - SDK/Client: cURL (raw HTTP)
  - Implementation: `components/RazorPay.php`
  - Endpoint: `https://api.razorpay.com/v1/orders`
  - Auth: Basic auth with `razorpay_key_id:razorpay_key_secret` (from `WebSetting`)
  - Features: Order creation, payment verification
  - Integration: Web-based order flow in admin/e-commerce modules

**Mapping & Geolocation:**
- Google Maps API - Distance matrix and location services
  - SDK/Client: `2amigos/yii2-google-maps-library`, cURL for Distance Matrix API
  - Implementation: `components/DrivingDistance.php`
  - Google Maps Key: `<REDACTED — set GOOGLE_MAPS_API_KEY in .env>` (loaded via env in `config/web.php` line 191)
  - Endpoints:
    - Distance Matrix API: `https://maps.googleapis.com/maps/api/distancematrix/json`
    - Map rendering widget in views
  - Usage: Calculate driving distance between locations (currently commented out in DrivingDistance.php)
  - Visualization: Highcharts integration for map data display

## Data Storage

**Databases:**
- MySQL 5.7+ / MariaDB 10.2+
  - Connection: `config/db.php`
  - Host: `localhost` (default, changeable)
  - Database: `nxt_backend`
  - Charset: `utf8mb4` with Unicode collation
  - Client: Yii2 ActiveRecord ORM
  - Credentials: Hardcoded in config file (SECURITY CONCERN)
  - Features: Schema caching enabled (3600s)

**File Storage:**
- Local filesystem only
  - Upload directory: `uploads/` (git-ignored)
  - Runtime directory: `runtime/` (git-ignored)
  - Web-accessible assets: `web/assets/` (generated)
  - Media module: `modules/media/` handles media operations

**Caching:**
- File-based caching via Yii2
  - Implementation: `yii\caching\FileCache`
  - Cache location: `runtime/cache/` (file-based)
  - No external cache service (Redis/Memcached)

## Authentication & Identity

**Auth Provider:**
- Custom authentication system
  - Implementation: `app\models\User` (identity class)
  - Session management: Yii2 built-in session handling
  - Auto-login: Enabled via persistent cookies
  - Config: `config/web.php` lines 70-72

- OAuth Integration:
  - SDK: `yiisoft/yii2-authclient` *
  - Purpose: Social/third-party authentication support
  - Implementation: `components/AuthHandler.php` (custom OAuth handler)
  - Status: Integrated but specific providers not yet configured

## Monitoring & Observability

**Error Tracking:**
- Not detected - No third-party error tracking service (Sentry, Bugsnag, etc.)
- Local approach: File-based logging

**Logs:**
- Yii2 File Logging
  - Implementation: `yii\log\FileTarget`
  - Log location: `runtime/logs/` (git-ignored)
  - Levels captured: `error`, `warning`
  - Categories: SwiftMailer logs specifically
  - Viewer: `kriss/yii2-log-reader` 2.* for UI access
  - Debug mode: Yii2 Debug module enabled in dev environment

## CI/CD & Deployment

**Hosting:**
- Not auto-detected - Likely shared hosting or dedicated server
- Requirements: Apache with PHP module or PHP-FPM
- Deployment: Manual (no CI/CD pipeline detected in codebase)

**CI Pipeline:**
- Not detected - No GitHub Actions, GitLab CI, Jenkins configs found

## Environment Configuration

**Required env vars:**
None - All configuration is file-based in `config/` directory

**Hardcoded Secrets (CRITICAL ISSUES):**
- SendGrid API Key - `config/web.php` line 89
- Google Maps API Key - `config/web.php` line 191
- Database credentials - `config/db.php` lines 5-6
- Cookie validation key - `config/web.php` line 54

**Settings Storage:**
- Dynamic settings stored in database `WebSetting` table
- Accessed via: `WebSetting::getSettingBykey()`
- Includes: API keys, configuration flags, sender emails

**Secrets location:**
- Database table: `WebSetting` (for dynamic, environment-specific settings)
- Config files: `config/web.php`, `config/db.php` (hardcoded)
- Runtime: Not using .env files or environment variables

## Webhooks & Callbacks

**Incoming:**
- Firebase FCM callback handling (device token registration)
  - Route: API endpoints under `modules/api/`
  - Processing: `modules/api/controllers/` handle registration

**Outgoing:**
- Razorpay payment webhooks (implied)
  - Verification: Basic auth checks in payment handlers
  - Endpoints: Admin order management controllers

- Email delivery webhooks (not detected)
  - SendGrid/Brevo event tracking: Not implemented

## API Architecture

**Internal API:**
- RESTful API module: `modules/api/`
- Controllers: `modules/api/controllers/`
  - DefaultController
  - StudentController, TeacherController, ParentController
  - AgentController, BusDriverController, AccountantController
  - ChiefWardenController, HostelManagementController
  - ChildAssessmentController, ExamManagementController
  - LeaveManagementController, ManagementController
  - StudentCertificatesController
- Authentication: Yii2 session-based + OAuth support

**Response Format:**
- JSON (standard Yii2 response format)
- HTTP status codes: Standard RESTful conventions

## Module-Specific Integrations

**Hostel Management:**
- Location picker widget: `pigochu/yii2-jquery-locationpicker`
- Driving distance calculation: `components/DrivingDistance.php`

**Document Generator:**
- PDF generation: `kartik-v/yii2-mpdf` (mPDF wrapper)
- QR codes: `endroid/qr-code` ^4.6 (for certificates)
- Export formats: Excel via `moonlandsoft/yii2-phpexcel`

**Inventory Management:**
- Data export: `kartik-v/yii2-export`, `hscstudio/yii2-export`

**Library Management:**
- Document management: PDFs and file handling

**Child Assessment:**
- QR code generation for assessment certificates

**Leave Management:**
- Email notifications: SendGrid via SwiftMailer

**Exam Management:**
- Certificate generation with QR codes
- PDF output and export

**Staff Management:**
- Credential handling and authentication

---

*Integration audit: 2026-04-28*
