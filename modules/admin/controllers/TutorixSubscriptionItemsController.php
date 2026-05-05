<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\TutorixSubscriptionItems;
use app\modules\admin\models\search\TutorixSubscriptionItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TutorixSubscriptionItemsController implements the CRUD actions for TutorixSubscriptionItems model.
 */
class TutorixSubscriptionItemsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status','expaired-index','pending-index','active-index','free-index','paid-index'],
                        'matchCallback' => function () { 
                            return User::isAdmin() ;

                        }
                       
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status','expaired-index','pending-index','active-index','free-index','paid-index'],
                        'matchCallback' => function () {
                            return User::isAdmin() ;
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
     * Lists all TutorixSubscriptionItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->search($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($totalItemPriceForCurrentPage);exit;
        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }
    
    public function actionExpairedIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->expairysearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($totalItemPriceForCurrentPage);exit;
        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }
    public function actionPendingIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->pendingsearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($totalItemPriceForCurrentPage);exit;
        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }
    public function actionActiveIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->activesearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($totalItemPriceForCurrentPage);exit;
        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }
    public function actionFreeIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->freesearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($totalItemPriceForCurrentPage);exit;
        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }
    public function actionPaidIndex()
    {
        $searchModel = new TutorixSubscriptionItemsSearch();
        $params = Yii::$app->request->queryParams;
    
        // Call the appropriate search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->paidsearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusAdminSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->institutesSearch($params);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            list($dataProvider, $totalItemPriceForCurrentPage) = $searchModel->campusSubAdminSearch($params);
        }
    // var_dump($dataProvider);exit;
    // var_dump($campusName);exit;

        // Render the view with the data provider and total item price
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'campusname' => $campusName,
            'totalItemPriceForCurrentPage' => $totalItemPriceForCurrentPage
        ]);
    }

    /**
     * Displays a single TutorixSubscriptionItems model.
     * 
     * @return mixed
     */
    public function actionView($id)
{
    $model = $this->findModel($id);
    return $this->render('view', [
        'model' => $model,
    ]);
}


    /**
     * Creates a new TutorixSubscriptionItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TutorixSubscriptionItems();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TutorixSubscriptionItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TutorixSubscriptionItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionDelete($id)
    {
      
        $model = $this->findModel($id);
        if(!empty($model)){
            $model->status = TutorixSubscriptionItems::STATUS_DELETE;
            $model->save(false); 
        }

        return $this->redirect(['index']);
    }
    
    public function actionUpdateStatus(){
		$data =[];
		$post = \Yii::$app->request->post();
		\Yii::$app->response->format = 'json';
		if (! empty ( $post ['id'] ) ) {
			$model = TutorixSubscriptionItems::find()->where([
				'id' => $post['id'],
			])->one();
			if(!empty($model)){

                $model->status = $post['val'];
              
               
			}
			if($model->save(false)){
				$data['message'] = "Updated";
                $data['id'] = $model->status ;
			}else{
				$data['message'] = "Not Updated";
                
			}

	}
	return $data;
}

    
    /**
     * Finds the TutorixSubscriptionItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return TutorixSubscriptionItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TutorixSubscriptionItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
