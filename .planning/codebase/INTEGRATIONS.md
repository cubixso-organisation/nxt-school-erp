# External Integrations

**Analysis Date:** 2026-06-04

## APIs & External Services

**Google Cloud Services:**
- Firebase Cloud Messaging (FCM) - Push notifications to mobile apps
  - SDK/Client: `google/apiclient` (2.0)
  - Auth: Service account JSON files
  - Implementation: `components/FirebaseNotification.php`
  - Access token generation via JWT with service account private key
  - FCM v1 API endpoint: `https://fcm.googleapis.com/v1/projects/{project_id}/messages:send`
  - Legacy FCM endpoint: `https://fcm.googleapis.com/fcm/send`

- Google Maps API - Location-based services
  - SDK/Client: `2amigos/yii2-google-maps-library`
  - Auth: API key via `GOOGLE_MAPS_API_KEY` env var
  - Implementation: `components/DrivingDistance.php` (distance calculations)

- Google OAuth - User authentication
  - SDK/Client: `yiisoft/yii2-authclient`
  - Implementation: `components/AuthHandler.php`

**Anthropic Claude AI:**
- Claude API - AI/LLM for tools, RAG, audit logging
  - SDK/Client: Custom HTTP client via cURL
  - Auth: API key via `ANTHROPIC_API_KEY` env var
  - Model: Claude Sonnet 4.6 (configurable via `ANTHROPIC_MODEL`)
  - Base URL: `https://api.anthropic.com/v1/messages`
  - API version: `2023-06-01`
  - Implementation: `components/ai/AIClient.php`
  - Tool registry pattern: `components/ai/tools/ToolRegistry.php`
  - Audit logging: `components/ai/AuditLogger.php`
  - PII redaction: `components/ai/PiiRedactor.php` (redacts emails, phone numbers, Aadhaar, national IDs)
  - Token limit: 1024 (default, configurable)

**ImageKit Image Hosting:**
- ImageKit.io - Image upload and CDN
  - API: `https://upload.imagekit.io/api/v1/files/upload`
  - Auth: Basic auth with public/private keys from database settings
  - Implementation: `components/FirebaseNotification.php` methods `imageKitUpload()`, `withoutLoginImagekit()`

**Email Services:**
- SendGrid SMTP - Transactional email
  - Host: `smtp.sendgrid.net` port 587
  - Auth: API key via `SENDGRID_API_KEY` env var
  - Username: `apikey` (literal string)
  - Encryption: TLS
  - Implementation: `config/web.php` mailer config
  - Mailer class: `yii\swiftmailer\Mailer`

- Brevo (formerly Sendinblue) - Email provider integration
  - Implementation: `components/BrevoEmail.php`

**SMS Services:**
- 2Factor.in SMS Gateway - OTP and transactional SMS
  - API endpoints:
    - OTP send: `http://2factor.in/API/V1/{api_key}/SMS/91{phone}/{otp}`
    - OTP verify: `http://2factor.in/API/V1/{api_key}/SMS/VERIFY/{session_code}/{otp_code}`
    - Transactional SMS: `http://2factor.in/API/V1/{api_key}/ADDON_SERVICES/SEND/TSMS`
    - Dynamic template SMS: `https://2factor.in/API/R1/` (POST)
  - Auth: API key from database settings
  - Implementation: `components/FirebaseNotification.php` methods:
    - `sendOtp()` - Send OTP
    - `verifyOtp()` - Verify OTP
    - `sendSMS()` - Transactional SMS
    - `sendSMSDynamicTemplate()` - Templated SMS
    - `sendSMSDynamicTemplateV2()` - Templated SMS with variable mapping

- MSG91 - SMS provider (alternate/legacy)
  - API: `http://api.msg91.com/api/sendhttp.php`
  - Implementation: `components/FirebaseNotification.php` method `sendSms91()`

**Payment Processing:**
- Razorpay - Payment gateway
  - Implementation: `components/RazorPay.php`
  - Sensitive field: `razorpay_payment_id` (redacted in logs)

**Document Generation:**
- Internal NxtSchools API - PDF/Document generation
  - Base: `https://api.nxtschools.com` and `https://api.nxtschools.tech`
  - Endpoints:
    - `/api/v1/token/generate` - Token generation
    - `/api/v1/document-generator/generatePdf` - Standard PDF
    - `/api/v1/document-generator/generate-marksheet-silvercrest` - Marksheet PDF
    - `/api/v1/document-generator/finalPdf` - Final marksheet PDF
  - Auth: Bearer token (JWT)
  - Implementation: `components/FirebaseNotification.php` methods:
    - `generateToken()` - Request auth token
    - `generateMarksheetPdf()` - Standard marksheet
    - `silverCrestMarksheet()` - Silver Crest marksheet
    - `generateFinalMarksheetPdf()` - Final marksheet

## Data Storage

