# Testing Patterns

**Analysis Date:** 2026-04-28

## Test Framework

**Runner:**
- Codeception 2.2.3+ (specified in `composer.json` require-dev: `"codeception/base": "^2.2.3"`)
- Config: `codeception.yml`

**Assertion Library:**
- Codeception Verify: `"codeception/verify": "~0.3.1"`
- Codeception Specify: `"codeception/specify": "~0.4.3"`

**Additional Testing Dependencies:**
- `yiisoft/yii2-faker`: ~2.0.0 (for test data generation)
- `yiisoft/yii2-debug`: ~2.0.0 (development debugging)

**Run Commands:**
```bash
# All tests (configured in codeception.yml)
php codecept run

# Unit tests only
php codecept run unit

# Functional tests only
php codecept run functional

# Acceptance tests only
php codecept run acceptance

# Watch mode / specific suite
php codecept run --watch
php codecept run unit --watch
```

**Coverage Report:**
```bash
# Generate code coverage (requires C3 middleware or remote config)
# Uncomment coverage section in codeception.yml first
php codecept run --coverage
php codecept run --coverage-html=tests/_output
```

## Test File Organization

**Location:**
- Separate from source code
- Tests directory: `tests/` (defined in codeception.yml: `paths: tests: tests`)
- Tests directory exists in configuration but **actual test files not present** in repository

**Naming:**
- Codeception uses suite-based naming: `{suite}Tester.php` auto-generated
- Test files likely named: `{Feature}Cept.php` (acceptance), `{Feature}Test.php` (unit/functional)

**Structure:**
```
tests/
├── _bootstrap.php           # Bootstrap configuration (defined in codeception.yml)
├── _output/                 # Test logs and coverage reports
├── _data/                   # Fixtures and test data
├── _support/                # Helper classes and custom steps
│   ├── AcceptanceTester.php (auto-generated)
│   ├── FunctionalTester.php (auto-generated)
│   ├── UnitTester.php       (auto-generated)
│   └── Helper/              (custom helpers)
├── acceptance/              # Acceptance tests
├── functional/              # Functional tests
└── unit/                    # Unit tests
```

## Test Structure

**Suite Organization:**

Codeception uses actor-based structure with three suite types:

```php
// Unit Test Example (if implemented)
class SomeTest extends \Codeception\Test\Unit
{
    protected $tester;
    
    protected function _before()
    {
        // Setup before each test
    }
    
    protected function _after()
    {
        // Teardown after each test
    }
    
    public function testSomething()
    {
        // Assertions using $this->tester
    }
}
```

```php
// Functional Test Example (if implemented)
class SomeFeatureCest
{
    public function testFeature(FunctionalTester $tester)
    {
        $tester->amOnPage('/path');
        $tester->see('Expected Text');
    }
}
```

**Patterns:**
- Unit tests: test business logic in isolation
- Functional tests: test controllers and models via browser simulation
- Acceptance tests: test full application via real browser
- All test suites configured in `codeception.yml` with Yii2 module

## Mocking

**Framework:**
- Codeception built-in mocking via `\Codeception\Test\Unit`
- PHPUnit mocking available through inheritance
- Yii2 Module in codeception provides fixtures and database reset

**Patterns:**
```php
// Typical Codeception unit test with mock
class UserModelTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->mockModel = \Mockery::mock(User::class);
    }
    
    public function testUserValidation()
    {
        // Test validation rules
        $user = new User();
        $user->rules(); // Test rule definitions
    }
}
```

**What to Mock:**
- External API calls (Google Maps, QR code generation)
- Database queries in unit tests
- File system operations

**What NOT to Mock:**
- Yii2 ORM methods (ActiveRecord) in functional tests
- Model validation rules
- Behavior application
- Framework core components

## Fixtures and Factories

**Test Data:**
```php
// Faker usage in fixtures (yii2-faker available)
$this->make(ChildMerit::class, [
    'name' => $this->faker->word,
    'description' => $this->faker->sentence,
    'max_marks' => $this->faker->numberBetween(10, 100),
    'status' => ChildMerit::STATUS_ACTIVE,
]);
```

