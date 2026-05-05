# Codebase Concerns

**Analysis Date:** 2026-04-28

## Critical Security Issues

### Exposed Credentials in Version Control

**SendGrid API Key Hardcoded:**
- Issue: Production SendGrid API key exposed in plaintext config
- Files: `config/web.php` (line 89)
- Credential: `SG.<REDACTED — see config/web.php; rotate this key>`
- Impact: Email service account compromised, attacker can send emails, modify email templates, access send history
- Fix approach: Move to `.env` file (add to `.gitignore`), use `Yii::$app->params` to load from environment

**Database Credentials Hardcoded:**
- Issue: MySQL root credentials in plaintext config
- Files: `config/db.php` (lines 5-7)
- Credentials: `username: <REDACTED>`, `password: <REDACTED — see config/db.php; rotate this password>`
- Impact: Unauthorized database access, data breach, data manipulation
- Fix approach: Use environment variables via `.env` loader (DotEnv package or similar)

### Missing File Upload Validation

**No Extension Whitelist:**
- Issue: File upload handlers accept any file type without validation
- Files: `controllers/SettingController.php` (lines 72-78, 105-111), `controllers/UserController.php`, `controllers/SiteController.php`
- Pattern: `$image->saveAs('uploads/' . $fileName)` without `validateType()` or extension checks
- Risk: Executable file upload (PHP, .exe), arbitrary code execution on server
- Fix approach: Implement `FileValidator` with `extensions` whitelist (jpg, png, gif, pdf only), validate MIME type via `finfo_file()`

**Incomplete Upload Handler:**
- Issue: `SettingController::actionSaveCmsImage()` only checks for empty files, no actual upload logic
- Files: `controllers/SettingController.php` (lines 229-234)
- Risk: Endpoint accepts requests but does nothing, possible confusion/inconsistency
- Fix approach: Complete implementation or remove endpoint

**Variable Name Typo in Upload:**
- Issue: Line 74 references `$imageFile->extension` but variable is `$image`
- Files: `controllers/SettingController.php` (line 74)
- Impact: Runtime error when image upload attempted
- Fix approach: Change `$imageFile->extension` to `$image->extension`

### Raw Input Handling

**Direct $_POST Access Without Validation:**
- Issue: Controllers directly access `$_POST` without Yii validation framework
- Files: `controllers/UserController.php` (line 63), `controllers/SiteController.php`, `controllers/SiteController copy.php`
- Pattern: `$_POST['depdrop_parents']`, `$_POST['code']`, `$_POST['csrf']`
- Risk: Bypasses Yii's CSRF protection, parameter pollution, type confusion
- Fix approach: Use `Yii::$app->request->post('key')` instead of `$_POST` directly

**Unsafe URL Fetching:**
- Issue: Dynamic URLs fetched without validation
- Files: `controllers/UserController.php`, `controllers/SiteController.php`
- Pattern: `file_get_contents($url)` where URL may contain user input
- Risk: SSRF (Server-Side Request Forgery), information disclosure
- Fix approach: Validate URL domain against whitelist, use `Guzzle` HTTP client with timeout

## Tech Debt & Code Quality

### God Classes / Monolithic Controllers

**Large API Controllers:**
- `modules/api/controllers/TeacherController.php`: 3,687 lines
- `modules/api/controllers/ParentController.php`: 2,792 lines
- `modules/api/controllers/BusDriverController.php`: 1,701 lines (87KB file)
- `modules/api/controllers/HostelManagementController.php`: 1,412 lines
- `modules/api/controllers/ChiefWardenController.php`: 1,238 lines
- Files: `modules/api/controllers/`
- Impact: Difficult to test, high cognitive load, hard to maintain, likely code duplication
- Safe modification: Extract logical features into separate actions/controllers, create service layer
- Recommended: Maximum 300 lines per controller file

