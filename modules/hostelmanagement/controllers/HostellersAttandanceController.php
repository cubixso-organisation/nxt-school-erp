<?php

namespace app\modules\hostelmanagement\controllers;

use app\components\AuthSettings;
use Yii;
use app\models\User;
use app\modules\admin\models\base\AttendanceSettings;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\modules\hostelmanagement\models\base\HostlerAttendanceSettings;
use app\modules\hostelmanagement\models\HostellersAttandance;
use app\modules\hostelmanagement\models\search\HostellersAttandanceSearch;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HostellersAttandanceController implements the CRUD actions for HostellersAttandance model.
 */
class HostellersAttandanceController extends Controller
{
    const API_OK = 'OK';
    const API_NOK = 'NOK';
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'index-day-wise-attendance', 'status-change', 'generate-today-attendance'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'index-day-wise-attendance', 'status-change', 'generate-today-attendance'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isInstituteAdmin() || User::isChefWarden();
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
     * Lists all HostellersAttandance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HostellersAttandanceSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndexDayWiseAttendance()
    {
        $searchModel = new HostellersAttandanceSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, 'today');
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams,);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, 'today');
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HostellersAttandance model.
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
     * Creates a new HostellersAttandance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HostellersAttandance();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing HostellersAttandance model.
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
     * Deletes an existing HostellersAttandance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = HostellersAttandance::STATUS_DELETE;
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
            $model = HostellersAttandance::find()->where([
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
     * Finds the HostellersAttandance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HostellersAttandance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HostellersAttandance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {

            $hostellerAttandence = HostellersAttandance::find()->where(['id' => $post['id']])->one();
            if (!empty($hostellerAttandence)) {
                $hostellerAttandence->attandance = (int)$post['val'];
                if ($hostellerAttandence->save(false)) {
                    return true;
                } else {
                    return false;
                }
            }else{
                return false;

            }
        }
    }
//     public function actionGenerateTodayAttendance()
// {
//     $data = [];
//     try {
        
//             $campusId = (new User())->getCampusId();

//             if (empty($campusId)) {
//                 $data['status'] = self::API_NOK;
//                 $data['error'] = "Invalid Campus ID.";
//                 return $this->sendJsonResponse($data);
//             }

//             $hostelers = HostellersAttandance::find()
//                 ->where(['campus_id' => $campusId])
//                 ->all();

//             if (empty($hostelers)) {
//                 $data['status'] = self::API_NOK;
//                 $data['error'] = "No hostelers found for the given campus.";
//             } else {
//                 $data['status'] = self::API_OK;
//                 $data['message'] = array_map(function ($hosteler) {
//                     return $hosteler->asJson(); 
//                 }, $hostelers);
//             }
       
//     } catch (Exception $e) {
//         $data['status'] = self::API_NOK;
//         $data['error'] = $e->getMessage();
//     }

//     return $this->sendJsonResponse($data);
// }
// protected function sendJsonResponse($data)
// {
//     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//     return $data;
// }
public function actionGenerateTodayAttendance()
{
    $campusIdByUser = (new User())->getCampusId();
    if (!$campusIdByUser) {
        return 'Campus ID not found!';
    }

    $attendanceSettings = HostlerAttendanceSettings::findOne(['campus_id' => $campusIdByUser]);
    if (!$attendanceSettings || !$attendanceSettings->daily_attendance_count) {
        return 'Attendance settings not configured!';
    }

    $dailyAttendanceCount = $attendanceSettings->daily_attendance_count;
    $students = Hostellers::find()->where(['campus_id' => $campusIdByUser])->all();
    $today = date('Y-m-d');

    foreach ($students as $student) {
        $existingCount = HostellersAttandance::find()
            ->where(['student_id' => $student->student_id, 'campus_id' => $campusIdByUser, 'date' => $today])
            ->count();

        $recordsToCreate = $dailyAttendanceCount - $existingCount;
        for ($i = 0; $i < $recordsToCreate; $i++) {
            $attendance = new HostellersAttandance();
            $attendance->student_id = $student->student_id;
            $attendance->hostel_id = $student->hostel_id;
            $attendance->room_id = $student->room_id;
            $attendance->campus_id = $campusIdByUser;
            $attendance->attandance_by = $campusIdByUser;
            $attendance->attendance_count_perday = $dailyAttendanceCount;
            $attendance->create_user_id = Yii::$app->user->id;
            $attendance->update_user_id = Yii::$app->user->id;
            $attendance->date = $today;
            $attendance->status = HostellersAttandance::STATUS_ACTIVE;
            $attendance->created_on = $today;
            $attendance->updated_on = $today;
            $attendance->attandance = HostellersAttandance::NOT_MARKED;

            if (!$attendance->save()) {
                return json_encode($attendance->errors);
            }
        }
    }

    // return 'Attendance created successfully!';
    Yii::$app->session->setFlash('success', 'Attendance records have been successfully generated.');
    return $this->redirect(['index-day-wise-attendance']);

}




}
