<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\BusRoute;
use app\modules\admin\models\Campus;
use app\modules\admin\models\search\BusRouteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BusRouteController implements the CRUD actions for BusRoute model.
 */
class BusRouteController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-has-bus'],
                        'matchCallback' => function () {
                            return  User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-has-bus'],
                        'matchCallback' => function () {
                            return  User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isCampusAdmin();
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
     * Lists all BusRoute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusRouteSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusRoute model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerStudentHasBus = new \yii\data\ArrayDataProvider([
            'allModels' => $model->studentHasBuses,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerStudentHasBus' => $providerStudentHasBus,
        ]);
    }

    /**
     * Creates a new BusRoute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusRoute();
        $model->scenario = 'create';


        $searchModel = new BusRouteSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id));
        }




        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);

            if ($model->save()) {

                return $this->refresh();
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }


    /**
     * Updates an existing BusRoute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $searchModel = new BusRouteSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        }


        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }



    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = BusRoute::find()->where([
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



    // public function actionDelete($id)
    // {

    //     $model = $this->findModel($id);
    //     if(!empty($model)){
    //         $model->status = BusRoute::STATUS_DELETE;
    //         $model->save(false); 
    //     }

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the BusRoute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentHasBus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentHasBus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentHasBus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formStudentHasBus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