**Large Model Files:**
- `models/User.php`: 1,615 lines
- Files: `models/User.php`
- Impact: Mixed concerns (validation, relationships, business logic), hard to test
- Fix approach: Extract business logic to service classes (`app\services\UserService`), keep model thin

### N+1 Query Problems

**Multiple findOne() Calls in Loops:**
- Issue: Controllers perform repeated database queries inside loops
- Files: `controllers/SiteController.php`, `modules/admin/controllers/StudentDetailsController.php`
- Pattern: `ParentDetails::find()->where(['id' => $dat['parent_id']])->one()` repeated per row
- Impact: Slow page loads, database overload, scales badly
- Fix approach: Eager load with `with()`, use `indexBy()` for lookup arrays

**Unoptimized Query Patterns:**
- Issue: Multiple separate queries where one JOIN would suffice
- Files: `modules/api/controllers/` (16 instances of `Yii::$app->db` direct access)
- Risk: Performance degradation
- Fix approach: Use Active Record relationships with eager loading

### Missing Test Coverage

**No Tests Directory:**
- Issue: Repository contains zero automated tests
- Impact: Regressions undetected, refactoring impossible, quality assurance relies on manual testing
- File structure: No `tests/` directory found
- Risk: High — codebase has 3,000+ PHP files with zero test coverage
- Fix approach: Start with critical path (auth, payment, student records), use PHPUnit + Codeception

### Large Binary Archives in Repository

**Large Uncompressed Files:**
- Files: `backend.zip` (1.75GB), `vendor.zip` (228MB), `zizaXoLX` (142MB, no extension)
- Impact: Repository clones are slow, disk space wasted, Git performance degraded
- Fix approach: Remove from Git history (BFG Repo-Cleaner), add to `.gitignore`, use dependency management (Composer)

### Suspicious/Unexplained Files

**Files Without Extensions:**
- `finalmarksheet` (1.5KB, also exists as `finalmarksheet.pdf`)
- `zizaXoLX` (142MB) — purpose unclear
- Risk: Possible malware, corrupted files, or incomplete uploads
- Action: Investigate purpose, remove if unused

**Orphaned Configuration Files:**
- `estudent.json`, `nxtschool.json`, `stateandcity.json` at root
- Purpose: Likely seed data or configuration
- Risk: If contains sensitive data, should be in `config/` or `.env`
- Fix: Move to `config/` directory or document purpose

**Duplicate Controller File:**
- `controllers/SiteController copy.php` — backup copy in working directory
- Risk: Code duplication, confusion about which version is live
- Fix: Remove immediately, use version control if backup needed

## Database & Performance Concerns

### Missing Query Optimization

**No Database Indexes on Foreign Keys:**
- Issue: Large tables (`StudentDetails`, `ParentDetails`, `Orders`) likely have unindexed foreign keys
- Impact: JOINs and WHERE queries are O(n) instead of O(log n)
- Files: Database migrations in `migrations/` directory
- Fix approach: Add migrations creating indexes on frequently-filtered columns

**File-Based Caching Only:**
- Issue: `config/web.php` uses `FileCache` with 3600s duration
- Impact: No distributed caching, poor performance under load, session conflicts in multi-server setup
- Fix: Use Redis or Memcached for production

### Logging Configuration Incomplete

**Selective Email Logging:**
- Issue: Only SwiftMailer logs are saved to file
- Files: `config/web.php` (lines 95-107)
- Risk: Application errors not logged, debugging production issues impossible
- Fix: Log all error categories to file, add separate email alert for critical errors

## Security Hardening Gaps

### Weak CSRF Protection

**CORS Headers Permissive:**
- Issue: `.htaccess` allows any origin with CORS headers
- Files: `.htaccess`
- Pattern: `Header set Access-Control-Allow-Origin "*"` and wildcard methods
- Risk: API endpoints accessible from any website (malicious cross-site requests)
- Fix approach: Whitelist specific trusted origins, restrict methods (GET, POST only)

