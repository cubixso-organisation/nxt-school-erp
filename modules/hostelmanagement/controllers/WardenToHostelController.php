<?php

namespace app\modules\hostelmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\hostelmanagement\models\base\Floor;
use app\modules\hostelmanagement\models\WardenToHostel;
use app\modules\hostelmanagement\models\search\WardenToHostelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;


/**
 * WardenToHostelController implements the CRUD actions for WardenToHostel model.
 */
class WardenToHostelController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-floor'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isChefWarden();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-floor'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isChefWarden();
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
     * Lists all WardenToHostel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WardenToHostelSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            $dataProvider = $searchModel->campusChiefWardenSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WardenToHostel model.
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
     * Creates a new WardenToHostel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WardenToHostel();

        if ($model->loadAll(Yii::$app->request->post())) {
            // Get the array of floor_id values from the form
            $floorIds = Yii::$app->request->post('WardenToHostel')['floor_id'];
            $check =  WardenToHostel::find()->where(['warden_id' => $model->warden_id])->andWhere(['status' => WardenToHostel::STATUS_ACTIVE])->one();
            if (!empty($check)) {
                if ($check->hostel_id != $model->hostel_id) {
                    Yii::$app->session->setFlash('error', "The warden is already assigned to other floors within the " . $check->hostel->name ?? "" . " hostel. Kindly choose floors specific to the " . $check->hostel->name ?? "" . " hostel.");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            // Iterate through each floor_id and save the model
            $allFloorsAssigned = true; // Assume all floors are assigned initially
            foreach ($floorIds as $floorId) {
                $checkAlreadyAssigned =  WardenToHostel::find()->where(['warden_id' => $model->warden_id])->andWhere(['floor_id' => $floorId])->one();
                if (empty($checkAlreadyAssigned)) {
                    $allFloorsAssigned = false; // At least one floor is not assigned
                    $newModel = new WardenToHostel();
                    $newModel->attributes = $model->attributes;
                    $newModel->floor_id = $floorId;

                    // You may need to set other attributes as needed

                    if ($newModel->save()) {
                        // Model saved successfully
                    } else {
                        // Handle the case when saving fails
                        Yii::$app->session->setFlash('error', 'Error saving model.');
                        return $this->refresh(); // You can redirect or render the form again based on your needs
                    }
                }
            }

            if ($allFloorsAssigned) {
                Yii::$app->session->setFlash('error', 'All selected floors are already assigned to the warden.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->redirect(['index']); // Redirect to the index page or any other page as needed
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing WardenToHostel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $check =  WardenToHostel::find()->where(['warden_id' => $model->warden_id])->andWhere(['status' => WardenToHostel::STATUS_ACTIVE])->one();
            if (!empty($check)) {
                if ($check->hostel_id != $model->hostel_id) {
                    Yii::$app->session->setFlash('error', "The warden is already assigned to other floors within the " . $check->hostel->name . " hostel. Kindly choose floors specific to the" . $check->hostel->name . " hostel.");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WardenToHostel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = WardenToHostel::STATUS_DELETE;
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
            $model = WardenToHostel::find()->where([
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
     * Finds the WardenToHostel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WardenToHostel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WardenToHostel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionGetFloor()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $floor = $parents[0];
                // var_dump($floor);exit;
                $out = (new Floor())->getFloorData($floor);
                // var_dump($out);exit;
                // return $out;
            }
        }
        return  Json::encode($out);
    }
}
