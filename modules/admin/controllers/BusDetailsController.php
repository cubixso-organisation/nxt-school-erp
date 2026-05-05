<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\Auth;
use app\modules\admin\models\BusDetails;
use app\modules\admin\models\BusRoute;
use app\modules\admin\models\Campus;
use app\modules\admin\models\CampusHasUsers;
use app\modules\admin\models\Designation;
use app\modules\admin\models\DriverHasBus;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\search\BusDetailsSearch;
use app\modules\admin\models\search\BusRouteSearch;
use app\modules\admin\models\search\EmployeeDetailsSearch;
use app\modules\admin\models\search\StudentAttendanceBusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BusDetailsController implements the CRUD actions for BusDetails model.
 */
class BusDetailsController extends Controller
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
                            'add-bus-route',
                            'add-bus-status',
                            'add-driver-has-bus',
                            'add-student-has-bus',
                            'bus-driver',
                            'driver-create',
                            'driver-update',
                            'bus-coordinator',
                            'coordinator-create',
                            'bus-coordinator-update',
                            'driver-view',
                            'bus-reports',
                            'view-bus-reports',
                            'bus-coordinator-delete',
                            'bus-driver-delete'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
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
                            'add-bus-route',
                            'add-bus-status',
                            'add-driver-has-bus',
                            'add-student-has-bus',
                            'bus-driver',
                            'driver-create',
                            'driver-update',
                            'bus-coordinator',
                            'coordinator-create',
                            'bus-coordinator-update',
                            'driver-view',
                            'bus-reports',
                            'view-bus-reports',
                            'bus-coordinator-delete',
                            'bus-driver-delete'
                        ],
                        'matchCallback' => function () {
                            return User::isManager();
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
     * Lists all BusDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    public function actionBusCoordinator()
    {
        $searchModel = new EmployeeDetailsSearch();
        $model = new EmployeeDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, USER::ROLE_BUS_COORDINATOR);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }

        return $this->render('bus-coordinator', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }


    public function actionBusCoordinatorDelete($id)
    {
        $model =  EmployeeDetails::find()->where(['id' => $id])->one();
        $model->status = EmployeeDetails::STATUS_DELETE;
        if ($model->save(false)) {
            return $this->redirect(['bus-coordinator']);
        }
    }





    public function actionDriverCreate()
    {
        $model = new EmployeeDetails();
        $model->scenario = 'create';
        $post = Yii::$app->request->post();
        $phone_number = isset($post['EmployeeDetails']['phone_number']) ? $post['EmployeeDetails']['phone_number'] : "";
        $user_role = User::ROLE_BUS_DRIVER;
        $checkUserDriver =  User::find()->where(['user_role' => $user_role])->andWhere(['contact_no' => $phone_number])->one();
        // var_dump($checkUserDriver);
        // exit;
        if (empty($checkUserDriver)) {



            if ($model->loadAll(Yii::$app->request->post())) {
                $createUser = new User();
                $employ_name = $post['EmployeeDetails']['employ_name'];
                $employee_id = $post['EmployeeDetails']['employee_id'];
                $age = $post['EmployeeDetails']['age'];
                $gender = $post['EmployeeDetails']['gender'];
                $blood_group_id = 3;
                $aadhar_number = $post['EmployeeDetails']['aadhar_number'];


                $email = $post['EmployeeDetails']['email'];
                $createUser->username = $phone_number . '@' . $user_role . '.com';
                $createUser->first_name = $employ_name;
                $createUser->contact_no = $phone_number;
                $createUser->user_role = $user_role;
                $createUser->create_user_id = Yii::$app->user->identity->id;
                $createUser->campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);

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
                        ->andWhere(['des_key' => User::ROLE_BUS_DRIVER])
                        ->one();
                    $model->user_id  = $createUser->id;
                    $model->employ_name  = $employ_name;
                    $model->phone_number  = $phone_number;
                    $model->employee_id  = $employee_id;
                    $model->age  = $age;
                    $model->gender  = $gender;
                    $model->aadhar_number  = $aadhar_number;
                    $model->blood_group_id  = $blood_group_id;
                    $model->email  = $email;
                    $model->user_role  = $user_role;

                    $model->campus_id   = User::getCampusesByUser(Yii::$app->user->identity->id);
                    $model->designation_id  = $designation_id_data->id;

                    $profile_picture = \yii\web\UploadedFile::getInstance($model, 'profile_picture');
                    if (!empty($profile_picture)) {
                        $profile_picture_img = Yii::$app->notification->imageKitUpload($profile_picture, 'profile_picture');
                        $model->profile_picture = $profile_picture_img['url'];
                    }




                    $id_proof = \yii\web\UploadedFile::getInstance($model, 'id_proof');
                    if (!empty($id_proof)) {
                        $image = Yii::$app->notification->imageKitUpload($id_proof, 'id_proof');
                        $model->id_proof = $image['url'];
                    }

                    $licence_front = \yii\web\UploadedFile::getInstance($model, 'licence_front');
                    if (!empty($licence_front)) {
                        $image = Yii::$app->notification->imageKitUpload($licence_front, 'licence');
                        $model->licence_front = $image['url'];
                    }


                    $licence_back = \yii\web\UploadedFile::getInstance($model, 'licence_back');
                    if (!empty($licence_back)) {
                        $image = Yii::$app->notification->imageKitUpload($licence_back, 'licence');
                        $model->licence_back = $image['url'];
                    }



                    $aadhar_image = \yii\web\UploadedFile::getInstance($model, 'aadhar_image');
                    if (!empty($aadhar_image)) {
                        $image = Yii::$app->notification->imageKitUpload($aadhar_image, 'aadhar');
                        $model->aadhar_image = $image['url'];
                    }





                    $aadhar_back = \yii\web\UploadedFile::getInstance($model, 'aadhar_back');
                    if (!empty($aadhar_back)) {
                        $image = Yii::$app->notification->imageKitUpload($aadhar_back, 'aadhar');
                        $model->aadhar_back = $image['url'];
                    }




                    if ($model->save(false)) {
                        if (!empty($post['EmployeeDetails']['bus_id'])) {
                            $bus = new DriverHasBus();
                            $bus->campus_id   = User::getCampusesByUser(Yii::$app->user->identity->id);
                            $bus->driver_id    = $createUser->id;
                            $bus->bus_id     = $post['EmployeeDetails']['bus_id'];
                            $bus->status     = DriverHasBus::STATUS_ACTIVE;
                            $bus->save(false);
                        }
                        //create campus access to user
                        $campus_has_users = new CampusHasUsers();
                        $campus_has_users->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $campus_has_users->user_id = $model->user_id;
                        $campus_has_users->status = CampusHasUsers::STATUS_ACTIVE;
                        $campus_has_users->save(false);
                    } else {
                        $deleteUser = User::find()->where(['id' => $createUser->id])->one();
                        $deleteUser->delete();
                        return $this->render('driver_create', [
                            'model' => $model,
                        ]);
                    }
                }

                return $this->redirect(['bus-driver']);
            } else {
                return $this->render('driver_create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->addError('phone_number', 'User Already exist');

            return $this->render('driver_create', [
                'model' => $model,
            ]);
        }
    }



    public function actionDriverView($id)
    {
        $model = EmployeeDetails::find()->where(['id' => $id])->one();

        return $this->render('view_bus_driver', [
            'model' => $model,

        ]);
    }



    public function actionCoordinatorCreate()
    {
        $model = new EmployeeDetails();
        $model->scenario = 'create';
        $post = Yii::$app->request->post();
        if (isset($post['EmployeeDetails']['phone_number'])) {
            $phone_number = $post['EmployeeDetails']['phone_number'];
        } else {
            $phone_number = ''; // Handle the missing value (e.g., set a default value)
        }
        
        $user_role = User::ROLE_BUS_COORDINATOR;
        $userCheck =  User::find()->where(['contact_no' => $phone_number])->andWhere(['user_role' => $user_role])->one();
        if (empty($userCheck)) {


            if ($model->loadAll(Yii::$app->request->post())) {
                $createUser = new User();
                $employ_name = $post['EmployeeDetails']['employ_name'];
                $employee_id = $post['EmployeeDetails']['employee_id'];
                $age = $post['EmployeeDetails']['age'];
                $gender = $post['EmployeeDetails']['gender'];
                $blood_group_id = 3;

                $phone_number = $post['EmployeeDetails']['phone_number'];
                $email = $post['EmployeeDetails']['email'];
                $createUser->username = $phone_number . '@' . $user_role . '.com';
                $createUser->first_name = $employ_name;
                $createUser->contact_no = $phone_number;
                $createUser->user_role = $user_role;
                $createUser->create_user_id = Yii::$app->user->identity->id;
                $createUser->campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);



                if ($createUser->save(false)) {
                    $providerId = $createUser->user_role;
                    $auth_id = $phone_number;
                    $auth = new Auth();
                    $auth->user_id = $createUser->id;
                    $auth->source = $providerId;
                    $auth->source_id = $auth_id;
                    $auth->save(false);
                    $designation_id_data = Designation::find()->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['des_key' => User::ROLE_BUS_COORDINATOR])->one();

                    if (!empty(\yii\web\UploadedFile::getInstance($model, 'profile_picture'))) {
                        $profile_picture = \yii\web\UploadedFile::getInstance($model, 'profile_picture');
                        $image = Yii::$app->notification->imageKitUpload($profile_picture, 'profile_picture');
                        $model->profile_picture = $image['url'];
                    }


                    $model->user_id  = $createUser->id;
                    $model->employ_name  = $employ_name;
                    $model->phone_number  = $phone_number;
                    $model->employee_id  = $employee_id;
                    $model->age  = $age;
                    $model->gender  = $gender;
                    $model->blood_group_id  = $blood_group_id;
                    $model->email  = $email;
                    $model->user_role  = $user_role;

                    $model->campus_id   = User::getCampusesByUser(Yii::$app->user->identity->id);
                    $model->designation_id  = $designation_id_data->id;
                    if ($model->save(false)) {
                        //create campus access to user
                        $campus_has_users = new CampusHasUsers();
                        $campus_has_users->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $campus_has_users->user_id = $model->user_id;
                        $campus_has_users->status = CampusHasUsers::STATUS_ACTIVE;
                        $campus_has_users->save(false);
                    } else {
                        $deleteUser = User::find()->where(['id' => $createUser->id])->one();
                        $deleteUser->delete();
                        return $this->render('coordinator-create', [
                            'model' => $model,
                        ]);
                    }
                }

                return $this->redirect(['bus-coordinator']);
            } else {
                return $this->render('coordinator-create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->addError('phone_number', 'user already exist');
            return $this->render('coordinator-create', [
                'model' => $model,
            ]);
        }
    }






    public function actionBusCoordinatorUpdate($id)
    {
        $model = EmployeeDetails::find()->where(['id' => $id])->one();
        // $model->scenario ='update';
        if ($model->loadAll(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();
            if (!empty(\yii\web\UploadedFile::getInstance($model, 'profile_picture'))) {
                $profile_picture = \yii\web\UploadedFile::getInstance($model, 'profile_picture');
                $image = Yii::$app->notification->imageKitUpload($profile_picture, 'profile_picture');
                $model->profile_picture = $image['url'];
            }


            $model->save();
            $findAuth = Auth::find()->where(['user_id' => $model->user_id])->one();
            if (!empty($findAuth)) {
                $findAuth->source = $model->phone_number;
                $findAuth->source_id = $model->user_id;
                $findAuth->save(false);
            }
            return $this->redirect(['bus-coordinator']);
        } else {
            return $this->render('coordinator-update', [
                'model' => $model,
            ]);
        }
    }










    public function actionDriverUpdate($id)
    {
        $model = EmployeeDetails::find()->where(['id' => $id])->one();
        // $model->scenario ='update';


        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();


            if (!empty(\yii\web\UploadedFile::getInstance($model, 'id_proof'))) {
                $id_proof = \yii\web\UploadedFile::getInstance($model, 'id_proof');
                $image = Yii::$app->notification->imageKitUpload($id_proof, 'id_proof');
                $model->id_proof = $image['url'];
            }


            if (!empty(\yii\web\UploadedFile::getInstance($model, 'profile_picture'))) {
                $id_proof = \yii\web\UploadedFile::getInstance($model, 'profile_picture');
                $image = Yii::$app->notification->imageKitUpload($id_proof, 'profile_picture');
                $model->profile_picture = $image['url'];
            }



            $licence_front = \yii\web\UploadedFile::getInstance($model, 'licence_front');

            if (!empty($licence_front)) {
                $image = Yii::$app->notification->imageKitUpload($licence_front, 'licence');


                $model->licence_front = $image['url'];
            }


            $licence_back = \yii\web\UploadedFile::getInstance($model, 'licence_back');
            if (!empty($licence_back)) {
                $image = Yii::$app->notification->imageKitUpload($licence_back, 'licence');
                $model->licence_back = $image['url'];
            }



            $aadhar_image = \yii\web\UploadedFile::getInstance($model, 'aadhar_image');
            if (!empty($aadhar_image)) {
                $image = Yii::$app->notification->imageKitUpload($aadhar_image, 'aadhar_image');
                $model->aadhar_image = $image['url'];
            }


            $aadhar_back = \yii\web\UploadedFile::getInstance($model, 'aadhar_back');
            if (!empty($aadhar_back)) {
                $image = Yii::$app->notification->imageKitUpload($aadhar_back, 'aadhar_image');
                $model->aadhar_back = $image['url'];
            }


            $model->save();
            $findAuth = Auth::find()->where(['user_id' => $model->user_id])->one();

            $user = User::find()->where(['id' => $model->user_id])->one();
            $user->username = $model->phone_number . '@' . USER::ROLE_BUS_DRIVER . '.com';
            $user->first_name = $model->employ_name;
            $user->contact_no = $model->phone_number;
            $user->save(false);


            if (!empty($findAuth)) {
                $findAuth->source = User::ROLE_BUS_DRIVER;
                $findAuth->source_id = $model->phone_number;
                $findAuth->save(false);
            } else {
                $authSave = new Auth();
                $authSave->user_id  = $model->user_id;
                $authSave->source = User::ROLE_BUS_DRIVER;
                $authSave->source_id = $model->phone_number;
                $authSave->save(false);
            }







            return $this->redirect(['bus-driver']);
        } else {
            return $this->render('driver_update', [
                'model' => $model,
            ]);
        }
    }


    public function actionBusDriver()
    {
        $searchModel = new EmployeeDetailsSearch();
        $model = new EmployeeDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, USER::ROLE_BUS_DRIVER);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams, USER::ROLE_BUS_DRIVER);
        }
        return $this->render('bus-driver', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * Displays a single BusDetails model.
     * @param integer $id
     * @return mixed
     */


    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModelBusRoute = new BusRouteSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProviderBusRoute = $searchModelBusRoute->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProviderBusRoute = $searchModelBusRoute->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderBusRoute = $searchModelBusRoute->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id), $id);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderBusRoute = $searchModelBusRoute->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id), $id);
        }



        $bus_route = BusRoute::find()->where(['bus_id' => $model->id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'bus_route' => $bus_route,
            'searchModelBusRoute' => $searchModelBusRoute,
            'dataProviderBusRoute' => $dataProviderBusRoute

        ]);
    }

    /**
     * Creates a new BusDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusDetails();

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->status = BusDetails::STATUS_PARKING;
            $model->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            if ($model->save(false)) {
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BusDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BusDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */


    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = BusDetails::find()->where([
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
     * Finds the BusDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusDetails::findOne($id)) !== null) {
            return $model;
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


    public function actionDelete($id)
    {
        $model = $this->findModel($id);


        if (!empty($model)) {
            if ($model->status == BusDetails::STATUS_PARKING) {
                $model->current_status = BusDetails::current_status_in_active;
                if ($model->save(false)) {
                    $driver_has_bus = DriverHasBus::find()->where(['bus_id' => $id])->all();
                    foreach ($driver_has_bus as $driver_has_bus_data) {
                        $driver_has_bus_data->status = DriverHasBus::STATUS_INACTIVE;
                        $driver_has_bus_data->save(false);
                    }
                }
            } else {
                Yii::$app->session->setFlash('danger', 'You Can Not Delete Or Update While bus driving Mode Retry again!');


                return $this->redirect(['index']);
            }
        }


        return $this->redirect(['index']);
    }

    public function actionBusReports()
    {
        $searchModel = new BusDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }

        return $this->render('bus_reports', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewBusReports($id)
    {
        $searchModel = new StudentAttendanceBusSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, $id);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, $id);
        }


        return $this->render('view_bus_reports', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'busId' => $id,
        ]);
    }


    public function actionBusDriverDelete($id)
    {
        $employee_details = EmployeeDetails::find()->where(['id' => $id])->one();
        if (!empty($employee_details)) {
            $user_id  = $employee_details->user_id;
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (empty($driver_has_bus)) {
                $employee_details->status = EmployeeDetails::STATUS_DELETE;
                if ($employee_details->save(false)) {
                    $user = User::find()->where(['id' => $user_id])->one();
                    if (!empty($user)) {
                        $user->status = User::STATUS_INACTIVE;
                        $user->save(false);
                        Yii::$app->session->setFlash('success', 'Driver Deleted Successfully');
                        return $this->redirect(['bus-driver']);
                        return $this->redirect(['bus-driver']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'You Can Not delete driver at this moment');
                    return $this->redirect(['bus-driver']);
                }
            } else {

                Yii::$app->session->setFlash('error', 'You Can not delete driver ,driver Assigned to Bus');
                return $this->redirect(['bus-driver']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Driver Details Not Found');
            return $this->redirect(['bus-driver']);
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
}