**Cookie Validation Key Weak:**
- Issue: Cookie key was hardcoded — `<REDACTED — now loaded from COOKIE_VALIDATION_KEY in .env>`
- Files: `config/web.php` (line 54)
- Risk: Predictable, not environment-specific
- Fix: Use random key per environment from `.env`

### Missing Security Headers

**No X-Frame-Options Header:**
- Risk: Clickjacking attacks possible
- Fix: Add `Header set X-Frame-Options "DENY"` to `.htaccess`

**No Content-Security-Policy Header:**
- Risk: XSS attacks not mitigated
- Fix: Add CSP header to prevent inline scripts

**No Strict-Transport-Security (HSTS):**
- Risk: SSL stripping attacks possible
- Fix: Add `Strict-Transport-Security: max-age=31536000` header

### Input Validation Gaps

**Email Parameter Not Validated:**
- Issue: Email fields used in queries without validation
- Files: `controllers/UserController.php`, `models/User.php`
- Risk: Invalid data in database, email-based functionality breaks
- Fix: Use Yii validators (`email`, `unique`, `required`)

## Fragile Areas

### File Upload Directory World-Readable

**Uploads Stored in Web Root:**
- Issue: `uploads/` directory contains user-submitted files directly accessible via HTTP
- Files: All controllers using `saveAs('uploads/' . $fileName)`
- Risk: Stored XSS (if text files uploaded), information disclosure
- Fix approach: Move uploads outside web root (`@app/uploads/`), serve via controller action with validation

### Direct Database Access Bypassing ORM

**16 Instances of Raw `Yii::$app->db`:**
- Files: `modules/api/controllers/`
- Risk: No validation, relationships not tracked, complex manual SQL
- Fix: Use Active Record queries for consistency and safety

### No Request Input Sanitization

**No Global Sanitizer:**
- Issue: No consistent approach to XSS prevention
- Risk: User input echoed in views without HTML escaping
- Fix: Use `\yii\helpers\Html::encode()` in all templates, or configure auto-escaping

## Scaling Limitations

**No Caching Layer for Frequently Queried Data:**
- Issue: Settings, campuses, and class/section data queried repeatedly without caching
- Files: `controllers/SettingController.php` (lines 156-198, 7 separate queries in one action)
- Impact: Database load increases linearly with user count
- Fix: Cache with 1-hour TTL, invalidate on update

**File-Based Sessions:**
- Issue: Default Yii session handler uses files
- Risk: Doesn't scale to multiple servers, filesystem can become bottleneck
- Fix: Use `CacheSession` or database sessions for production

## Dependencies at Risk

**Deprecated SwiftMailer:**
- Library: `yii\swiftmailer\Mailer`
- Risk: SwiftMailer is deprecated, use `symfony/mailer` instead
- Migration: Switch to `yii\mailer\Mailer` with SMTP transport

**Old jQuery Version:**
- Issue: Hardcoded jQuery 1.11.2 (released 2015)
- Files: `config/web.php` (line 44)
- Risk: Known XSS vulnerabilities, compatibility issues
- Fix: Update to jQuery 3.x or migrate to modern framework

## Quick Wins (Priority Fixes)

| Issue | Effort | Impact | Priority |
|-------|--------|--------|----------|
| Remove hardcoded credentials | 1 hour | Critical | 1 |
| Fix file upload validation (variable typo, whitelist) | 2 hours | Critical | 2 |
| Remove large binary files from repo | 30 min | High | 3 |
| Add MIME type validation to file uploads | 1 hour | Critical | 4 |
| Remove duplicate `SiteController copy.php` | 5 min | Low | 5 |
| Replace `$_POST` with `Yii::$app->request->post()` | 2 hours | Medium | 6 |
| Tighten CORS headers in `.htaccess` | 30 min | High | 7 |

---

*Security and quality audit: 2026-04-28*
