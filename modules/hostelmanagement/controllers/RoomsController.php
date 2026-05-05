<?php

namespace app\modules\hostelmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\hostelmanagement\models\base\Floor;
use app\modules\hostelmanagement\models\Rooms;
use app\modules\hostelmanagement\models\search\RoomsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;


/**
 * RoomsController implements the CRUD actions for Rooms model.
 */
class RoomsController extends Controller
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
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isChefWarden();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-floor'],
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
     * Lists all Rooms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoomsSearch();
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
     * Displays a single Rooms model.
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
     * Creates a new Rooms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rooms();

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->available_bed = $model->no_of_beds;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Rooms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldNoOfBeds = $model->no_of_beds;
        $occupiedBeds = $oldNoOfBeds - $model->available_bed;

        if ($model->loadAll(Yii::$app->request->post())) {
            // Get the new number of beds
            $newNoOfBeds = $model->no_of_beds;

            // Calculate the difference between old and new number of beds
            $bedsDifference = $newNoOfBeds - $oldNoOfBeds;

            // Calculate the new available beds
            $newAvailableBeds = $newNoOfBeds - $occupiedBeds;

            // Check if new number of available beds is greater than or equal to zero
            if ($newAvailableBeds >= 0) {
                $model->available_bed = $newAvailableBeds;
                if ($model->save(false)) { // Save without validation
                    Yii::$app->session->setFlash('success', 'Model updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save the model.');
                }
            } else {
                // Handle the case where new number of beds is less than occupied beds
                Yii::$app->session->setFlash('error', 'New number of beds cannot be less than occupied beds. Occupied beds: ' . $occupiedBeds);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }




    /**
     * Deletes an existing Rooms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Rooms::STATUS_DELETE;
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
            $model = Rooms::find()->where([
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
     * Finds the Rooms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rooms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rooms::findOne($id)) !== null) {
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
                $hostel_id = $parents[0];
                // var_dump('' . $hostel_id . '');
                // exit;
                $out = (new Floor)->getFloor($hostel_id);
                // var_dump($out);exit;
                // return $out; 
            }
        }
        return  Json::encode($out);
    }
}
