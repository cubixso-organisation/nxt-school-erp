# Coding Conventions

**Analysis Date:** 2026-04-28

## Naming Patterns

**Files:**
- Classes use PascalCase: `ChildMeritController.php`, `ChildMerit.php`, `ChildMeritSearch.php`
- Base/generated model files prefixed with `Base`: `base/ChildMerit.php`
- Search classes suffixed with `Search`: `ChildMeritSearch.php`

**Namespaces:**
- Follow directory structure: `app\modules\[module_name]\controllers`, `app\modules\[module_name]\models`
- Search models in subdirectory: `app\modules\childassessment\models\search\ChildMeritSearch`
- Root models: `app\models\User`, `app\models\ActiveRecord`
- Traits organized by type: `app\traits\controllers\FindModelOrFail`, `app\traits\models\WithStatus`

**Classes:**
- Controllers: PascalCase, suffixed with `Controller`: `ChildMeritController`
- Models: PascalCase, matches database table (singular): `ChildMerit`
- Search models: Model class name suffixed with `Search`
- Traits: PascalCase, descriptive names: `FindModelOrFail`, `WithStatus`

**Functions/Methods:**
- camelCase: `actionIndex()`, `actionView()`, `actionCreate()`, `actionUpdate()`, `actionDelete()`
- Action methods prefixed with `action`: all controller actions follow `public function action[Action]()`
- Model methods: `behaviors()`, `rules()`, `scenarios()`, `loadAll()`, `saveAll()`

**Variables:**
- camelCase for local variables and properties: `$searchModel`, `$dataProvider`, `$model`, `$params`
- Database columns use snake_case: `campus_id`, `max_marks`, `status`, `created_on`, `updated_on`, `create_user_id`, `update_user_id`

**Constants:**
- UPPERCASE_WITH_UNDERSCORES: `STATUS_DELETE`, `ROLE_ADMIN`, `ROLE_CAMPUS_ADMIN`, `ROLE_INSTITUTE_ADMIN`

## Code Style

**Formatting:**
- PHP 7.0+ syntax required (`composer.json` specifies `"php": ">=7.0"`)
- Opening braces on same line (K&R style): `public function actionIndex() {`
- Indentation: 4 spaces (visible in all code samples)
- No inline closing comment tags for namespaces

**Linting:**
- No linter configuration file detected (no `.eslintrc`, `phpstan.neon`, `psalm.xml` present)
- Yii2 framework conventions apply by default

## Import Organization

**Order:**
1. PHP namespace declaration: `namespace app\modules\childassessment\controllers;`
2. Framework imports: `use Yii;`
3. App model imports: `use app\models\User;`, `use app\modules\childassessment\models\ChildMerit;`
4. Search/helper imports: `use app\modules\childassessment\models\search\ChildMeritSearch;`
5. Yii component imports: `use yii\web\Controller;`, `use yii\web\NotFoundHttpException;`, `use yii\filters\VerbFilter;`

**Path Aliases:**
- `@app`: application root
- `@bower`: `@vendor/bower-asset`
- `@npm`: `@vendor/npm-asset`
- Standard Yii aliases used without custom extensions

## Error Handling

**Patterns:**
- Yii exceptions thrown for missing resources: `throw new NotFoundHttpException()`
- Exception messages use i18n translation: `Yii::t('app', 'The requested page does not exist.')`
- Framework exceptions from trait `FindModelOrFail`: throws `InvalidCallException` for missing `$modelClass`, `NotFoundHttpException` when model not found
- No try/catch blocks in controller actions observed; framework handles exception propagation
- Validation errors handled through model validation: `if ($model->loadAll(...) && $model->save())` pattern
- HTTP method validation via `VerbFilter` behavior (DELETE via POST)

**Exception Types Used:**
- `yii\web\NotFoundHttpException` - for missing records
- `yii\base\InvalidCallException` - for misconfigured traits/classes

## Logging

**Framework:** No explicit logging observed; Yii2 application logging available via configuration

**Patterns:**
- Logging configured in `config/web.php` bootstrap
- No custom logging seen in explored controllers/models
- `Yii::$app->user->identity` used for accessing current user context

## Comments

**When to Comment:**
- Class-level docblocks (PSR-5 style): Document controller/model purpose with `/**`
- Method docblocks: Include `@param`, `@return`, `@throws` annotations
- Not used for inline code explanation; code is self-documenting

**JSDoc/TSDoc:**
- PHP docblock format (PSR-5):
  ```php
  /**
   * ChildMeritController implements the CRUD actions for ChildMerit model.
   */
  ```

**Docblock Tags:**
- `@inheritdoc` - used when overriding parent methods
- `@param` - document parameters with types
- `@return` - document return types (`mixed`, specific classes, or `void`)
- `@throws` - document exceptions thrown

## Function Design

**Size:** Standard CRUD actions (index, view, create, update, delete) occupy 10-20 lines each

**Parameters:**
- Controller actions: minimal parameters, route params extracted via `Yii::$app->request`
- Model methods: array parameters for batch operations (`loadAll()`, `saveAll()`)
- Search methods: array `$params` for filter/sort input

**Return Values:**
- Actions return: rendered views (`$this->render()`) or redirects (`$this->redirect()`)
- Behaviors return array of behavior configurations
- Rules/validation return array of validation rule definitions
- Search methods return `ActiveDataProvider` instance

## Module Design

**Exports:**
- Controllers public: all action methods `public function action*`
- Models public: `rules()`, `scenarios()`, `behaviors()`, validation/relation methods
- Traits public: specific methods like `findModel()`

**Barrel Files:**
- Not used; imports are explicit and fully qualified
- Namespaces used for organization instead

**Model Inheritance:**
- All models extend base model: `ChildMerit extends BaseChildMerit`
- Base models auto-generated (not part of normal workflow)
- Custom models override `rules()` using `array_replace_recursive(parent::rules(), [...])`
- `ActiveRecord` base class in `app\models\ActiveRecord` extends `yii\db\ActiveRecord` with timestamp behavior

**Traits Usage:**
- `FindModelOrFail` trait: provides `findModel($id)` helper for controllers
- `WithStatus` trait: likely provides status-related constants and methods
- Traits used in controllers for shared functionality

---

*Convention analysis: 2026-04-28*
