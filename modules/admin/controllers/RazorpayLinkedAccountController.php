<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\RazorpayLinkedAccount;
use app\modules\admin\models\search\RazorpayLinkedAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RazorpayLinkedAccountController implements the CRUD actions for RazorpayLinkedAccount model.
 */
class RazorpayLinkedAccountController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-update-account-details', 'get-cities'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
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
     * Lists all RazorpayLinkedAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RazorpayLinkedAccountSearch();

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
     * Displays a single RazorpayLinkedAccount model.
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
     * Creates a new RazorpayLinkedAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $accountExists = RazorpayLinkedAccount::find()->where(['campus_id' => (new User())->getCampusId()])->one();
        if (empty($accountExists)) {
            $model = new RazorpayLinkedAccount();
        } else {
            $model = $accountExists;
        }
        $jsonPath = Yii::getAlias('@webroot/stateandcity.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);


        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'jsonData' => $jsonData,
            ]);
        }
    }


    
    public function actionGetCities($state)
    {
        Yii::info("Received state: " . $state, __METHOD__);
        $jsonPath = Yii::getAlias('@webroot/stateandcity.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        // var_dump($jsonData);
        // exit;

        // Debugging: Log the received state
        // var_dump("Received state: " . $state, __METHOD__);exit;;

        // Ensure the state exists in the JSON data
        $cities = isset($jsonData[$state]) ? $jsonData[$state] : [];
        return json_encode($cities);
    }
    public function actionAddUpdateAccountDetails()
    {
        $accountExists = RazorpayLinkedAccount::find()->where(['campus_id' => (new User())->getCampusId()])->one();
        $post = Yii::$app->request->post();



        if (empty($accountExists)) {
            $accountExists = new RazorpayLinkedAccount();
        }

        $accountExists->campus_id = (new User())->getCampusId();
        $accountExists->status = $post['status'];
        if ($accountExists->save(false)) {
            $data['status'] = "ok";
            $data['message'] = "Account updated Successfully";
        } else {
            $data['status'] = "nok";
            $data['message'] = "Something went wrong";
        }

        return json_encode($data);
    }

    /**
     * Updates an existing RazorpayLinkedAccount model.
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
     * Deletes an existing RazorpayLinkedAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = RazorpayLinkedAccount::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (! empty($post['id'])) {
            $model = RazorpayLinkedAccount::find()->where([
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
     * Finds the RazorpayLinkedAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RazorpayLinkedAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RazorpayLinkedAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
