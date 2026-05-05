<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\ActivationModules;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\search\InstitutesSearch;
use app\modules\admin\models\WebSetting;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Exception;
use app\modules\admin\models\Designation;
use app\components\BrevoEmail;
use yii\web\UploadedFile;

/**
 * InstitutesController implements the CRUD actions for Institutes model.
 */
class InstitutesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'add-assign-fee-to-student',
                            'add-bus-details',
                            'add-bus-route',
                            'add-bus-status',
                            'add-campus',
                            'add-campus-has-users',
                            'add-campus-web-settings',
                            'add-class-sections',
                            'add-designation',
                            'add-driver-has-bus',
                            'add-educational-institution-types',
                            'add-employee-details',
                            'add-fee-structures',
                            'add-fees-typs',
                            'add-parent-details',
                            'add-pay-fees',
                            'add-payment-details',
                            'add-special-courses',
                            'add-student-attendance-bus',
                            'add-student-class',
                            'add-student-details',
                            'add-student-has-bus',
                            'add-student-has-parent',
                            'add-student-special-courses',
                            'shadow-login'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Institutes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstitutesSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Institutes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerActivationModules = new \yii\data\ArrayDataProvider([
            'allModels' => $model->activationModules,
        ]);
        $providerCampus = new \yii\data\ArrayDataProvider([
            'allModels' => $model->campuses,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerActivationModules' => $providerActivationModules,
            'providerCampus' => $providerCampus,
        ]);
    }

    /**
     * Creates a new Institutes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */ public function actionCreate()
    {
        $model = new Institutes();
        $model->scenario = 'create';

        // Check if request is POST (form submission)
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // Log POST data and files for debugging
            Yii::debug('POST data: ' . json_encode($post), __METHOD__);
            Yii::debug('Files uploaded: ' . json_encode($_FILES), __METHOD__);

            $contact_number_of_the_authorized = $post['Institutes']['contact_number_of_the_authorized'] ?? null;
            $subscription_type = $post['Institutes']['subscription_type'] ?? null;
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (empty($contact_number_of_the_authorized)) {
                    throw new \Exception('Contact number of the authorized person is required.');
                }

                if (empty($subscription_type)) {
                    throw new \Exception('Subscription type is required.');
                }

                $user_role = $subscription_type == Institutes::subscription_type_group_of_institutions
                    ? User::ROLE_INSTITUTE_ADMIN
                    : User::ROLE_CAMPUS_ADMIN;

                $user_check = User::find()
                    ->where(['contact_no' => $contact_number_of_the_authorized])
                    ->andWhere(['user_role' => $user_role])
                    ->one();

                if ($user_check) {
                    $model->addError('contact_number_of_the_authorized', 'User already exists with this contact number and role.');
                    throw new \Exception('User already exists.');
                }

                if (!$model->loadAll($post)) {
                    throw new \Exception('Failed to load institute data from form.');
                }

                $name_of_the_authorized = $post['Institutes']['name_of_the_authorized'] ?? null;
                $email_id_of_the_authorized = $post['Institutes']['email_id_of_the_authorized'] ?? null;
                $fee_receipt_content = $post['Institutes']['fee_receipt_content'] ?? '';
                $rand = rand(11111111, 99999999);

                $user = new User();
                $user->username = $contact_number_of_the_authorized;
                $user->email = $email_id_of_the_authorized;
                $user->contact_no = $contact_number_of_the_authorized;
                $user->first_name = $name_of_the_authorized;
                $user->user_role = $user_role;
                $user->password_hash = password_hash($rand, PASSWORD_BCRYPT);
                $user->create_user_id = Yii::$app->user->identity->id;
                $user->status = User::STATUS_ACTIVE;

                if (!$user->save(false)) {
                    Yii::error('Failed to save user: ' . json_encode($user->getErrors()), __METHOD__);
                    throw new \Exception('User could not be created.');
                }

                // Set user_id before validation
                $model->user_id = $user->id;

                // Handle file uploads
                $registration_document = \yii\web\UploadedFile::getInstance($model, 'registration_document');
                if ($registration_document) {
                    $image = Yii::$app->notification->imageKitUpload($registration_document, 'registration_document');
                    if (empty($image['url'])) {
                        throw new \Exception('Failed to upload registration document.');
                    }
                    $model->registration_document = $image['url'];
                } else {
                    $model->registration_document = null; // Allow null
                }

                $school_logo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
                if ($school_logo) {
                    $image = Yii::$app->notification->imageKitUpload($school_logo, 'school_logo');
                    if (empty($image['url'])) {
                        throw new \Exception('Failed to upload school logo.');
                    }
                    $model->school_logo = $image['url'];
                }

                $model->coordinates = $model->lat . ',' . $model->lng;

                // Validate institute model
                $model->scenario = 'create';
                if (!$model->validate()) {
                    Yii::error('Institute validation errors: ' . json_encode($model->getErrors()), __METHOD__);
                    throw new \Exception('Institute model validation failed: ' . json_encode($model->getErrors()));
                }

                if (!$model->save(false)) {
                    Yii::error('Failed to save institute: ' . json_encode($model->getErrors()), __METHOD__);
                    throw new \Exception('Institute could not be saved.');
                }

                // Save activation modules
                $activation_modules = $post['Institutes']['activation_modules'] ?? [];
                $default_activation = (new Institutes())->getActionModeOptionsSave();

                // Save default activation modules as inactive
                foreach ($default_activation as $module_data) {
                    if (!in_array($module_data, $activation_modules)) {
                        $activation_modules_save = new ActivationModules();
                        $activation_modules_save->institute_id = $model->id;
                        $activation_modules_save->activation_modules = $module_data;
                        $activation_modules_save->status = ActivationModules::STATUS_INACTIVE;
                        if (!$activation_modules_save->save(false)) {
                            Yii::error('Failed to save default activation module: ' . json_encode($activation_modules_save->getErrors()), __METHOD__);
                            throw new \Exception('Default activation module could not be saved.');
                        }
                    }
                }

                // Save selected activation modules as active
                foreach ($activation_modules as $module_data) {
                    $activation_modules_save = ActivationModules::find()
                        ->where(['institute_id' => $model->id])
                        ->andWhere(['activation_modules' => $module_data])
                        ->one();

                    if (empty($activation_modules_save)) {
                        $activation_modules_save = new ActivationModules();
                        $activation_modules_save->institute_id = $model->id;
                        $activation_modules_save->activation_modules = $module_data;
                        $activation_modules_save->status = ActivationModules::STATUS_ACTIVE;
                    }

                    if (!$activation_modules_save->save(false)) {
                        Yii::error('Failed to save activation module: ' . json_encode($activation_modules_save->getErrors()), __METHOD__);
                        throw new \Exception('Activation module could not be saved.');
                    }
                }

                // Handle campus creation for individual institution
                if ($model->subscription_type == Institutes::subscription_type_individual_institution) {
                    $campus = new Campus();
                    $campus->institute_id = $model->id;
                    $campus->name_of_the_educational_Institution = $model->name_of_the_educational_Institution;
                    $campus->educational_institution_type_id = $model->educational_institution_type_id;
                    $campus->user_id = $model->user_id;
                    $campus->country_id = $model->country_id;
                    $campus->state_id = $model->state_id;
                    $campus->district_id = $model->district_id;
                    $campus->address = $model->address;
                    $campus->pincode = $model->pincode;
                    $campus->fee_receipt_content = $fee_receipt_content;
                    $campus->registration_number = $model->registration_number;
                    $campus->registration_document = $model->registration_document;
                    $campus->name_of_the_authorized = $model->name_of_the_authorized;
                    $campus->designation_of_the_authorized = $model->designation_of_the_authorized;
                    $campus->contact_number_of_the_authorized = $model->contact_number_of_the_authorized;
                    $campus->name_of_the_contact = $model->name_of_the_contact;
                    $campus->designation_of_the_contact = $model->designation_of_the_contact;
                    $campus->contact_number_of_the_contact = $model->contact_number_of_the_contact;
                    $campus->email_id_of_the_authorized = $model->email_id_of_the_authorized;
                    $campus->expiry_date = $model->expiry_date;
                    $campus->onboarding_date = $model->onboarding_date;
                    $campus->aadhaar_of_the_authorized = $model->aadhaar_of_the_authorized;
                    $campus->status = $model->status;
                    $campus->lat = $model->lat;
                    $campus->lng = $model->lng;
                    $campus->coordinates = $model->coordinates;

                    // Log campus attributes before validation
                    Yii::debug('Campus attributes before validation: ' . json_encode($campus->attributes), __METHOD__);

                    if (!$campus->validate()) {
                        Yii::error('Campus validation errors: ' . json_encode($campus->getErrors()), __METHOD__);
                        Yii::error('Campus attributes: ' . json_encode($campus->attributes), __METHOD__);
                        throw new \Exception('Campus model validation failed: ' . json_encode($campus->getErrors()));
                    }

                    if (!$campus->save(false)) {
                        Yii::error('Failed to save campus: ' . json_encode($campus->getErrors()), __METHOD__);
                        throw new \Exception('Campus could not be saved.');
                    }

                    // Create designations
                    $designations = (new User())->designations();
                    foreach ($designations as $key => $designations_data) {
                        $designation = new Designation();
                        $designation->campus_id = $campus->id;
                        $designation->title = $designations_data;
                        $designation->des_key = $key;
                        $designation->status = Designation::STATUS_ACTIVE;
                        if (!$designation->save(false)) {
                            Yii::error('Failed to save designation: ' . json_encode($designation->getErrors()), __METHOD__);
                            throw new \Exception('Designation could not be saved.');
                        }
                    }
                }

                // Prepare email content (add mailing logic if needed)
                $subject = $model->name_of_the_educational_Institution;
                $htmlContent = "<b>Name of school or college: $subject</b><br>
                            <b>User Name: $user->username</b><br>
                            <b>Password: $rand</b>";

                // Commit transaction
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Institute created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error('Error creating institute: ' . $e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Failed to create institute: ' . $e->getMessage());
                return $this->render('create', ['model' => $model]);
            }
        }

        // For GET request, render the form
        Yii::debug('Rendering create form (GET request)', __METHOD__);
        return $this->render('create', ['model' => $model]);
    }



    /**
     * Updates an existing Institutes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */




    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";
        $school_logo_old =  $model->school_logo;
        $registration_document_old = $model->registration_document;

        // echo $school_logo_old;

        // exit;

        // $model->scenario = 'update';
        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();



            $school_logo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
            $registration_document = \yii\web\UploadedFile::getInstance($model, 'registration_document');



            if (!empty($school_logo)) {
                $image = Yii::$app->notification->imageKitUpload($school_logo, 'school_logo');
                $model->school_logo = $image['url'];
            } else {


                $model->school_logo = $school_logo_old;
            }



            if (!empty($registration_document)) {
                $image = Yii::$app->notification->imageKitUpload($registration_document, 'registration_document');
                $model->registration_document = $image['url'];
            } else {
                $model->registration_document = $registration_document_old;
            }




            if ($model->save(false)) {
                $activation_modules = $post['Institutes']['activation_modules'];
                $activation = (new Institutes())->getActionModeOptionsSave();
                $dff =  array_merge(array_diff($activation, $activation_modules), array_diff($activation, $activation));
                foreach ($dff as $dff_data) {
                    $inactiveActivateModes = ActivationModules::find()->where(['activation_modules' => $dff_data])
                        ->andWhere(['institute_id' => $model->id])
                        ->one();
                    $inactiveActivateModes->status = ActivationModules::STATUS_INACTIVE;
                    $inactiveActivateModes->save(false);
                }
                foreach ($activation_modules as $activation_modules_data) {
                    $inactiveActive = ActivationModules::find()->where(['activation_modules' => $activation_modules_data])
                        ->andWhere(['institute_id' => $model->id])
                        ->one();
                    $inactiveActive->status =  ActivationModules::STATUS_ACTIVE;
                    $inactiveActive->save(false);
                }
            }



            if ($model->subscription_type = Institutes::subscription_type_individual_institution) {
                $campus = Campus::find()->where(['institute_id' => $model->id])->one();
                $model->expiry_date =  $model->expiry_date;
                $model->onboarding_date =  $model->onboarding_date;
                $model->save(false);
                $campus->expiry_date = $model->expiry_date;
                $campus->onboarding_date = $model->onboarding_date;
                $campus->save(false);
            } else {
                $model->expiry_date =  $model->expiry_date;
                $model->onboarding_date =  $model->onboarding_date;
                $model->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Institutes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Institutes::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = Institutes::find()->where([
                'id' => $post['id'],
            ])->one();
            if (!empty($model)) {
                $model->status = $post['val'];
            }
            if ($model->save(false)) {
                $data['message'] = "Updated";
                $data['id'] = $model->status;
            } else {
                $data['message'] = "Not Updated";
            }
        }
        return $data;
    }


    /**
     * Finds the Institutes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Institutes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Institutes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AssignFeeToStudent
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddAssignFeeToStudent()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AssignFeeToStudent');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formAssignFeeToStudent', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for BusDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddBusDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('BusDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formBusDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for BusRoute
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddBusRoute()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('BusRoute');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formBusRoute', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for BusStatus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddBusStatus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('BusStatus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formBusStatus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for Campus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddCampus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Campus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formCampus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for CampusHasUsers
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddCampusHasUsers()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('CampusHasUsers');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formCampusHasUsers', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for CampusWebSettings
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddCampusWebSettings()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('CampusWebSettings');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formCampusWebSettings', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for ClassSections
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddClassSections()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('ClassSections');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formClassSections', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for Designation
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddDesignation()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Designation');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formDesignation', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for DriverHasBus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddDriverHasBus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('DriverHasBus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formDriverHasBus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for EducationalInstitutionTypes
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddEducationalInstitutionTypes()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('EducationalInstitutionTypes');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formEducationalInstitutionTypes', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for EmployeeDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddEmployeeDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('EmployeeDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formEmployeeDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for FeeStructures
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddFeeStructures()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('FeeStructures');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formFeeStructures', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for FeesTyps
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddFeesTyps()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('FeesTyps');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formFeesTyps', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for ParentDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddParentDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('ParentDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formParentDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for PayFees
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddPayFees()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('PayFees');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formPayFees', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for PaymentDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddPaymentDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('PaymentDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formPaymentDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for SpecialCourses
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddSpecialCourses()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('SpecialCourses');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formSpecialCourses', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentAttendanceBus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentAttendanceBus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentAttendanceBus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentAttendanceBus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentClass
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentClass()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentClass');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentClass', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentHasBus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentHasBus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentHasBus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentHasBus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentHasParent
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentHasParent()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentHasParent');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentHasParent', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentSpecialCourses
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentSpecialCourses()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentSpecialCourses');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentSpecialCourses', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    // Shadow login to institute dashboard

    public function actionShadowLogin($id, $shadow_login = '')
    {
        $post = \Yii::$app->request->post();
        $identity = User::findOne(['id' => $id]);
        // var_dump($identity);exit;
        if (Yii::$app->user->login($identity)) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
                $model = Institutes::find()->Where(['user_id' => \Yii::$app->user->identity->id])
                    // ->andWhere(['is_verified' => Store::VERIFIED])

                    ->one();
                $cookies = Yii::$app->response->cookies;
                // add a new cookie to the response to be sent
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'username',
                    'value' => 'yiiuser',
                ]));

                if (!empty($model)) {
                    return $this->redirect(['/admin/dashboard', 'shadow_login' => $shadow_login, 'model' => $model]);
                } else {
                    throw new NotFoundHttpException(Yii::t('app', 'Currently No Store Added To Your Dashboard Please Contact To Admin'));
                }
            }
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Invalid id'));
        }
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            return $this->redirect(['/admin/dashboard',]);
        }
    }
}
