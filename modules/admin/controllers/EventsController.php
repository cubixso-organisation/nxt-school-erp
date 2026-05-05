<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\EventClasses;
use app\modules\admin\models\Events;
use app\modules\admin\models\search\EventsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status','get-events'],
                        'matchCallback' => function () { 
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();

                        }
                       
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status','get-events'],
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
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsSearch();


   


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
    public function actionGetEvents()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $events = Events::find()->all(); // Adjust this to fetch only necessary events, add filters if needed
    
        $eventList = [];
        foreach ($events as $event) {
            $eventList[] = [
                'id' => $event->id,
                'image' => $event->image,
                'event_name' => $event->event_name,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'description' => $event->description,
                'venue' => $event->venue,
            ];
        }
        
        return $eventList;
    }
    
    /**
     * Displays a single Events model.
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
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();
        
        
        if ($model->load(Yii::$app->request->post())) {
            // Handle image upload
            $image = \yii\web\UploadedFile::getInstance($model, 'image');
            if (!empty($image)) {
                $imageLogo = Yii::$app->notification->imageKitUpload($image, 'image');
                $model->image = $imageLogo['url'];
            }
        
            // Set campus_id if not passed from form
            if (empty($model->campus_id)) {
                $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            }
        
            // Save event and handle sections if is_global is 2
            if ($model->save()) {
                // If is_global == 2 and sections are selected
                if ($model->is_global == 2 && is_array($model->section) && !empty($model->section)) {
                    foreach ($model->section as $sectionId) {
                        // var_dump($model->section);exit;
                        $eventClass = new EventClasses();
                        $eventClass->event_id = $model->id;
                        $eventClass->campus_id = $model->campus_id;
                        $eventClass->section_id = $sectionId;
                        $eventClass->status = EventClasses::STATUS_ACTIVE;
        
                        // Check if EventClass can be saved
                        if (!$eventClass->save(false)) {
                            Yii::error('Failed to save EventClass: ' . print_r($eventClass->getErrors(), true));
                        } else {
                            Yii::info('Successfully saved EventClass with section ID: ' . $sectionId);
                        }
                    }
                }
        
                Yii::$app->session->setFlash('success', 'Event created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create event.');
            }
        }
        
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    
    

    /**
     * Updates an existing Events model.
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
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      
        $model = $this->findModel($id);
        if(!empty($model)){
            $model->status = Events::STATUS_DELETE;
            $model->save(false); 
        }

        return $this->redirect(['index']);
    }
    
    public function actionUpdateStatus(){
		$data =[];
		$post = \Yii::$app->request->post();
		\Yii::$app->response->format = 'json';
		if (! empty ( $post ['id'] ) ) {
			$model = Events::find()->where([
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
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
