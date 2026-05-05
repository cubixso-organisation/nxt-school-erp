<?php

namespace app\modules\admin\controllers;

use app\components\Dashboard;
use Yii;
use Exception;
use app\models\Auth;
use app\models\User;
use yii\base\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\Campus;
use app\modules\admin\forms\UserForm;
use app\modules\admin\models\UserSearch;
use app\traits\controllers\FindModelOrFail;
use app\modules\admin\models\UserHasModules;
use app\modules\admin\models\User as ModelsUser;
use app\modules\admin\models\base\CashbackTransaction;
use app\modules\admin\models\base\ParentDetails;
use app\modules\leavemanagement\models\base\DashboardNotification;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
    use FindModelOrFail;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->modelClass = UserForm::class;
    }




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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'user-data', 'shadow-login', 'auto-login', 'go-to-admin', 'edit-admin', 'index-teacher', 'teacher-update', 'index-parent', 'parent-update'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'view', 'create', 'update', 'delete', 'update-status', 'user-data',
                            'shadow-login', 'auto-login', 'go-to-admin', 'key-persons', 'key-person-create', 'key-person-update', 'status-change', 'edit-admin', 'create-bus-coordinator', 'bus-coordinator', 'index-teacher', 'teacher-update',
                            'get-notifications', 'index-parent', 'parent-update'
                        ],
                        'matchCallback' => function () {
                            return User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isLibraryManager();
                        }
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }




    //start now change status

    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {
            $transaction = User::find()->where(['id' =>  $post['id']])->one();
            if ($transaction->user_role == User::role_key_person) {
                $transaction->status = $post['val'];
                if (!empty($transaction)) {
                    $transaction->status = $post['val'];
                    if ($transaction->update(false)) {
                        $update_remaining_users = User::find()->where(['user_role' => User::role_key_person])
                            ->andWhere(['not in', 'id', $transaction->id])
                            ->andWhere(['campus_id' =>  User::getCampusesByUser(Yii::$app->user->identity->id)])->all();
                        if (!empty($update_remaining_users)) {
                            if ($transaction->status == User::STATUS_ACTIVE) {
                                foreach ($update_remaining_users as $update_remaining_users_data) {
                                    $update_remaining_users_data->status = User::STATUS_INACTIVE;
                                    $update_remaining_users_data->save(false);
                                }
                            }
                        }

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
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
    }

    ///end now change status




    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->user->identity->id;

        $searchModel = new UserSearch();

        if (User::isAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN, USER::ROLE_INSTITUTE_ADMIN]);
        } elseif (User::isInstituteAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN, User::ROLE_BUS_COORDINATOR], '', Yii::$app->user->identity->id);
        } elseif (User::isCampusAdmin()) {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                [USER::role_campus_sub_admin, USER::ROLE_CAMPUS_ADMIN],
                '',
                Yii::$app->user->identity->id
            );
        }
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndexTeacher()
    {
        Yii::$app->user->identity->id;

        $searchModel = new UserSearch();

        if (User::isCampusAdmin()) {
            $dataProvider = $searchModel->teacherSearch(Yii::$app->request->queryParams, [USER::role_teacher]);
        }
        return $this->render('index-teacher', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndexParent()
    {
        Yii::$app->user->identity->id;

        $searchModel = new UserSearch();

        if (User::isCampusAdmin()) {
            $dataProvider = $searchModel->parentSearch(Yii::$app->request->queryParams, [USER::role_teacher]);
        }
        return $this->render('index-parent', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionBusCoordinator()
    {
        Yii::$app->user->identity->id;

        $searchModel = new UserSearch();

        if (User::isAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN, USER::ROLE_INSTITUTE_ADMIN]);
        } elseif (User::isInstituteAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::ROLE_CAMPUS_ADMIN, User::ROLE_BUS_COORDINATOR], '', Yii::$app->user->identity->id);
        } elseif (User::isCampusAdmin()) {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                [USER::role_campus_sub_admin],
                '',
                Yii::$app->user->identity->id
            );
        }
        return $this->render('bus_coordinator', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm();
        $model->on(User::EVENT_BEFORE_INSERT, [$model, 'generateAuthKey']);
        $post = Yii::$app->request->post();



        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }
        if ($model->load(Yii::$app->request->post())) {
            $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->campus_id = $campus_id;
            if ($model->save()) {
                $post = Yii::$app->request->post();




                $activation_modules = isset($post['UserForm']['module_id']) ? $post['UserForm']['module_id'] : '';
                if (!empty($activation_modules)) {
                    $activation = (new User())->getActionModeOptionsSave();
                    foreach ($activation as $kay => $activation_modules_data) {
                        $activation_modules_save = new  UserHasModules();
                        $activation_modules_save->user_id  = $model->id;
                        $activation_modules_save->module_id = $activation_modules_data;
                        $activation_modules_save->campus_id  = $campus_id;
                        $activation_modules_save->status = UserHasModules::STATUS_INACTIVE;
                        $activation_modules_save->save(false);
                    }

                    foreach ($activation_modules as $activation_modules_data) {
                        $activation_modules_exist = UserHasModules::find()
                            ->where(['campus_id' => $campus_id])
                            ->andWhere(['module_id' => $activation_modules_data])
                            ->andWhere(['user_id' => $model->id])
                            ->one();
                        if (!empty($activation_modules_exist)) {
                            $activation_modules_save = UserHasModules::find()
                                ->where(['id' => $activation_modules_exist->id])
                                ->one();
                        } else {
                            $activation_modules_save = new  UserHasModules();
                        }
                        $activation_modules_save->user_id = $model->id;
                        $activation_modules_save->campus_id = $campus_id;
                        $activation_modules_save->module_id = $activation_modules_data;
                        $activation_modules_save->status = UserHasModules::STATUS_ACTIVE;
                        $activation_modules_save->save(false);
                    }
                }




                $lastInsertId = $model->id;
                $create_user_id = Yii::$app->user->identity->id;
                if (User::isAdmin()) {
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $updateUser->save(false);
                } elseif (User::isInstituteAdmin()) {
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $updateUser->save(false);
                } elseif (User::isCampusAdmin()) {
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $updateUser->save(false);
                }
                if ($model->user_role == User::ROLE_BUS_COORDINATOR) {
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $auth = new Auth();
                    $auth->user_id = $model->id;
                    $auth->source = $model->user_role;
                    $auth->source_id = $model->contact_no;
                    $auth->save(false);
                }

                if ($model->user_role == User::ROLE_PARENT) {
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $updateUser->save(false);
                    $auth = new Auth();
                    $auth->user_id = $model->id;
                    $auth->source = $model->user_role;
                    $auth->source_id = $model->contact_no;
                    $auth->save(false);
                }




                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->getErrors());
                exit;
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateBusCoordinator()
    {


        $model = new UserForm();
        $model->on(User::EVENT_BEFORE_INSERT, [$model, 'generateAuthKey']);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }

        $post = Yii::$app->request->post();
        $contact_no = $post['UserForm']['contact_no'];
        $user_role = $post['UserForm']['user_role'];
        $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => $user_role])->one();


        if (empty($user_check)) {
            if ($model->load(Yii::$app->request->post())) {
                $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->campus_id = $campus_id;
                if ($model->save()) {
                    $lastInsertId = $model->id;
                    $create_user_id = Yii::$app->user->identity->id;
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $updateUser->save(false);
                    $updateUser = User::find()->where(['id' => $lastInsertId])->one();
                    $updateUser['create_user_id'] = $create_user_id;
                    $auth = new Auth();
                    $auth->user_id = $model->id;
                    $auth->source = $model->user_role;
                    $auth->source_id = $model->contact_no;
                    $auth->save(false);
                    return $this->redirect(['bus-coordinator']);
                } else {
                    $model->getErrors();
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->addError('contact_no', 'user already exist');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }





    public function actionKeyPersonCreate()
    {
        $model = new User();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }
        $post = Yii::$app->request->post();
        $user_role = User::role_key_person;
        $contact_no = isset($post['User']['contact_no']) ? $post['User']['contact_no'] : "";

        $user_check = User::find()->where(['user_role' => $user_role])->andWhere(['contact_no' => $contact_no])->one();
        if (empty($user_check)) {


            if ($model->load(Yii::$app->request->post())) {


                $create_user_id = Yii::$app->user->identity->id;
                $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->campus_id = $campus_id;
                $model->create_user_id = $create_user_id;
                if ($model->save()) {
                    if ($model->status == User::STATUS_ACTIVE) {
                        $update_remaining_users = User::find()->where(['user_role' => User::role_key_person])
                            ->andWhere(['not in', 'id', $model->id])
                            ->andWhere(['campus_id' =>  User::getCampusesByUser(Yii::$app->user->identity->id)])->all();
                        if (!empty($update_remaining_users)) {
                            if ($model->status == User::STATUS_ACTIVE) {
                                foreach ($update_remaining_users as $update_remaining_users_data) {
                                    $update_remaining_users_data->status = User::STATUS_INACTIVE;
                                    $update_remaining_users_data->save(false);
                                }
                            }
                        }
                    }


                    $lastInsertId = $model->id;
                    return $this->redirect(['key-persons']);
                } else {
                    return $this->render('key_person_create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('key_person_create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->addError('contact_no', 'user already exist');

            return $this->render('key_person_create', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /**
         * @var UserForm $model
         */
        $model = $this->findModel($id);
        $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                $post = Yii::$app->request->post();
                $activation_modules = !empty($post['UserForm']['module_id']) ? $post['UserForm']['module_id'] : '';
                $activation = (new User())->getActionModeOptionsSave();

                if (!empty($activation) && !empty($activation_modules)) {
                    $dff =  array_merge(array_diff($activation, $activation_modules), array_diff($activation, $activation));
                    if (!empty($dff)) {
                        foreach ($dff as $dff_data) {
                            $inactiveActivateModes = UserHasModules::find()->where(['module_id' => $dff_data])
                                ->andWhere(['campus_id' => $campus_id])
                                ->andWhere(['user_id' => $model->id])
                                ->one();
                            $inactiveActivateModes->status = UserHasModules::STATUS_INACTIVE;
                            $inactiveActivateModes->save(false);
                        }
                    }
                }

                if (!empty($activation_modules)) {
                    foreach ($activation_modules as $activation_modules_data) {
                        $inactiveActive = UserHasModules::find()->where(['module_id' => $activation_modules_data])
                            ->andWhere(['campus_id' => $campus_id])
                            ->one();
                        $inactiveActive->status =  UserHasModules::STATUS_ACTIVE;
                        $inactiveActive->save(false);
                    }
                }
                // Check if the user role is admin
                if (Yii::$app->user->identity->user_role === User::ROLE_ADMIN) {
                    // Set a success message
                    Yii::$app->session->setFlash('success', 'Record updated successfully.');

                    // Redirect to the referrer page
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // Redirect to the view page if the user role is not admin
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // Handle error case
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    public function actionEditAdmin($id)
    {


        /**
         * @var UserForm $model
         */
        $model = $this->findModel($id);
        $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);


        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $post = Yii::$app->request->post();




                return $this->redirect(['/admin/dashboard']);
            } else {
            }
        }
        return $this->render('update_edit_self', [
            'model' => $model,
        ]);
    }
    public function actionTeacherUpdate($id)
    {

        // dd();

        $model = $this->findModel($id);
        $campus_id =  User::getCampusesByUser(Yii::$app->user->identity->id);


        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                $post = Yii::$app->request->post();




                return $this->redirect(['/admin/users/index-teacher']);
            } else {
            }
        }
        return $this->render('_form_edit.php', [
            'model' => $model,
        ]);
    }
    public function actionParentUpdate($id)
    {
        $model = $this->findModel($id);
        // print_r($model);exit;

        // Assuming $model is an instance of \app\modules\admin\models\User

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            // After saving the User model, update associated ParentDetails model
            $parentDetails = $model->parentDetail; // Assuming `parentDetail` is the relation name
            if ($parentDetails !== null) {
                $parentDetails->contact_number = $model->contact_no; // Replace 'new_contact_number' with the actual attribute from the form
                $parentDetails->save(false); // Save without validation
            }

            return $this->redirect(['/admin/users/index-parent']);
        }

        return $this->render('_form_edit.php', [
            'model' => $model,
        ]);
    }





    public function actionKeyPersonUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }


        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                if ($model->status == User::STATUS_ACTIVE) {
                    $update_remaining_users = User::find()->where(['user_role' => User::role_key_person])
                        ->andWhere(['not in', 'id', $model->id])
                        ->andWhere(['campus_id' =>  User::getCampusesByUser(Yii::$app->user->identity->id)])->all();
                    if (!empty($update_remaining_users)) {
                        if ($model->status == User::STATUS_ACTIVE) {
                            foreach ($update_remaining_users as $update_remaining_users_data) {
                                $update_remaining_users_data->status = User::STATUS_INACTIVE;
                                $update_remaining_users_data->save(false);
                            }
                        }
                    }
                }


                return $this->redirect(['key-persons']);
            } else {
                return $this->render('key_person_update', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('key_person_update', [
            'model' => $model,
        ]);
    }





    public function actionView($id)
    {
        $model = User::find()->where(['id' => $id])->one();
        return $this->render('view_old', ['model' => $model]);
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


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = User::find()->where([
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

    public function actionUserData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $subscription_type = $parents[0];
                $out = (new User())->getUserDataBySubscriptionType($subscription_type);
                return $out;
            }
        }

        return $out;
    }

    public function actionAutoLogin($id, $type)
    {
        if ((new User())->shadowLogin($id, $type)) {
            return $this->redirect(['/admin/dashboard']);
        }
    }

    public function actionGoToAdmin($id)
    {
        if ((new User())->backToAdmin($id)) {
            return $this->redirect(['/admin/dashboard']);
        }
    }

    public function actionKeyPersons()
    {
        $searchModel = new UserSearch();

        if (User::isAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::role_key_person]);
        } elseif (User::isInstituteAdmin()) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [USER::role_key_person], '', Yii::$app->user->identity->id);
        } elseif (User::isCampusAdmin()) {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                [USER::role_key_person],
                '',
                Yii::$app->user->identity->id
            );
        }


        return $this->render('key_persons', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetNotifications()
    {

        $campus_id = (new User())->getCampusesByUser(\Yii::$app->user->identity->id);
        $notifications = DashboardNotification::find()->where(['campus_id' => $campus_id])->all();

        return \yii\helpers\Json::encode($notifications);
    }
}
