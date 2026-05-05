<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\TeacherDetails;
use app\modules\inventory\models\base\InventoryItems;
use app\modules\inventory\models\base\IssueReturnInventory as BaseIssueReturnInventory;
use app\modules\inventory\models\IssueReturnInventory;
use app\modules\inventory\models\search\IssueReturnInventorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;



/**
 * IssueReturnInventoryController implements the CRUD actions for IssueReturnInventory model.
 */
class IssueReturnInventoryController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-user', 'status-change', 'get-inventory-items'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-user', 'status-change', 'get-inventory-items'],
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
     * Lists all IssueReturnInventory models.
     * @return mixed
     */

  
    public function actionIndex()
    {
        $searchModel = new IssueReturnInventorySearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
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
     * Displays a single IssueReturnInventory model.
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
     * Creates a new IssueReturnInventory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IssueReturnInventory();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->loadAll(Yii::$app->request->post()) && $model->validate() && $model->saveAll()) {
                // Load the related inventory item
                $inventoryItem = $model->inventoryItems;

                // Subtract issued quantity from available_quantity
                $inventoryItem->available_quantity -= $model->quantity;

                // Save the updated inventory item
                $inventoryItem->save();

                // Commit the transaction
                $transaction->commit();

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }



    /**
     * Updates an existing IssueReturnInventory model.
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
     * Deletes an existing IssueReturnInventory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = IssueReturnInventory::STATUS_DELETE;
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
            $model = IssueReturnInventory::find()->where([
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
     * Finds the IssueReturnInventory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IssueReturnInventory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IssueReturnInventory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionGetUser()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $type = $parents[0];
                // var_dump($type);exit;
                $out = (new IssueReturnInventory())->getUser($type);
                // var_dump($out);exit;
                // return $out;
            }
        }
        return  Json::encode($out);
    }
    public function actionStatusChange()
    {
        $postId = \Yii::$app->request->post('id');
        $postVal = \Yii::$app->request->post('val');

        if (empty($postId) || empty($postVal)) {
            return false;
        }

        $transaction = IssueReturnInventory::findOne($postId);

        if (empty($transaction)) {
            return false;
        }

        // Save the old status for comparison
        $oldStatus = $transaction->status;

        // Update the status
        $transaction->status = $postVal;

        // Check if the status is updated to "Returned" (status 2)
        if ($oldStatus != 2 && $transaction->status == 2) {
            // Update return_date
            $transaction->return_date = date('Y-m-d'); // Use your preferred format

            // Save the transaction
            $transaction->save(false);

            // Retrieve the related inventory item
            $inventoryItem = $transaction->inventoryItems;

            // Add the issued quantity back to available_quantity
            $inventoryItem->available_quantity += $transaction->quantity;

            // Save the updated inventory item
            $inventoryItem->save(false);
        } else {
            // Save the transaction for other status changes
            $transaction->save(false);
        }

        return true;
    }
    function actionGetMemberData($item_category_id)
    {
        $data = [];
        $stock = InventoryItems::find()->where(['id' => $item_category_id])->one();
        $data = [
            'item_name' => $stock->id,


        ];
        return json_encode($data);
    }
    public function actionGetInventoryItems()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $type = $parents[0];
                // var_dump($type);exit;
                $out = (new IssueReturnInventory())->getCategory($type);
                // var_dump($out);exit;
                // return $out;
            }
        }
        return  Json::encode($out);
    }
}
