<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\base\CampusHasUsers;
use Yii;
use app\models\User;
use app\modules\admin\models\AgentStudentJoin;
use app\modules\admin\models\Auth;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\Designation;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\search\EmployeeDetailsSearch;
use app\modules\admin\models\search\SpecialCoursesSearch;
use app\modules\admin\models\search\StudentClassSearch;
use app\modules\admin\models\StudentDetailsAgentLead;
use app\modules\admin\models\search\StudentDetailsAgentLeadSearch;
use app\modules\admin\models\SpecialCourses;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentSpecialCourses;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/** 
 * StudentDetailsAgentLeadController implements the CRUD actions for StudentDetailsAgentLead model.
 */
class StudentDetailsAgentLeadController extends Controller
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
                            'index', 'view', 'create', 'update', 'delete', 'update-status', 'add-agent-student-join', 'agents',
                            'agents-create', 'agent-update', 'agent-student-class', 'agent-special-courses', 'student-class-create', 'agent-special-courses-create',
                            'student-class-update', 'agent-special-courses-update', 'agent-view', 'status-change'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'view', 'create', 'update', 'delete', 'update-status', 'add-agent-student-join', 'agents', 'agents-create',
                            'agent-update', 'agent-student-class', 'agent-special-courses', 'student-class-create', 'agent-special-courses-create',
                            'student-class-update', 'agent-special-courses-update', 'agent-view', 'status-change'
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
     * Lists all StudentDetailsAgentLead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentDetailsAgentLeadSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
            $dataProvider_status_status_admission_ok  = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id), StudentDetailsAgentLead::status_admission_ok);
            $dataProvider_status_status_admission_not_ok   = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id), StudentDetailsAgentLead::status_admission_not_ok);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));

            $dataProvider_status_status_admission_ok  = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id), StudentDetailsAgentLead::status_admission_ok);
            $dataProvider_status_status_admission_not_ok   = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id), StudentDetailsAgentLead::status_admission_not_ok);
        }
        $data['total_pending_payments'] = AgentStudentJoin::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => AgentStudentJoin::STATUS_PENDING])->count();
        $data['total_success_payments'] = AgentStudentJoin::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => AgentStudentJoin::STATUS_PAID])->count();
        $data['total_failed_payments'] = AgentStudentJoin::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => AgentStudentJoin::STATUS_FAILED])->count();
        $data['total_received_amount'] = AgentStudentJoin::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => AgentStudentJoin::STATUS_PAID])->sum('amount');




        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider_status_status_admission_ok' => $dataProvider_status_status_admission_ok,
            'dataProvider_status_status_admission_not_ok' => $dataProvider_status_status_admission_not_ok,
            'data' => $data

        ]);
    }



    public function actionAgentStudentClass()
    {
        $searchModel = new StudentClassSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, 1);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams, 1);
        }
        return $this->render('agent-student-class', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionStudentClassUpdate($id)
    {
        $model = StudentClass::find()->where(['id' => $id])->one();

        if ($model->loadAll(Yii::$app->request->post())) {
            if (User::isCampusAdmin()) {
                $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->campus_id = $campusId;
            }
            if ($model->saveAll()) {
                return $this->redirect(['agent-student-class']);
            }
        } else {
            return $this->render('student_class_update', [
                'model' => $model,
            ]);
        }
    }




    public function actionAgentSpecialCoursesUpdate($id)
    {
        $model = SpecialCourses::find()->where(['id' => $id])->one();

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['agent-special-courses']);
        } else {
            return $this->render('agent-special-courses-update', [
                'model' => $model,
            ]);
        }
    }



    public function actionAgentView($id)
    {
        $model = EmployeeDetails::find()->where(['id' => $id])->one();


        return $this->render('agent-view', [
            'model' => $model,

        ]);
    }






    public function actionStudentClassCreate()
    {
        $model = new StudentClass();

        if ($model->loadAll(Yii::$app->request->post())) {
            if (User::isCampusAdmin()) {
                $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->campus_id = $campusId;
                $model->status =  StudentClass::STATUS_ACTIVE;
                $model->is_agent = 1;
            }
            if ($model->saveAll()) {
                return $this->redirect(['agent-student-class']);
            }
            return $this->redirect(['agent-student-class']);
        } else {
            return $this->render('student-class-create', [
                'model' => $model,
            ]);
        }
    }





    public function actionAgentSpecialCoursesCreate()
    {
        $model = new SpecialCourses();

        if ($model->loadAll(Yii::$app->request->post())) {
            if (User::isCampusAdmin()) {
                $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->campus_id = $campusId;
            }
            $model->status =  SpecialCourses::STATUS_ACTIVE;
            $model->is_agent = 1;
            if ($model->saveAll()) {
                return $this->redirect(['agent-special-courses']);
            }
            return $this->redirect(['agent-special-courses']);
        } else {
            return $this->render('agent-special-courses-create', [
                'model' => $model,
            ]);
        }
    }





    public function actionAgentSpecialCourses()
    {
        $searchModel = new SpecialCoursesSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, 1);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams, 1);
        }
        return $this->render('agent-special-courses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    public function actionAgents()
    {
        $searchModel = new EmployeeDetailsSearch();

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, User::ROLE_AGENT);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams, User::ROLE_AGENT);
        }
        return $this->render('agents', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single StudentDetailsAgentLead model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerAgentStudentJoin = new \yii\data\ArrayDataProvider([
            'allModels' => $model->agentStudentJoins,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerAgentStudentJoin' => $providerAgentStudentJoin,
        ]);
    }

    /**
     * Creates a new StudentDetailsAgentLead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentDetailsAgentLead();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StudentDetailsAgentLead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // var_dump($model->agent_id);exit;

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            if ($model->status == StudentDetailsAgentLead::status_admission_not_ok) {
                $title = "Admission Not Approved";
                $message = "Your admission has not been approved.";

                // Send notification for admission not approved
                $sendNoti = Yii::$app->notification->UserNotification('', $model->agent_id, $title, $message);
            } elseif ($model->status == StudentDetailsAgentLead::status_admission_ok) {
                $title = "Admission Approved";
                $message = "Congratulations! Your admission has been approved.";

                // Send notification for admission approved
                $sendNoti = Yii::$app->notification->UserNotification('', $model->agent_id, $title, $message);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing StudentDetailsAgentLead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = StudentDetailsAgentLead::STATUS_DELETE;
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
            $model = StudentDetailsAgentLead::find()->where([
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



    public function actionAgentsCreate()
    {
        $model = new EmployeeDetails();
        $model->scenario  = 'create';
        $post = Yii::$app->request->post();
        if (empty($post)) {
            return $this->render('agents_create', [

                'model' => $model,

            ]);
        }
        $phone_number = $post['EmployeeDetails']['phone_number'];
        $user_role = USER::ROLE_AGENT;
        $checkUserAgent =  User::find()->where(['user_role' => $user_role])->andWhere(['username' => $phone_number . '@' . USER::ROLE_AGENT . '.com'])->one();
        if (empty($checkUserAgent)) {

            if ($model->loadAll(Yii::$app->request->post())) {
                $getCampusId = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
                $createUser = new User();
                $employ_name = $post['EmployeeDetails']['employ_name'];
                $phone_number = $post['EmployeeDetails']['phone_number'];
                $employee_id = $post['EmployeeDetails']['employee_id'];
                $agent_type = $post['EmployeeDetails']['agent_type'];



                $age = $post['EmployeeDetails']['age'];
                $gender = $post['EmployeeDetails']['gender'];
                $blood_group_id = $post['EmployeeDetails']['blood_group_id'];
                $email = $post['EmployeeDetails']['email'];



                $createUser->username = $phone_number . '@' . USER::ROLE_AGENT . '.com';
                $createUser->first_name = $employ_name;
                $createUser->contact_no = $phone_number;
                $createUser->campus_id = $getCampusId;
                $createUser->user_role = USER::ROLE_AGENT;

                if ($createUser->save(false)) {
                    $providerId = $createUser->user_role;
                    $auth_id = $phone_number;
                    $auth = new Auth();
                    $auth->user_id = $createUser->id;
                    $auth->source = $providerId;
                    $auth->source_id = $auth_id;
                    $auth->save(false);
                    $designation_id_data = Designation::find()
                        ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['des_key' => User::ROLE_AGENT])
                        ->one();
                    $model->user_id  = $createUser->id;
                    $model->employ_name  = $employ_name;
                    $model->phone_number  = $phone_number;
                    $model->employee_id  = $employee_id;
                    $model->agent_type  = $agent_type;

                    $model->age  = $age;
                    $model->gender  = $gender;
                    $model->blood_group_id  = $blood_group_id;
                    $model->email  = $email;
                    $model->campus_id   = User::getCampusesByUser(Yii::$app->user->identity->id);
                    $model->designation_id  = $designation_id_data->id;
                    // $model->user_role  = $user_role;


                    $id_proof_upload = \yii\web\UploadedFile::getInstance($model, 'id_proof');
                    if (!empty($id_proof_upload)) {
                        $image = Yii::$app->notification->imageKitUpload($id_proof_upload);
                        $model->id_proof = $image['url'];
                    }


                    $qr_code_file = \yii\web\UploadedFile::getInstance($model, 'qr_code_file');
                    if (!empty($qr_code_file)) {
                        $image = Yii::$app->notification->imageKitUpload($qr_code_file);
                        $model->qr_code_file = $image['url'];
                    }



                    //create campus access to user
                    if ($model->save(false)) {
                        $campus_has_users = new CampusHasUsers();
                        $campus_has_users->campus_id  = $model->campus_id;
                        $campus_has_users->user_id = $model->user_id;
                        $campus_has_users->status = CampusHasUsers::STATUS_ACTIVE;
                        $campus_has_users->save(false);
                    } else {
                        $deleteUser = User::find()->where(['id' => $createUser->id])->one();
                        $deleteUser->delete();
                        return $this->render('agents_create', [
                            'model' => $model,
                        ]);
                    }
                }
                return $this->redirect(['agents']);
            } else {
                return $this->render('agents_create', [
                    'model' => $model,
                ]);
            }
        } else {

            $model->addError('contact_no', 'User Already exist this contact number');
            return $this->render('agents_create', [
                'model' => $model,
            ]);
        }
    }


    public function actionAgentUpdate($id)
    {
        $model = EmployeeDetails::find()->where(['id' => $id])->one();
        $model->scenario  = 'update';

        $id_proof = $model->id_proof;
        $qr_code_file = $model->qr_code_file;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            $phone_number = isset($post['EmployeeDetails']['phone_number']) ? $post['EmployeeDetails']['phone_number'] : '';
            $employee_name = isset($post['EmployeeDetails']['employ_name']) ? $post['EmployeeDetails']['employ_name'] : '';
            // var_dump($employee_name);exit;
            $id_proof_upload = \yii\web\UploadedFile::getInstance($model, 'id_proof');
            if (!empty($id_proof_upload)) {
                $image = Yii::$app->notification->imageKitUpload($id_proof_upload);
                $model->id_proof = $image['url'];
            } else {
                $model->id_proof = $id_proof;
            }

            $qr_code_file = \yii\web\UploadedFile::getInstance($model, 'qr_code_file');
            if (!empty($qr_code_file)) {
                $image = Yii::$app->notification->imageKitUpload($qr_code_file);
                $model->qr_code_file = $image['url'];
            } else {
                $model->qr_code_file = $qr_code_file;
            }

            $model->employ_name = $employee_name;


            if ($model->save(false)) {
                //update user



                $findAuth = Auth::find()->where(['user_id' => $model->user_id])->one();
                $user = User::find()->where(['id' => $model->user_id])->one();
                $user->username = $phone_number . '@' . USER::ROLE_AGENT . '.com';
                $user->first_name = $model->employ_name;
                $user->contact_no = $phone_number;
                $user->save(false);

                if (!empty($findAuth)) {
                    $findAuth->user_id  = $model->user_id;
                    $findAuth->source = User::ROLE_AGENT;
                    $findAuth->source_id = $user->contact_no;
                    $findAuth->save(false);
                } else {
                    $authSave = new Auth();
                    $authSave->user_id  = $model->user_id;
                    $authSave->source = User::ROLE_AGENT;
                    $authSave->source_id = $user->contact_no;
                    $authSave->save(false);
                }
                return $this->redirect(['agents']);
            } else {
                //validate phone number


                return $this->render('agent_update', [
                    'model' => $model,
                ]);
            }
        } else {



            return $this->render('agent_update', [
                'model' => $model,
            ]);
        }
    }



    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {
            $transaction = StudentDetailsAgentLead::find()->where(['id' =>  $post['id']])->one();
            if (!empty($transaction)) {
                $transaction->status = $post['val'];

                if ($transaction->update(false)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }





    /**
     * Finds the StudentDetailsAgentLead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentDetailsAgentLead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentDetailsAgentLead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AgentStudentJoin
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddAgentStudentJoin()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AgentStudentJoin');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formAgentStudentJoin', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
