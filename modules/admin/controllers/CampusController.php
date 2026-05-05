<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\base\Institutes;
use Yii;
use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Designation;
use app\modules\admin\models\search\CampusSearch;
use app\modules\admin\models\WebSetting;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Exception;
use app\components\BrevoEmail;

/** 
 * CampusController implements the CRUD actions for Campus model.
 */
class CampusController extends Controller
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
                            'add-campus-has-users',
                            'add-campus-web-settings',
                            'add-class-sections',
                            'add-designation',
                            'add-driver-has-bus',
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
                            'name-of-the-educational-institution',
                            'campus-data',
                            'campus-view',
                            'shadow-login',
                            'my-campus'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusSubAdmin();
                        }

                    ],



                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'my-campus', 'my-campus-update'],
                        'matchCallback' => function () {
                            return User::isCampusAdmin() || User::isCampusSubAdmin();
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
     * Lists all Campus models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new CampusSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {

            $dataProvider = $searchModel->InstituteAdminSearch(Yii::$app->request->queryParams, (new Institutes())->getInstituteIdOfUser());
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Campus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),

        ]);
    }

    public function actionMyCampus()
    {
        $id = User::getCampusesByUser(Yii::$app->user->identity->id);
        return $this->render('my_campus_view', [
            'model' => $this->findModel($id),

        ]);
    }


    public function actionCampusView($id)
    {
        return $this->render('campus_profile', [
            'model' => $this->findModel($id),

        ]);
    }
    public function actionMyCampusUpdate()
    {
        $id = User::getCampusesByUser(Yii::$app->user->identity->id);
        $model = $this->findModel($id);
        $registration_document_old = $model->registration_document;
        $school_logo_old = $model->school_logo;

        if ($model->loadAll(Yii::$app->request->post())) {
            $school_logo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
            $registration_document = \yii\web\UploadedFile::getInstance($model, 'registration_document');


          
if ($school_logo && !$school_logo->getHasError()) {
    // Use 'school_logo' as the default folder if no folder is set
    $folderName = isset($folder) && !empty($folder) ? $folder : 'generalimages'; // Ensure folder name is valid and exists
    
    // Upload the image using the determined folder name
    $uploadedImage = Yii::$app->notification->imageKitUpload($school_logo, $folderName);

    // Debug the response
    var_dump($uploadedImage);

    if (isset($uploadedImage['url'])) {
        $model->school_logo = $uploadedImage['url'];
    } else {
        Yii::$app->session->setFlash('error', 'School logo upload failed: ' . $uploadedImage['message']);
        $model->school_logo = $school_logo_old; // Retain old logo if upload failed
    }
} else {
    // No new school logo uploaded, retain the old logo
    $model->school_logo = $school_logo_old;
}



            if (!empty($registration_document)) {
                $image = Yii::$app->notification->imageKitUpload($registration_document, 'registration_document');
                $model->registration_document = $image['url'];
            } else {
                $model->registration_document = $registration_document_old;
            }





            $model->save(false);
            return $this->redirect(['my-campus']);
        } else {
            return $this->render('my_campus_update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Campus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
{
    $model = new Campus();
    $post = Yii::$app->request->post();

    // Verify if the form is posted
    if ($model->load($post)) {
        $contact_number_of_the_authorized = $post['Campus']['contact_number_of_the_authorized'] ?? null;
        $user_role = User::ROLE_CAMPUS_ADMIN;

        // Check if user with this contact and role already exists
        $user_check = User::find()->where(['contact_no' => $contact_number_of_the_authorized, 'user_role' => $user_role])->one();
        if (empty($user_check)) {
            $model->scenario = 'create';
            $name_of_the_authorized = $post['Campus']['name_of_the_authorized'];
            $email_id_of_the_authorized = $post['Campus']['email_id_of_the_authorized'] ?? null;
            $random_password = rand(11111111, 99999999);

            // Initialize new User model
            $user = new User();
            $user->username = $contact_number_of_the_authorized . '@' . $user_role . '.com';
            $user->email = $email_id_of_the_authorized;
            $user->contact_no = $contact_number_of_the_authorized;
            $user->first_name = $name_of_the_authorized;
            $user->user_role = $user_role;
            $user->password_hash = password_hash($random_password, PASSWORD_BCRYPT);
            $user->status = User::STATUS_ACTIVE;

            // Save user and continue if successful
            if ($user->save(false)) {
                // Upload school logo
                $school_logo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
                if ($school_logo) {
                    $image = Yii::$app->notification->imageKitUpload($school_logo, 'school_logo');
                    $model->school_logo = $image['url'];
                }

                // Upload registration document
                $registration_document = \yii\web\UploadedFile::getInstance($model, 'registration_document');
                if ($registration_document) {
                    $image = Yii::$app->notification->imageKitUpload($registration_document, 'registration_document');
                    $model->registration_document = $image['url'];
                }

                // Set other model attributes
                $model->user_id = $user->id;
                $model->status = Campus::STATUS_ACTIVE;
                $model->coordinates = $model->lat . ',' . $model->lng;
                $model->radius = $model->lat . ',' . $model->lng;

                // Save Campus model
                if ($model->save(false)) {
                    // Send email
                    // $subject = $model->name_of_the_educational_Institution;
                    // $htmlContent = "<b>Name of school or college: $subject</b><br>
                    //                 <b>Username: $user->username</b><br>
                    //                 <b>Password: $random_password</b>";
                    // BrevoEmail::sendEmail(Yii::$app->user->identity->id, $email_id_of_the_authorized, $user->username, $subject, $htmlContent);

                    // Create designations
                    $designations = (new User)->designations();
                    foreach ($designations as $key => $designation_title) {
                        $designation = new Designation();
                        $designation->campus_id = $model->id;
                        $designation->title = $designation_title;
                        $designation->des_key = $key;
                        $designation->status = Designation::STATUS_ACTIVE;
                        $designation->save(false);
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    // Handle failure in saving model by deleting the created user
                    $user->delete();
                }
            }

            // Return to form if saving fails
            Yii::error('Failed to save Campus or User model.', __METHOD__);
        } else {
            $model->addError('contact_number_of_the_authorized', 'User already exists');
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}


    /**
     * Updates an existing Campus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $registration_document_old = $model->registration_document;
        $school_logo_old = $model->school_logo;

        if ($model->loadAll(Yii::$app->request->post())) {
            $school_logo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
            $registration_document = \yii\web\UploadedFile::getInstance($model, 'registration_document');



            if (!empty($school_logo)) {
                $image = Yii::$app->notification->imageKitUpload($school_logo, 'school_logo');
                // var_dump($image);
                // exit;

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





            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Campus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Campus::STATUS_DELETE;
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
            $model = Campus::find()->where([
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
     * Finds the Campus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Campus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campus::findOne($id)) !== null) {
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formBusStatus', ['row' => $row]);
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formDriverHasBus', ['row' => $row]);
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
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
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formStudentSpecialCourses', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }


    public function actionNameOfTheEducationalInstitution()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $campus_id = $parents[0];
                $out = (new Campus())->getNameOfTheEducationalInstitution($campus_id);
                return $out;
            }
        }

        return $out;
    }

    public function actionCampusData()
    {


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $institute_id = $parents[0];
                $out = (new Campus())->getCampusData($institute_id);
                return $out;
            }
        }

        return $out;
    }

    public function actionShadowLogin($id, $shadow_login = '')
    {
        $post = \Yii::$app->request->post();
        $identity = User::findOne(['id' => $id]);
        if (Yii::$app->user->login($identity)) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                $model = Campus::find()->Where(['user_id' => \Yii::$app->user->identity->id])
                    ->one();
                $cookies = Yii::$app->response->cookies;

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
