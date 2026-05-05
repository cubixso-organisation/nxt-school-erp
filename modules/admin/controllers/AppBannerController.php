<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\AppBanner;
use app\modules\admin\models\search\AppBannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppBannerController implements the CRUD actions for AppBanner model.
 */
class AppBannerController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status'],
                        'matchCallback' => function () { 
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();

                        }
                       
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin()||User::isCampusSubAdmin();
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
     * Lists all AppBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppBannerSearch();


   


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
     * Displays a single AppBanner model.
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
     * Creates a new AppBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AppBanner();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            try {
                // Handle school_logo upload
                $image = \yii\web\UploadedFile::getInstance($model, 'image');
                if (!empty($image)) {
                    $imageLogo = Yii::$app->notification->imageKitUpload($image, 'school_logo');
                    $model->image = $imageLogo['url'];
                }

                // Save model without validation
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Banner created succesfully');

                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save the Banner.');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'An error occurred while uploading the files or saving the template: ' . $e->getMessage());
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppBanner model.
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
     * Deletes an existing AppBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      
        $model = $this->findModel($id);
        if(!empty($model)){
            $model->status = AppBanner::STATUS_DELETE;
            $model->save(false); 
        }

        return $this->redirect(['index']);
    }
    
    public function actionUpdateStatus(){
		$data =[];
		$post = \Yii::$app->request->post();
		\Yii::$app->response->format = 'json';
		if (! empty ( $post ['id'] ) ) {
			$model = AppBanner::find()->where([
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
     * Finds the AppBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
