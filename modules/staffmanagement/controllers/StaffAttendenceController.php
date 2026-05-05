<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\AttendanceSettings;
use app\modules\staffmanagement\models\base\StaffAttendenceSettings;
use app\modules\staffmanagement\models\base\StaffDetails;
use app\modules\staffmanagement\models\StaffAttendence;
use app\modules\staffmanagement\models\search\StaffAttendenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * StaffAttendenceController implements the CRUD actions for StaffAttendence model.
 */
class StaffAttendenceController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'today-attandance', 'generate-today-attendance'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin();
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
     * Lists all StaffAttendence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffAttendenceSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }
        $index = "index";
        if (Yii::$app->request->post('hasEditable')) {
            $postId = Yii::$app->request->post('editableKey');
            $model = StaffAttendence::findOne($postId);

            $out = Json::encode(['output' => '', 'message' => '']);
            // $categoryArr = Yii::$app->request->post('Category');
            $post = [];
            $posted = current($_POST['StaffAttendence']);
            $post['StaffAttendence'] = $posted;

            if ($model->load($post)) {

                $output = '';


                // Update Marks_type

                if ($post['StaffAttendence']['attendence'] != Null || $post['StaffAttendence']['attendence'] != "") {
                    $attendence = $post['StaffAttendence']['attendence'];

                    $oldAttendance = $model->attendence;

                    $model->attendence = $attendence;
                    $model->save(false);
                    $out = Json::encode(['output' => strip_tags($model->getStateOptionsBadges()), 'message' => '']);
                }
            }
            return $out;
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'index' => $index
        ]);
    }


    public function actionTodayAttandance()
    {
        $searchModel = new StaffAttendenceSearch();
        $todayDate = date('Y-m-d');
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, $todayDate);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }


        if (Yii::$app->request->post('hasEditable')) {
            $postId = Yii::$app->request->post('editableKey');
            $model = StaffAttendence::findOne($postId);

            $out = Json::encode(['output' => '', 'message' => '']);
            // $categoryArr = Yii::$app->request->post('Category');
            $post = [];
            $posted = current($_POST['StaffAttendence']);
            $post['StaffAttendence'] = $posted;

            if ($model->load($post)) {

                $output = '';


                // Update Marks_type

                if ($post['StaffAttendence']['attendence'] != Null || $post['StaffAttendence']['attendence'] != "") {
                    $attendence = $post['StaffAttendence']['attendence'];

                    $oldAttendance = $model->attendence;

                    $model->attendence = $attendence;
                    $model->save(false);
                    $out = Json::encode(['output' => strip_tags($model->getStateOptionsBadges()), 'message' => '']);
                }
            }
            return $out;
        }

        $index = "";
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    public function actionGenerateTodayAttendance()
    {

        $todayDate = date('Y-m-d');
        $attendanceSetting =  AttendanceSettings::find()->one();
        if (empty($attendanceSetting)) {
            Yii::$app->session->setFlash('error', 'Attendance Settings is not added please add.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $noOfAttendancePerDay = $attendanceSetting->daily_attendance_count;
            for ($i = 1; $i <= (int)$noOfAttendancePerDay; $i++) {
                $staffs  = StaffDetails::find()->joinWith('designation as desg')->where(['staff_details.campus_id' => (new User())->getCampusId()])->andWhere(['AND', ['!=', 'desg.title', User::role_teacher], ['!=', 'desg.title', User::ROLE_WARDEN]])->all();
                foreach ($staffs as $staff) {

                    $staffAttendance = StaffAttendence::find()->where(['date' => $todayDate])->andWhere(['staff_id' => $staff->id])->andWhere(['attendance_count_perday' => (int)$i])->one();
                    if (empty($staffAttendance)) {
                        $staffAttendance = new StaffAttendence();
                        $staffAttendance->campus_id = (new User())->getCampusId();
                        $staffAttendance->staff_id  = $staff->id;
                        $staffAttendance->attendance_count_perday  = $i;
                        $staffAttendance->date  = $todayDate;
                        $staffAttendance->save(false);
                    } else {
                        $staffAttendance->campus_id = (new User())->getCampusId();
                        $staffAttendance->staff_id  = $staff->id;
                        $staffAttendance->attendance_count_perday  = $i;
                        $staffAttendance->date  = $todayDate;
                        $staffAttendance->save(false);
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Todays Attendence is generated');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Displays a single StaffAttendence model.
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
     * Creates a new StaffAttendence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaffAttendence();
        $userIdentity = Yii::$app->user->identity;

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusId($userIdentity->id);
            $model->date = date('Y-m-d');

            $attendence_setting = StaffAttendenceSettings::findOne(['campus_id' => $model->campus_id]);
            if ($attendence_setting !== null) {
                $daily_count = $attendence_setting->daily_attendance_count;
                $check_attendence = StaffAttendence::find()->where(['date' => $model->date])->andWhere(['staff_id' => $model->staff_id])->count();

                if ($check_attendence < $daily_count) {
                    if ($model->save(false)) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Daily attendance limit reached for ' . $model->staff->name);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Attendance settings not found for this campus.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing StaffAttendence model.
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
     * Deletes an existing StaffAttendence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {

    //     $model = $this->findModel($id);
    //     if (!empty($model)) {
    //         $model->status = StaffAttendence::STATUS_DELETE;
    //         $model->save(false);
    //     }

    //     return $this->redirect(['index']);
    // }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = StaffAttendence::find()->where([
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
     * Finds the StaffAttendence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffAttendence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffAttendence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
