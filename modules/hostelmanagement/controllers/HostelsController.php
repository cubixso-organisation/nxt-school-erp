<?php

namespace app\modules\hostelmanagement\controllers;

use app\components\Toast;
use app\modules\admin\models\Auth;
use Yii;
use app\models\User;
use app\modules\hostelmanagement\models\Hostels;
use app\modules\hostelmanagement\models\search\HostelsSearch;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * HostelsController implements the CRUD actions for Hostels model.
 */
class HostelsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'create-warden', 'index-warden', 'get-rooms', 'warden-list', 'warden-view', 'create-chief-warden'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isChefWarden();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAccountant() || User::isDocumentationDepartment()  || User::isLmLeadsManagement()  || User::isDeliveryAdmin() || User::isChefWarden();
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
     * Lists all Hostels models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HostelsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->SubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            $dataProvider = $searchModel->ChiefWardenSearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hostels model.
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


    public function actionWardenView($id)
    {
        $model = User::find()->where(['id' => $id])->one();
        return $this->render('@app/modules/admin/views/users/view_old', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Hostels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hostels();
        $post = \Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {

            $upload_image = \yii\web\UploadedFile::getInstance($model, 'image_file');
            $mess_menu = \yii\web\UploadedFile::getInstance($model, 'mess_menu');
            // var_dump($upload_image);exit;
            $image = Yii::$app->notification->imageKitUpload($upload_image);
            $mess_menu_image = Yii::$app->notification->imageKitUpload($mess_menu);

            // var_dump($image['url']);
            // exit;
            //Add servicetype by service id

            $model->campus_id = User::getCampusId();
            // $model->warden_id = (int)$post["Hostels"]["warden_id"];

            $model->mess_menu = $mess_menu_image['url'];
            $model->image_file = $image['url'];
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Hostels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldImage = $model->image_file;

        $oldMessImage = $model->mess_menu;
        $upload_image = \yii\web\UploadedFile::getInstance($model, 'image_file');
        $mess_image = \yii\web\UploadedFile::getInstance($model, 'mess_menu');


        if ($model->loadAll(\Yii::$app->request->post())) {


            if (!empty($upload_image)) {
                $image = Yii::$app->notification->imageKitUpload($upload_image);
                $model->image_file = $image['url'];
            } else {
                $model->image_file = $oldImage;
            }


            if (!empty($mess_image)) {

                $image = Yii::$app->notification->imageKitUpload($mess_image);
                $model->mess_menu = $image['url'];
            } else {
                $model->mess_menu = $oldMessImage;
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
     * Deletes an existing Hostels model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Hostels::STATUS_DELETE;
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
            $model = Hostels::find()->where([
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
     * Finds the Hostels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hostels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hostels::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionCreateWarden()
    {

        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            // $upload_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
            // $image = Yii::$app->notification->imageKitUpload($upload_image);
            $existingUser = User::findOne(['username' => $model->contact_no . '@' . $model->user_role . '.com']);
            if ($existingUser) {
                Yii::$app->session->setFlash('error', 'Username already exists. Choose a different username.');
                return $this->render(
                    'create-warden',
                    [
                        'model' => $model,
                    ]
                );
            }
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->contact_no);

            $model->username = $model->contact_no . '@' . $model->user_role . '.com';
            $model->campus_id = User::getCampusId();

            if ($model->save(false)) {
                $auth = new Auth();
                $auth->user_id = $model->id;
                $auth->source = 'Warden';
                $auth->source_id = $model->contact_no;
                $auth->save(false);
            }
            return $this->redirect(['warden-list', 'id' => $model->id]);
        } else {
            return $this->render('create-warden', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndexWarden()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::ROLE_WARDEN || User::ROLE_CHEF_WARDEN);

        return $this->render('index_warden', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionWardenList()
    {
        Yii::$app->user->identity->id;

        $searchModel = new UserSearch();

        $dataProvider = $searchModel->warden(Yii::$app->request->queryParams, USER::ROLE_WARDEN);

        return $this->render('warden_index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreateChiefWarden()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusId();
            $existingUser = User::findOne(['username' => $model->contact_no . '@' . $model->user_role . '.com']);
            if ($existingUser) {
                Yii::$app->session->setFlash('error', 'Username already exists. Choose a different username.');
                return $this->render(
                    'create-warden',
                    [
                        'model' => $model,
                    ]
                );
            }
            $model->username = $model->contact_no . '@' . $model->user_role . '.com';
            $model->password_hash = Yii::$app->security->generatePasswordHash($model['contact_no']);
            if ($model->save(false)) {

                $auth = new Auth();
                $auth->user_id = $model->id;
                $auth->source = "chiefwarden";
                $auth->source_id = $model['contact_no'];
                $auth->save(false);
                Toast::success('Chief Warden Created Successfully');
                return $this->redirect(['warden-list', 'id' => $model->id]);
            } else {
                Toast::error('Chief Warden Created Successfully');
            }
        }

        return $this->render('create_chief_warden', [
            'model' => $model,
        ]);
    }
}
