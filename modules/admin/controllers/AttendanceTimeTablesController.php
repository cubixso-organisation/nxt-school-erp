<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\AttendanceSettings;
use app\modules\admin\models\AttendanceTimeTables;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\search\AttendanceTimeTablesSearch;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * AttendanceTimeTablesController implements the CRUD actions for AttendanceTimeTables model.
 */
class AttendanceTimeTablesController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'subject-timetable-data'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'subject-timetable-data'],
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
     * Lists all AttendanceTimeTables models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttendanceTimeTablesSearch();





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


    public function actionSubjectTimetableData()
    {

        $post = Yii::$app->request->post();
        $day_id = !empty($post['depdrop_all_params']['attendancetimetables-day_id']) ? $post['depdrop_all_params']['attendancetimetables-day_id'] : '';
        $class_id = !empty($post['depdrop_all_params']['class-id']) ? $post['depdrop_all_params']['class-id'] : '';
        $section_id = !empty($post['depdrop_all_params']['class-section-id']) ? $post['depdrop_all_params']['class-section-id'] : '';

        $subjectTimetables = SubjectTimetable::find()
            ->where([
                'day_id' => $day_id,
                'class_id' => $class_id,
                'section_id' => $section_id,
            ])
            ->orderBy('id')
            ->asArray()
            ->all();

        foreach ($subjectTimetables as $subjectTimetable) {
            $model = subjectTimetable::find()->where(['id' => $subjectTimetable['id']])->one();
            $day_id = $model['day_id'];
            $class_id = $model['class_id'];
            $section_id  = $model['section_id'];
            $subject_id = $model['subject_id'];
            $time_from = $model['time_from'];
            $time_to = $model['time_to'];

            $getTimeOfDay = strip_tags(SubjectTimetable::getTimeOfDay($time_from));
            $subjects = Subjects::find()->where(['id' => $subject_id])->one();
            $student_class = StudentClass::find()->where(['id' => $class_id])->one();
            $class_sections = ClassSections::find()->where(['id' => $section_id])->one();
            $time_table_details  = $subjects->subject_name . ' ' . $student_class->title . ' ' . $class_sections->section_name . ' ' . $day_id . ' ' . $time_from . '-' . $time_to . ' ' . $getTimeOfDay;




            $response['output'][] = ['id' => $subjectTimetable['id'], 'name' => $time_table_details];
        }

        return Json::encode($response);
    }












    /**
     * Displays a single AttendanceTimeTables model.
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
     * Creates a new AttendanceTimeTables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AttendanceTimeTables();

        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $attendance_settings_id = $post['AttendanceTimeTables']['attendance_settings_id'];
            $subject_timetable_id = $post['AttendanceTimeTables']['subject_timetable_id'];

            $attendance_settings = AttendanceSettings::find()->where(['id' => $attendance_settings_id])->one();
            $daily_attendance_count = $attendance_settings->daily_attendance_count;
            $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();

            if (!empty($subject_timetable)) {
                $class_id  = $subject_timetable->class_id;
                $section_id   = $subject_timetable->section_id;
                $day_id   = $subject_timetable->day_id;
            } else {
                $class_id  = '';
                $section_id   = '';
                $day_id   = '';
            }

            $attendance_time_tables = AttendanceTimeTables::find()->innerJoinWith('subjectTimetable as sjt')
                ->where(['sjt.class_id' => $class_id])
                ->andWhere(['sjt.section_id' => $section_id])
                ->andWhere(['sjt.campus_id' => $campus_id])
                ->andWhere(['sjt.day_id' => $day_id])
                ->count();
            if ($attendance_time_tables < $daily_attendance_count) {

                $attendance_time_tables = AttendanceTimeTables::find()->innerJoinWith('subjectTimetable as sjt')
                    ->where(['subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['sjt.campus_id' => $campus_id])
                    ->andWhere(['sjt.day_id' => $day_id])
                    ->one();
                if (empty($attendance_time_tables)) {
                    $model->saveAll();
                    return $this->redirect(['index']);
                } else {
                    $model->addError('subject_timetable_id', "Time table already already exist");
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                $model->addError('subject_timetable_id', "daily attendance limit exists with this class day attendance count $daily_attendance_count");

                return $this->render('create', [
                    'model' => $model,
                ]);
            };
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AttendanceTimeTables model.
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
     * Deletes an existing AttendanceTimeTables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = AttendanceTimeTables::STATUS_DELETE;
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
            $model = AttendanceTimeTables::find()->where([
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
     * Finds the AttendanceTimeTables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AttendanceTimeTables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AttendanceTimeTables::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