**Databases:**
- MySQL 5.7+ (default: localhost)
  - DSN via `DB_DSN` env var or default `mysql:host=localhost;dbname=nxt_backend`
  - Username: `DB_USERNAME` env var
  - Password: `DB_PASSWORD` env var
  - Charset: utf8mb4
  - Collation: utf8mb4_unicode_ci
  - Client: Yii2 `yii\db\Connection`
  - ORM: Yii2 ActiveRecord models in `models/` and `modules/*/models/`
  - Tables:
    - `ai_invocations` - AI tool invocation logs
    - `ai_proposals` - AI-generated change proposals
    - `fcm_notification` - Push notification history
    - `auth_session` - User device tokens for push notifications
    - Others: Users, institutes, campuses, orders, students, etc.

**File Storage:**
- Local filesystem:
  - Uploads: `./uploads/` (writable, user-managed)
  - Runtime cache: `./runtime/cache/` (writable, system-managed)
  - Logs: `./runtime/logs/` (writable, system-managed)
  - Generated PDFs: `./runtime/mpdf/` (mPDF temp directory)
  - Assets: `./web/assets/` (compiled by Yii2)

**Caching:**
- File-based cache via `yii\caching\FileCache`
- Location: `./runtime/cache/`
- Schema cache: 1-hour TTL for database schema caching

## Authentication & Identity

**Auth Provider:**
- Custom + OAuth hybrid
  - Session-based: Yii2 `yii\web\User` with `app\models\User` identity class
  - OAuth: `yiisoft/yii2-authclient` for Google/social login
  - Implementation: `components/AuthHandler.php`

**Token/Session Management:**
- Cookie-based sessions (Yii2 default)
- Cookie validation key: `COOKIE_VALIDATION_KEY` env var (required for security)
- Device token tracking: `auth_session` table for push notifications

**Firebase Service Accounts:**
- File path variables:
  - `FIREBASE_NXTSCHOOL_KEY_PATH` - Primary Firebase credentials
  - `FIREBASE_ESTUDENT_KEY_PATH` - Secondary Firebase credentials
- Fallback: `~/.config/nxtschools/nxtschool.json` if env var not set
- Format: Google service account JSON with private key
- Usage: JWT token generation for FCM access

## Monitoring & Observability

**Error Tracking:**
- Application exceptions logged to `./runtime/logs/`
- Yii2 error handler: `site/error` action
- Debug toolbar: Yii2 Debug (~2.0.0) in development

**Logs:**
- File-based logging: `yii\log\FileTarget`
- SwiftMailer errors/warnings: `yii\swiftmailer\Logger`
- Log categories: `yii\swiftmailer\Logger::add`

**AI Audit Logging:**
- Invocations logged to `ai_invocations` table:
  - Tool name, model, user/institute/campus context
  - Request/response payloads (JSON)
  - Latency in milliseconds
  - Status (success/error) and error messages
- Proposals logged to `ai_proposals` table:
  - Target table/primary key
  - Proposed changes (JSON)
  - Reasoning
  - Status (pending)
- PII redaction on logging via `components/ai/PiiRedactor.php`

## CI/CD & Deployment

**Hosting:**
- Generic PHP hosting (server details not specified in config)
- Requires: PHP 7.0+, MySQL, writable directories

**CI Pipeline:**
- Not detected in codebase

**Composer Post-Install:**
- Cookie validation key generation on `composer install`
- Permission setting via Composer hooks

## Environment Configuration

**Required env vars:**
- `DB_DSN` - MySQL connection string
- `DB_USERNAME` - Database user
- `DB_PASSWORD` - Database password
- `COOKIE_VALIDATION_KEY` - Session/cookie encryption key (32+ chars)
- `SENDGRID_API_KEY` - SendGrid SMTP credentials
- `GOOGLE_MAPS_API_KEY` - Google Maps API key
- `FIREBASE_NXTSCHOOL_KEY_PATH` - Path to Firebase service account JSON
- `FIREBASE_ESTUDENT_KEY_PATH` - Path to second Firebase account JSON
- `ANTHROPIC_API_KEY` - Claude API key
- `ANTHROPIC_MODEL` - Claude model (default: claude-sonnet-4-6)

**Secrets location:**
- `.env` file (gitignored) - Local development
- Service account JSONs: Absolute file paths or `~/.config/nxtschools/`
- Environment variable injection on deployment

**Custom dotenv loader:**
- Location: `config/env.php`
- No external dependency (pure PHP)
- Loads `.env` into `getenv()`, `$_ENV`, `$_SERVER`
- Respects existing OS/server env vars (no override)
- Strips quotes from values
- Skips comments and blank lines

## Webhooks & Callbacks

**Incoming:**
- Not detected

**Outgoing:**
- Firebase Cloud Messaging - Push notifications to mobile devices
- SendGrid SMTP - Email delivery webhooks (optional, configured externally)
- 2Factor.in - OTP delivery callbacks (optional)

---

*Integration audit: 2026-06-04*
