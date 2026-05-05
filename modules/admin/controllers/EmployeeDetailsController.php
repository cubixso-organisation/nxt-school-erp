<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\Auth;
use app\modules\admin\models\Campus;
use app\modules\admin\models\CampusHasUsers;
use app\modules\admin\models\Designation;
use app\modules\admin\models\DriverHasBus;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\search\EmployeeDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeeDetailsController implements the CRUD actions for EmployeeDetails model.
 */
class EmployeeDetailsController extends Controller
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
                            'index', 'view', 'create', 'update', 'delete', 'update-status',
                            'add-bus-status', 'add-driver-has-bus', 'employ-designation-data'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'view', 'create', 'update', 'delete', 'update-status',
                            'add-bus-status', 'add-driver-has-bus', 'employ-designation-data'
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
     * Lists all EmployeeDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeeDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $this->findModel($id),

        ]);
    }

    /**
     * Creates a new EmployeeDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeDetails();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            // echo "<pre>";
            // print_r($post);
            // exit;
            $createUser = new User();
            $employ_name = $post['EmployeeDetails']['employ_name'];
            $phone_number = $post['EmployeeDetails']['phone_number'];
            $user_role = $post['EmployeeDetails']['role'];
            $employee_id = $post['EmployeeDetails']['employee_id'];
            $age = $post['EmployeeDetails']['age'];
            $gender = $post['EmployeeDetails']['gender'];
            $blood_group_id = $post['EmployeeDetails']['blood_group_id'];
            $phone_number = $post['EmployeeDetails']['phone_number'];
            $email = $post['EmployeeDetails']['email'];
            $createUser->username = $phone_number . '@' . $user_role . '.com';
            $createUser->first_name = $employ_name;
            $createUser->contact_no = $phone_number;
            $createUser->user_role = $user_role;
            if ($createUser->save(false)) {
                $providerId = $createUser->user_role;
                $auth_id = $phone_number;
                $auth = new Auth();
                $auth->user_id = $createUser->id;
                $auth->source = $providerId;
                $auth->source_id = $auth_id;
                $auth->save(false);

                $model->user_id  = $createUser->id;
                $model->employ_name  = $employ_name;
                $model->phone_number  = $phone_number;
                $model->employee_id  = $employee_id;
                $model->age  = $age;
                $model->gender  = $gender;
                $model->blood_group_id  = $blood_group_id;
                $model->email  = $email;


                if ($model->save(false)) {
                    if ($createUser->user_role == User::ROLE_BUS_DRIVER) {
                        $bus = new DriverHasBus();
                        $bus->campus_id   = $post['EmployeeDetails']['campus_id'];
                        $bus->driver_id    = $createUser->id;
                        $bus->bus_id     = $post['EmployeeDetails']['bus_id'];
                        $bus->status     = DriverHasBus::STATUS_ACTIVE;
                        $bus->save(false);
                    }

                    //create campus access to user

                    $campus_has_users = new CampusHasUsers();
                    $campus_has_users->campus_id  = $post['EmployeeDetails']['campus_id'];
                    $campus_has_users->user_id = $model->user_id;
                    $campus_has_users->status = CampusHasUsers::STATUS_ACTIVE;
                    $campus_has_users->save(false);
                }
            }



            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EmployeeDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $user_role = $post['EmployeeDetails']['role'];
            $model->save();

            $findAuth = Auth::find()->where(['user_id' => $model->user_id])->one();
            if (!empty($findAuth)) {
                $findAuth->source = $model->phone_number;
                $findAuth->source_id = $model->user_id;
                $findAuth->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EmployeeDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = EmployeeDetails::STATUS_DELETE;
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
            $model = EmployeeDetails::find()->where([
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
     * Finds the EmployeeDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmployeeDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmployeeDetails::findOne($id)) !== null) {
            return $model;
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

    public function actionEmployDesignationData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $campus_id = $parents[0];
                $out = (new Designation())->getEmployDesignationData($campus_id);
                return $out;
            }
        }

        return $out;
    }
}
