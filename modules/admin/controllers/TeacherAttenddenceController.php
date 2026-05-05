<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\TeacherAttenddence;
use app\modules\admin\models\search\TeacherAttenddenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeacherAttenddenceController implements the CRUD actions for TeacherAttenddence model.
 */
class TeacherAttenddenceController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status','get-attendance','index-old'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status','get-attendance','index-old'],
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
     * Lists all TeacherAttenddence models.
     * @return mixed
     */
    public function actionIndex()
{
    $searchModel = new TeacherAttenddenceSearch();

    $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
    $teachers = TeacherDetails::find()->where(['campus_id' => $campus_id])->all();

    
  

    if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
        $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
    } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
        $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
    } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
        $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
    }

    // Fetch the attendance for the selected teacher
   

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'teachers' => $teachers,
        
    ]);
}
public function actionIndexOld()
    {
        $searchModel = new TeacherAttenddenceSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->oldIndexSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->oldIndexSearch(Yii::$app->request->queryParams);
            
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->oldIndexSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->oldIndexSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index_old', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
public function actionGetAttendance()
{
    $selectedTeacherId = Yii::$app->request->get('teacher_id');
    $month = Yii::$app->request->get('month');
    $year = Yii::$app->request->get('year');
    $events = [];

    $userRole = Yii::$app->user->identity->user_role;
    $canUpdate = ($userRole == \app\models\User::ROLE_ADMIN || 
                  $userRole == \app\models\User::ROLE_CAMPUS_ADMIN || 
                  $userRole == \app\models\User::role_campus_sub_admin);

    if ($selectedTeacherId) {
        $attendanceData = TeacherAttenddence::find()
            ->where(['teacher_details_id' => $selectedTeacherId])
            ->andWhere(['YEAR(teacher_present_date_and_time)' => $year])
            ->andWhere(['MONTH(teacher_present_date_and_time)' => $month])
            ->all();

        foreach ($attendanceData as $attendance) {
            $events[] = [
                'id' => $attendance->id,
                'title' => $attendance->status == 1 ? 'Present' : 'Absent',
                'start' => date('Y-m-d', strtotime($attendance->teacher_present_date_and_time)),
                'end' => date('Y-m-d', strtotime($attendance->checkout_date_time)), // Optional
                'color' => $attendance->status == 1 ? 'green' : 'red',
                'date' => $attendance->date,
                'teacher' => $attendance->teacherDetails->name,
                'lat' => $attendance->lat,
                'lng' => $attendance->lng,
                'teacher_present_date_and_time' => $attendance->teacher_present_date_and_time,
                'checkout_date_time' => $attendance->checkout_date_time,
                'canUpdate' => $canUpdate, // Add the flag for frontend usage
            ];
        }
    }

    return $this->asJson($events);
}








    /**
     * Displays a single TeacherAttenddence model.
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
     * Creates a new TeacherAttenddence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeacherAttenddence();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TeacherAttenddence model.
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
     * Deletes an existing TeacherAttenddence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = TeacherAttenddence::STATUS_DELETE;
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
            $model = TeacherAttenddence::find()->where([
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
     * Finds the TeacherAttenddence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TeacherAttenddence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeacherAttenddence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