**Location:**
- Fixture data: `tests/_data/`
- Fixture classes: `tests/_support/fixtures/`
- Faker factory definitions in test bootstrap

## Coverage

**Requirements:**
- Coverage reporting disabled by default (commented in `codeception.yml`)
- Can be enabled with C3 middleware or remote configuration
- Whitelist includes: `models/*`, `controllers/*`, `commands/*`, `mail/*`
- Blacklist excludes: `assets/*`, `config/*`, `runtime/*`, `vendor/*`, `views/*`, `web/*`, `tests/*`

**View Coverage:**
```bash
# After uncommenting coverage section and running tests
open tests/_output/coverage/index.html
```

## Test Types

**Unit Tests:**
- Scope: Individual model classes, validation rules, behaviors
- Approach: No database access; test business logic in isolation
- Location: `tests/unit/`
- Example: `ChildMeritTest.php` testing model validation and methods

**Functional Tests:**
- Scope: Controller actions, form submission, database integration
- Approach: Simulated browser requests via Yii2 test runner
- Location: `tests/functional/`
- Database: Uses test database (configured in `config/test.php`)
- Cleanup: Controlled by `codeception.yml: modules.config.Yii2.cleanup`

**Acceptance Tests:**
- Scope: Full application workflows, user journeys
- Approach: Real browser automation (requires Selenium/WebDriver)
- Location: `tests/acceptance/`
- Example: User login → navigate → perform action → verify result

**E2E Tests:**
- Not explicitly configured; acceptance tests serve this purpose
- Could use Selenium for real browser testing

## Common Patterns

**Async Testing:**
- Not applicable for PHP/Codeception (synchronous execution)
- Database transactions used for test isolation

**Error Testing:**
```php
// Test exception throwing
public function testNotFoundThrow(UnitTester $tester)
{
    $tester->expectException(NotFoundHttpException::class, function() {
        ChildMeritController::findModel(99999);
    });
}

// Test validation failures
public function testValidationFailure()
{
    $model = new ChildMerit();
    $this->assertFalse($model->validate()); // No required fields
    $this->assertArrayHasKey('campus_id', $model->errors);
}
```

**Database Testing:**
```php
// Functional tests with database
public function testCreateModel(FunctionalTester $tester)
{
    $tester->haveInDatabase('child_merit', [
        'campus_id' => 1,
        'name' => 'Test Merit',
        'max_marks' => 100,
        'status' => 1,
    ]);
    
    $count = ChildMerit::find()->count();
    $tester->assertEquals(1, $count);
}
```

## Test Configuration

**Codeception Config: `codeception.yml`**
- Actor: `Tester` (base actor class name)
- Bootstrap: `tests/_bootstrap.php`
- Memory limit: 1024M
- Color output enabled
- Yii2 module configured with `config/test.php`
- Database cleanup disabled by default (`cleanup: false`)

**Test Database Config: `config/test.php`**
- Separate configuration for test environment
- Uses test database (typically SQLite or separate MySQL DB)
- Disables caching during tests
- Enables Yii debug mode

**Functional Tests:**
- Use `FunctionalTester` actor
- Yii2 module resets application state between tests
- Database transactions available for rollback

## Known Limitations

**Current Status:**
- Tests configured but **not implemented**: `tests/` directory referenced in codeception.yml but actual test files not present in codebase
- Code coverage disabled: coverage section commented out in `codeception.yml`
- No test data fixtures created yet

**To Enable Testing:**
1. Create `tests/` directory structure (bootstrap.php, _support/, unit/, functional/, acceptance/)
2. Write test cases for critical paths (controllers, model validation, business logic)
3. Uncomment coverage section in `codeception.yml` if coverage tracking needed
4. Run `php codecept generate:cests` to scaffold test files

---

*Testing analysis: 2026-04-28*
