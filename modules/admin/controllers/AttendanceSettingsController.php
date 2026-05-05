<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\AttendanceSettings;
use app\modules\admin\models\search\AttendanceSettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AttendanceSettingsController implements the CRUD actions for AttendanceSettings model.
 */
class AttendanceSettingsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-attendance-time-tables'],
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
     * Lists all AttendanceSettings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttendanceSettingsSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AttendanceSettings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerAttendanceTimeTables = new \yii\data\ArrayDataProvider([
            'allModels' => $model->attendanceTimeTables,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerAttendanceTimeTables' => $providerAttendanceTimeTables,
        ]);
    }

    /**
     * Creates a new AttendanceSettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AttendanceSettings();

        if ($model->loadAll(Yii::$app->request->post())) {
            $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->campus_id = $campusId;
            $model->saveAll();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AttendanceSettings model.
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
     * Deletes an existing AttendanceSettings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = AttendanceSettings::STATUS_DELETE;
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
            $model = AttendanceSettings::find()->where([
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
     * Finds the AttendanceSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AttendanceSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AttendanceSettings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AttendanceTimeTables
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddAttendanceTimeTables()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AttendanceTimeTables');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAttendanceTimeTables', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
