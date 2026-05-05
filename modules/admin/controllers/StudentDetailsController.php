<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\search\ExamsResultSearch;
use Yii;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use app\modules\admin\models\search\StudentDetailsSearch;

use app\base\Model;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Auth;
use app\modules\admin\models\base\ParentDetails as BaseParentDetails;
use app\modules\admin\models\base\StudentHasParent;
use app\modules\admin\models\BloodGroups;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\Classrooms;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\ParentHasCampus;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\search\PaymentDetailsSearch;
use app\modules\admin\models\search\StudentAttendanceBusSearch;
use app\modules\admin\models\search\StudentClassAttendanceSearch;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\UserSearch;
use Exception;
use PHPExcel_Shared_Date;
use yii\widgets\ActiveForm;

/**  
 * StudentDetailsController implements the CRUD actions for StudentDetails model.
 */
class StudentDetailsController extends Controller
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
                            'add-agent-student-join',
                            'add-assign-fee-to-student',
                            'add-pay-fees',
                            'add-payment-details',
                            'add-student-attendance-bus',
                            'add-student-has-bus',
                            'add-student-has-parent',
                            'add-student-special-courses',
                            'parent',
                            'create-parent',
                            'update-parent',
                            'student-data-by-class-section',
                            'student-data-by-class-section-by-bus',
                            'student-data-by-class-section-by-parent',
                            'upload-excel',
                            'promote-students',
                            'promote-students-next-level',
                            'student-form-print',
                            'left-student',
                            'students',
                            'hostel-students'

                        ],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'add-agent-student-join',
                            'add-assign-fee-to-student',
                            'add-pay-fees',
                            'add-payment-details',
                            'add-student-attendance-bus',
                            'add-student-has-bus',
                            'add-student-has-parent',
                            'add-student-special-courses',
                            'parent',
                            'create-parent',
                            'update-parent',
                            'student-data-by-class-section',
                            'student-data-by-class-section-by-bus',
                            'student-data-by-class-section-by-parent',
                            'upload-excel',
                            'promote-students',
                            'promote-students-next-level',
                            'student-form-print',
                            'left-student',
                            'students',
                            'hostel-students'


                        ],
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
     * Lists all StudentDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentDetailsSearch();
        $model = new StudentDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->subcampussearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }


    public function actionStudents()
    {
        $searchModel = new StudentDetailsSearch();
        $model = new StudentDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        return $this->render('students', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    public function actionLeftStudent()
    {
        $searchModel = new StudentDetailsSearch();
        $model = new StudentDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->searchLeftStudent(Yii::$app->request->queryParams, campus::getCampusId());
        }
        return $this->render('index_left_student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionHostelStudents()
    {
        $searchModel = new StudentDetailsSearch();
        $model = new StudentDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->hostelStudentSearch(Yii::$app->request->queryParams, campus::getCampusId());
        }
        return $this->render('hostel_students', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }


    public function actionCreateParent()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $contact_no = $post['User']['contact_no'];
            $first_name = $post['User']['first_name'];
            if (!empty($contact_no) && !empty($first_name)) {
                $user_role = User::ROLE_PARENT;
                $model->username = $contact_no . '@' . $user_role . '.com';
                $model->user_role = $user_role;
                $model->create_user_id = Yii::$app->user->identity->id;


                $model->status = User::STATUS_ACTIVE;
                $model->campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
                if ($model->save()) {
                    $auth = new Auth();
                    $auth->user_id = $model->id;
                    $auth->source = $model->user_role;
                    $auth->source_id = $model->contact_no;
                    $auth->save(false);
                    return $this->redirect(['parent']);
                } else {
                    return $this->render('parent_create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('parent_create', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('parent_create', [
            'model' => $model,
        ]);
    }



    public function actionUpdateParent($id)
    {
        $model = User::find()->where(['id' => $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
            if ($model->save()) {

                $AuthCheck = Auth::find()->where(['user_id' => $model->id])->one();
                if (!empty($AuthCheck)) {
                    $auth =  Auth::find()->where(['id' => $AuthCheck->id])->one();
                } else {
                    $auth =  new Auth();
                }
                $auth->user_id = $model->id;
                $auth->source = $model->user_role;
                $auth->source_id = $model->contact_no;
                $auth->save(false);
                return $this->redirect(['parent']);
            } else {
                print_r($model->getErrors());
            }
        }
        return $this->render('parent_update', [
            'model' => $model,
        ]);
    }




    public function actionParent()
    {
        $searchModel = new UserSearch();

        if (User::isAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN, USER::ROLE_INSTITUTE_ADMIN]);
        } elseif (User::isInstituteAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN], '', Yii::$app->user->identity->id);
        } elseif (User::isCampusAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_PARENT], '', '', (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (User::isCampusSubAdmin()) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams, [USER::ROLE_PARENT], '', '', (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }


        return $this->render('parent', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $student_fee = PayFees::find()->joinWith('student')->where(['pay_fees.student_id' => $id])->all();

        $searchModelStudentClassAttendance = new StudentClassAttendanceSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProviderStudentClassAttendance = $searchModelStudentClassAttendance->search(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderStudentClassAttendance = $searchModelStudentClassAttendance->campusAdminSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProviderStudentClassAttendance = $searchModelStudentClassAttendance->institutesSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderStudentClassAttendance = $searchModelStudentClassAttendance->campusSubAdminSearch(Yii::$app->request->queryParams, $id);
        }

        $searchModelExamsResultSearch = new ExamsResultSearch();

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProviderExamsResult = $searchModelExamsResultSearch->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderExamsResult = $searchModelExamsResultSearch->campusAdminSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProviderExamsResult = $searchModelExamsResultSearch->institutesSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderExamsResult = $searchModelExamsResultSearch->campusAdminSearch(Yii::$app->request->queryParams, $id);
        }

        $searchModelStudentBusAttendance = new StudentAttendanceBusSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProviderStudentBusAttendance = $searchModelStudentBusAttendance->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderStudentBusAttendance = $searchModelStudentBusAttendance->campusSearch(Yii::$app->request->queryParams, '', $id);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderStudentBusAttendance = $searchModelStudentBusAttendance->campusAdminSearch(Yii::$app->request->queryParams, '', $id);
        }
        $searchModelPaymentDetails = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProviderPaymentDetails = $searchModelPaymentDetails->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderPaymentDetails = $searchModelPaymentDetails->campusSearch(Yii::$app->request->queryParams, $id);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderPaymentDetails = $searchModelPaymentDetails->campusSubAdminSearchSearch(Yii::$app->request->queryParams, $id);
        }


        return $this->render('view', [
            'model' => $this->findModel($id),
            'student_fee' => $student_fee,
            'searchModelStudentClassAttendance' => $searchModelStudentClassAttendance,
            'dataProviderStudentClassAttendance' => $dataProviderStudentClassAttendance,
            'searchModelStudentBusAttendance' => $searchModelStudentBusAttendance,
            'dataProviderStudentBusAttendance' => $dataProviderStudentBusAttendance,
            'searchModelPaymentDetails' => $searchModelPaymentDetails,
            'dataProviderPaymentDetails' => $dataProviderPaymentDetails,
            'searchModelExamsResultSearch' => $searchModelExamsResultSearch,
            'dataProviderExamsResult' => $dataProviderExamsResult,







        ]);
    }

    /**
     * Creates a new StudentDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentDetails();
        // $model->scenario = 'create';

        if ($model->loadAll(Yii::$app->request->post())) {

            $profile_photo = \yii\web\UploadedFile::getInstance($model, 'profile_photo');
            if (!empty($profile_photo)) {
                $image = Yii::$app->notification->imageKitUpload($profile_photo, 'profile_image/students');
                $model->profile_photo = $image['url'];
            }



            $post = Yii::$app->request->post();
            $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $student = new User();
            $student->username = $model->phone_number . '_' . $model->admission_number . '_' . '@' . User::ROLE_STUDENT . '.com';
            $student->first_name =  $model->student_name;
            $student->contact_no =  $model->phone_number;
            $student->campus_id =  $campus_id;

            $student->save(false);
            $model->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->user_id = $student->id;
            if ($model->save()) {
                $student->admission_number =  $model->admission_number;
                $student->save(false);


                //assign bus to student
                if ($model->bus_transport_required == StudentDetails::TRANSPORT_REQUIRED_YES) {
                    $bus_id = isset($post['StudentDetails']['bus_id']) ? $post['StudentDetails']['bus_id'] : '';
                    $bus_route_id = isset($post['StudentDetails']['bus_route_id']) ? $post['StudentDetails']['bus_route_id'] : '';
                    if (!empty($bus_id) && !empty($bus_route_id)) {
                        $student_has_bus = new StudentHasBus();
                        $student_has_bus->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $student_has_bus->student_id  = $model->id;
                        $student_has_bus->bus_id  = $bus_id;
                        $student_has_bus->bus_route_id   = $bus_route_id;
                        $student_has_bus->status   = StudentHasBus::STATUS_ACTIVE;
                        $student_has_bus->save(false);
                    }
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionStudentFormPrint()
    {
        $model = new StudentDetails();
        $ParentDetails = new ParentDetails();

        return $this->render('student_form_print', [
            'model' => $model,
            'ParentDetails' => $ParentDetails
        ]);
    }



    /**
     * Updates an existing StudentDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $profile_photo_old =  $model->profile_photo;

        if ($model->loadAll(Yii::$app->request->post())) {


            $post = Yii::$app->request->post();


            $profile_photo = \yii\web\UploadedFile::getInstance($model, 'profile_photo');
            if (!empty($profile_photo)) {
                $image = Yii::$app->notification->imageKitUpload($profile_photo, 'profile_image/students');

                $model->profile_photo = $image['url'];
            } else {
                $model->profile_photo = $profile_photo_old;
            }



            //update bus to student
            if ($model->bus_transport_required == StudentDetails::TRANSPORT_REQUIRED_YES) {
                $bus_id = isset($post['StudentDetails']['bus_id']) ? $post['StudentDetails']['bus_id'] : '';
                $bus_route_id = isset($post['StudentDetails']['bus_route_id']) ? $post['StudentDetails']['bus_route_id'] : '';
                if (!empty($bus_id) && !empty($bus_route_id)) {
                    $student_has_bus =  StudentHasBus::find()->where(['student_id' => $model->id])->one();
                    if (!empty($student_has_bus)) {
                        $student_has_bus->bus_id  = $bus_id;
                        $student_has_bus->bus_route_id   = $bus_route_id;
                        $student_has_bus->save(false);
                    } else {
                        $student_has_bus =  new  StudentHasBus();
                        $student_has_bus->campus_id   = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $student_has_bus->student_id   = $model->id;
                        $student_has_bus->bus_id  = $bus_id;
                        $student_has_bus->bus_route_id   = $bus_route_id;
                        $student_has_bus->save(false);
                    }
                }
            }



            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StudentDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }


    /**
     * Finds the StudentDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentDetails::findOne($id)) !== null) {
            return $model;
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


    public function actionStudentDataByClassSection()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $section_id = $parents[0];

                $out = (new StudentDetails())->getStudentDataByClassSection($section_id);
                return $out;
            }
        }

        return $out;
    }




    public function actionStudentDataByClassSectionByBus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $section_id = $parents[0];


                $out = (new StudentDetails())->getStudentDataByClassSectionBus($section_id);
                return $out;
            }
        }

        return $out;
    }

    public function actionStudentDataByClassSectionByParent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $section_id = $parents[0];


                $out = (new StudentDetails())->getStudentDataByClassSectionParent($section_id);
                return $out;
            }
        }

        return $out;
    }

    public function actionUploadExcel()
    {
        $transaction = Yii::$app->db->beginTransaction();

        // try {
        $modelImport = new StudentDetails();
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && $modelImport->load($post)) {
            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
            $inputFile = $modelImport->fileImport->tempName;

            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $batchSize = 100; // Define your batch size
            $batchData = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];
                $batchData[] = $this->prepareRowData($rowData);
                // Process batch when it reaches the batch size
                if (count($batchData) >= $batchSize) {
                    $this->processBatch($batchData);
                    $batchData = [];
                }
            }

            // Process remaining data
            if (!empty($batchData)) {
                $this->processBatch($batchData);
            }

            $transaction->commit();

            return json_encode(['type' => 'success', 'message' => 'Upload Success']);
        } else {
            throw new \Exception('Something went wrong, please try again later');
        }
        // } catch (\Exception $e) {
        //     $transaction->rollBack();
        //     return json_encode(['type' => 'error', 'message' => $e->getMessage()]);
        // }
    }

    private function prepareRowData($rowData)
    {
        return [
            'roll_number' => $rowData[1],
            'admission_number' => $rowData[2] ?? 'Not Set',
            'student_name' => $rowData[3] ?? 'Not Set',
            'date_of_birth' => $this->convertExcelDate($rowData[4] ?? ''),
            'gender' => $rowData[5] ?? "NULL",
            'category' => $rowData[6],
            'religion' => $rowData[7],
            'caste' => $rowData[8],
            'student_class_name' => $rowData[9],
            'section_name' => $rowData[10],
            'academic_year' => $rowData[11],
            'email' => $rowData[12],
            'admission_date' => $this->convertExcelDate($rowData[13] ?? ''),
            'blood_group_id' => $rowData[14],
            'student_house' => $rowData[15],
            'height' => $rowData[16],
            'weight' => $rowData[17],
            'national_Identification_number' => $rowData[18],
            'mother_tongue' => $rowData[19],
            'identification_marks' => $rowData[20],
            'previous_school' => $rowData[21],
            'old_admission_number' => $rowData[22],
            'name_of_the_father' => $rowData[23],
            'name_of_the_mother' => $rowData[24],
            'current_address' => $rowData[25],
            'permanent_address' => $rowData[26],
            'contact_number' => $rowData[27],
            'father_education_qualification' => $rowData[28],
            'mother_education_qualification' => $rowData[29],
            'father_aadhaar_number' => $rowData[30],
            'mother_aadhaar_number' => $rowData[31],
            'father_occupation' => $rowData[32] ?? 'na',
            'mother_occupation' => $rowData[33] ?? 'na',
            'bus_transport_required' => !empty($rowData[34]),
        ];
    }

    private function processBatch($batchData)
    {
        foreach ($batchData as $data) {
            // Retrieve campus_id and academic_year_id only once per batch to minimize database queries
            $campus_id = $this->getCampusId();

            $academic_year_id = $this->getAcademicYearId($data['academic_year'], $campus_id);
            $data['academic_year_id'] = $academic_year_id;
            // Handle parent user and details
            $parent_user = $this->getOrCreateParentUser($data['contact_number'], $data['name_of_the_father'], $data['name_of_the_mother'], $data['permanent_address']);
            $parent_details = $this->getOrCreateParentDetails($parent_user->id, $data['name_of_the_father'], $data['name_of_the_mother'], $data['current_address'], $data['permanent_address'], $data['contact_number'], $data['father_education_qualification'], $data['mother_education_qualification'], $data['father_aadhaar_number'], $data['mother_aadhaar_number'], $data['father_occupation'], $data['mother_occupation']);

            // Assign parent to campus
            $this->assignParentToCampus($parent_details->id, $campus_id);

            // Handle student user and details
            $student_user = $this->getOrCreateStudentUser($campus_id, $data['admission_number'], $data['student_name'], $data['contact_number']);
            $student_class_id = $this->getStudentClassId($data['student_class_name'], $campus_id);
            $data['student_class_id'] = $student_class_id;

            // var_dump($data);exit;
            $class_sections_id = $this->getClassSectionId($data['section_name'], $student_class_id, $campus_id);
            $data['class_sections_id'] = $class_sections_id;
            // Save student details
            $this->saveStudentDetails($student_user->id, $parent_details->id, $campus_id, $data);
        }
    }

    private function convertExcelDate($excelDate)
    {
        if (!empty($excelDate) && is_numeric($excelDate)) {
            $UNIX_DATE = ($excelDate - 25569) * 86400;
            return gmdate("Y-m-d", $UNIX_DATE);
        } else {
            return date('Y-m-d');
        }
    }

    private function getCampusId()
    {
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        if (empty($campus_id)) {
            $campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
        }
        return $campus_id;
    }

    private function getAcademicYearId($academic_year, $campus_id)
    {
        $academic_years = AcademicYears::find()->where(['title' => $academic_year])->andWhere(['campus_id' => $campus_id])->andWhere(['status' => AcademicYears::STATUS_ACTIVE])->one();
        return !empty($academic_years->id) ? $academic_years->id : '';
    }

    private function getOrCreateParentUser($contact_number, $name_of_the_father, $name_of_the_mother, $permanent_address)
    {
        $parent_user_check = User::find()->where(['contact_no' => $contact_number])->andWhere(['user_role' => User::ROLE_PARENT])->one();
        if (empty($parent_user_check)) {
            $parent_user = new User();
        } else {
            $parent_user = User::find()->where(['id' => $parent_user_check->id])->one();
        }
        $parent_user->username = $contact_number . '@' . User::ROLE_PARENT;
        $parent_user->first_name = !empty($name_of_the_father) ? $name_of_the_father : $name_of_the_mother;
        $parent_user->contact_no = $contact_number;
        $parent_user->address = $permanent_address;
        $parent_user->user_role = User::ROLE_PARENT;
        $parent_user->status = User::STATUS_ACTIVE;
        if ($parent_user->save(false)) {
            $auth = Auth::find()->where(['user_id' => $parent_user->id])->one();
            if (empty($auth)) {
                $auth = new Auth();
                $auth->user_id = $parent_user->id;
                $auth->source = "Parent";
                $auth->source_id = $contact_number;
                $auth->save(false);
            }
        }

        return $parent_user;
    }

    private function getOrCreateParentDetails($user_id, $name_of_the_father, $name_of_the_mother, $current_address, $permanent_address, $contact_number, $father_education_qualification, $mother_education_qualification, $father_aadhaar_number, $mother_aadhaar_number, $father_occupation, $mother_occupation)
    {
        $parent_details_exist = ParentDetails::find()->where(['contact_number' => $contact_number])->one();
        if (!empty($parent_details_exist)) {
            $parent_details = ParentDetails::find()->where(['id' => $parent_details_exist->id])->one();
        } else {
            $parent_details = new ParentDetails();
        }

        $parent_details->user_id = $user_id;
        $parent_details->name_of_the_father = $name_of_the_father;
        $parent_details->name_of_the_mother = $name_of_the_mother;
        $parent_details->current_address = $current_address;
        $parent_details->permanent_address = $permanent_address;
        $parent_details->contact_number = $contact_number;
        $parent_details->father_education_qualification = $father_education_qualification;
        $parent_details->mother_education_qualification = $mother_education_qualification;
        $parent_details->father_aadhaar_number = $father_aadhaar_number;
        $parent_details->mother_aadhaar_number = $mother_aadhaar_number;
        $parent_details->father_occupation = $father_occupation;
        $parent_details->mother_occupation = $mother_occupation;
        $parent_details->status = ParentDetails::STATUS_ACTIVE;

        $parent_details->save(false);

        return $parent_details;
    }

    private function assignParentToCampus($parent_id, $campus_id)
    {
        $parent_campus_exist = ParentHasCampus::find()->where(['patient_id' => $parent_id, 'campus_id' => $campus_id])->one();
        if (empty($parent_campus_exist)) {
            $parent_campus = new ParentHasCampus();
            $parent_campus->patient_id = $parent_id;
            $parent_campus->campus_id = $campus_id;
            $parent_campus->status = ParentHasCampus::STATUS_ACTIVE;

            $parent_campus->save(false);
        }
    }

    private function getOrCreateStudentUser($campus_id, $admission_number, $student_name, $contact_number)
    {
        $student_user_check = User::find()->where(['first_name' => $student_name])->andWhere(['contact_no' => $contact_number])->one();
        // var_dump($student_user_check);exit;
        if (empty($student_user_check)) {
            $student_user = new User();
            $student_user->username = $contact_number . '_' . $campus_id . '@' . User::ROLE_STUDENT . '.com';
            $student_user->first_name = $student_name;
            $student_user->contact_no = $contact_number;
            $student_user->address = 'NULL';
            $student_user->campus_id = $campus_id;
            $student_user->user_role = User::ROLE_STUDENT;
            $student_user->save(false);
        } else {
            $student_user = User::find()->where(['id' => $student_user_check->id])->one();
        }

        return $student_user;
    }

    private function getStudentClassId($student_class_name, $campus_id)
    {

        $student_class = StudentClass::find()->where(['title' => $student_class_name])->andWhere(['campus_id' => $campus_id])->one();

        return !empty($student_class->id) ? $student_class->id : '';
    }

    private function getClassSectionId($section_name, $student_class_id, $campus_id)
    {
        $class_sections = ClassSections::find()->where(['section_name' => $section_name])->andWhere(['student_class_id' => $student_class_id])->andWhere(['campus_id' => $campus_id])->one();
        return !empty($class_sections->id) ? $class_sections->id : '';
    }

    private function saveStudentDetails($user_id, $parent_id, $campus_id, $data)
    {

        $student_details = StudentDetails::find()->where(['user_id' => $user_id, 'parent_id' => $parent_id, 'campus_id' => $campus_id])->one();


        if (empty($student_details)) {
            $student_details = new StudentDetails();
        }

        $student_details->user_id = $user_id;
        $student_details->parent_id = $parent_id;
        $student_details->campus_id = $campus_id;
        $student_details->admission_number = $data['admission_number'];
        $student_details->rool_number = $data['roll_number'];
        $student_details->student_name = $data['student_name'];
        $student_details->gender = $data['gender'];
        $student_details->date_of_birth = $data['date_of_birth'];
        $student_details->category = $data['category'];
        $student_details->religion = $data['religion'];
        $student_details->caste = $data['caste'];
        $student_details->phone_number = $data['contact_number'];
        if (array_key_exists('student_class_id', $data)) {
            $student_details->student_class_id = $data['student_class_id'];
        } else {
            $student_details->student_class_id = 0;
        }
        if (array_key_exists('class_sections_id', $data)) {
            $student_details->section_id = $data['class_sections_id'];
        } else {
            $student_details->section_id = 0;
        }
        if (array_key_exists('academic_year_id', $data)) {

            $student_details->academic_year_id = $data['academic_year_id'];
        } else {
            $student_details->academic_year_id = 0;
        }
        $student_details->email = $data['email'];
        $student_details->admission_date = $data['admission_date'];
        $student_details->blood_group_id = $data['blood_group_id'];
        $student_details->student_house = $data['student_house'];
        $student_details->height = $data['height'];
        $student_details->weight = $data['weight'];
        $student_details->current_address = $data['current_address'];
        $student_details->permanent_address = $data['permanent_address'];
        $student_details->national_Identification_number = $data['national_Identification_number'];
        $student_details->mother_tongue = $data['mother_tongue'];
        $student_details->identification_marks = $data['identification_marks'];
        $student_details->previous_school = $data['previous_school'];
        $student_details->old_admission_number = $data['old_admission_number'];
        $student_details->bus_transport_required = $data['bus_transport_required'];
        $student_details->status = StudentDetails::STATUS_ACTIVE;

        $student_details->save(false);
    }







    public function actionPromoteStudents()
    {

        $model = new StudentDetails();
        $searchModel = new StudentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }

        return $this->render('promote_students', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionPromoteStudentsNextLevel()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $selection = $post['selection'];
        $academic_year_id = !empty($post['academic_year_id']) ? $post['academic_year_id'] : '';
        $student_class_id = !empty($post['student_class_id']) ? $post['student_class_id'] : '';
        $section_id = !empty($post['section_id']) ? $post['section_id'] : '';
        if (!empty($selection)) {
            foreach ($selection as $key => $selection_data) {
                $student_id = $selection_data;
                $status_id = 'status_id' . '_' . $selection[$key];
                // $status = $post[$status_id];
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    $student_details->status = $student_details->status;
                    $student_details->academic_year_id = $academic_year_id;
                    $student_details->student_class_id = $student_class_id;
                    $student_details->section_id = $section_id;
                    $student_details->save(false);
                }
            }
            $data['status'] = 'ok';
            $data['details'] = 'Students promoted successfully';
        } else {
            $data['status'] = 'nok';
            $data['error'] = 'required data is missing check again';
        }
        return json_encode($data);
    }
    public function actionUpdateStatus()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id']) && isset($post['val'])) {
            $transaction = StudentDetails::findOne($post['id']);

            if ($transaction !== null) {
                $transaction->status = $post['val'];

                if ($transaction->save(false)) {
                    return true; // Status updated successfully
                } else {
                    return false; // Unable to update status
                }
            } else {
                return false; // Transaction not found
            }
        } else {
            return false; // ID or status value not provided
        }
    }
}
