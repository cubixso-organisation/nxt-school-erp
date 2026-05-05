<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\ClassRooms;
use app\modules\admin\models\base\StudentClassAttendance;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\search\SubjectTimetableSearch;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\SubjectGroups;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\TeacherDetails;
use Exception;
use Google\Service\AdExchangeBuyerII\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

/**
 * SubjectTimetableController implements the CRUD actions for SubjectTimetable model.
 */
class SubjectTimetableController extends Controller
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
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'add-student-class-attendance',
                            'search-teacher-time-table',
                            'get-subjects-by-group',
                            'teacher-time-table',
                            'get-time-table-by-teachers',
                            'subject-groups',
                            'add-or-update-time-table',
                            'get-academic-year-id',
                            'reset-session-data',
                            'subject-time-table-delete',
                            'import'

                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'add-student-class-attendance',
                            'search-teacher-time-table',
                            'teacher-time-table',
                            'get-subjects-by-group',
                            'get-time-table-by-teachers',
                            'subject-groups',
                            'add-or-update-time-table',
                            'get-academic-year-id',
                            'reset-session-data',
                            'subject-time-table-delete',
                            'import'
                        ],
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
     * Lists all SubjectTimetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubjectTimetableSearch();

        $model = new SubjectTimetable();
        $SubjectTimetable = [new SubjectTimetable];



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
            'model' => $model,
            'SubjectTimetable' => $SubjectTimetable

        ]);
    }
    public function actionTeacherTimeTable($teacher_details_id = '')
    {
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        $model = new SubjectTimetable();
        $teacherTimeTable = SubjectTimetable::find()->Where(['teacher_details_id' => $teacher_details_id])->andwhere(['campus_id' => $campuses])->all();


        return $this->render('index_teacher_time_table', [
            'teacherTimeTable' => $teacherTimeTable,
            'model' => $model
        ]);
    }
    /**
     * Displays a single SubjectTimetable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerStudentClassAttendance = new \yii\data\ArrayDataProvider([
            'allModels' => $model->studentClassAttendances,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerStudentClassAttendance' => $providerStudentClassAttendance,
        ]);
    }

    /**
     * Creates a new SubjectTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubjectTimetable();





        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SubjectTimetable model.
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
     * Deletes an existing SubjectTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = SubjectTimetable::STATUS_DELETE;
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
            $model = SubjectTimetable::find()->where([
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
     * Finds the SubjectTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubjectTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubjectTimetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentClassAttendance
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentClassAttendance()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentClassAttendance');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formStudentClassAttendance', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }



    public function actionGetSubjectsByGroup()
    {
        $post = \Yii::$app->request->post();
        $section_id = $post['SubjectTimetable']['section_id'];
        $class_id = $post['SubjectTimetable']['class_id'];
        $subject_group_subject_id = $post['SubjectTimetable']['subject_group_subject_id'];
        $session = Yii::$app->session;
        $user_id = Yii::$app->user->identity->id;

        $session->set('section_id', $section_id);

        $session->set('class_id', $class_id);
        $session->set('subject_group_subject_id', $subject_group_subject_id);
        return json_encode(array('status' => 'ok'));
    }

    public function actionGetTimeTableByTeachers()
    {
        $post = \Yii::$app->request->post();
        $day_id = $post['SubjectTimetable']['day_id'];
        $teacher_details_id = $post['SubjectTimetable']['teacher_details_id'];
        // $subject_group_subject_id = $post['SubjectTimetable']['subject_group_subject_id'];
        $session = Yii::$app->session;
        $user_id = Yii::$app->user->identity->id;

        // $session->set('section_id', $section_id);

        $session->set('class_id', $class_id);
        $session->set('subject_group_subject_id', $subject_group_subject_id);
        return json_encode(array('status' => 'ok'));
    }

    public function actionGetAcademicYearId()
    {

        $post = \Yii::$app->request->post();
        // var_dump($post);
        // exit;
        $academic_year_id = $post['SubjectTimetable']['academic_year_id'];

        if (!empty($academic_year_id)) {
            $session = Yii::$app->session;
            $session->set('academic_year_id', $academic_year_id);
            // var_dump($session->get('academic_year_id'));
            // exit;
            return json_encode(array('status' => 'ok'));
        } else {
            return json_encode(array('status' => 'nok'));
        }
    }


    public function actionResetSessionData()
    {

        $session = Yii::$app->session;

        $session->remove('academic_year_id');
        $session->remove('section_id');
        $session->remove('class_id');
        $session->remove('academic_year_id');
        $session->remove('subject_group_subject_id');

        return json_encode(array('status' => 'ok'));
    }


    public static function getSubjectGroupData($section_id)
    {
        if (empty($section_id)) {
            Yii::error("Empty section_id passed to getSubjectGroupData", __METHOD__);
            return ['output' => []];
        }

        $academic_year_id = '';
        $session = Yii::$app->session;


        if ($session->has('academic_year_id')) {
            $academic_year_id = trim($session->get('academic_year_id'));
        }

        Yii::error("getSubjectGroupData called with section_id: " . $section_id, __METHOD__);
        Yii::error("academic_year_id: " . $academic_year_id, __METHOD__);

        $out = [];

        // Get unique subject_group_id values for this section
        $subject_group_id = SubjectGroupsClassSections::find()
            ->select('subject_group_id')
            ->where(['class_sections_id' => $section_id])
            ->distinct()
            ->column();
        Yii::error("subject_group_id array: " . json_encode($subject_group_id), __METHOD__);
        // var_dump($subject_group_id);
        // exit;
        if (!empty($subject_group_id)) {
            $data = SubjectGroups::find()
                ->where(['in', 'id', $subject_group_id])
                ->andWhere(['academic_year_id' => $academic_year_id])->all();

            // var_dump($data->createCommand()->getRawSql());
            // exit;
            Yii::error("SubjectGroups data: " . json_encode($data), __METHOD__);

            if (!empty($data)) {
                foreach ($data as $dat) {
                    Yii::error("SubjectGroup row: " . json_encode($dat->attributes), __METHOD__);
                    $out[] = ['id' => $dat->id, 'name' => $dat->subject_group_name];
                }
            } else {
                Yii::error("No SubjectGroups found for ids: " . json_encode($subject_group_id) . " and academic_year_id: " . $academic_year_id, __METHOD__);
            }
        } else {
            Yii::error("No subject_group_id found for section_id: " . $section_id, __METHOD__);
        }

        Yii::error("Returning output: " . json_encode($out), __METHOD__);
        return [
            'output' => $out
        ];
    }




    public function actionSubjectGroups()
    {



        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $section_id = $parents[0];
                $out = self::getSubjectGroupData($section_id);
                return $out;
            }
        }

        return $out;
    }
    public function actionAddOrUpdateTimeTable()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        $session = Yii::$app->session;
        $section_id = '';
        $class_id = '';
        $subject_group_subject_id = '';
        $update_error = [];
        $insert_error = [];

        $error = true;
        if ($session->has('section_id')) {
            $section_id =  $session->get('section_id');
        }

        if ($session->has('class_id')) {
            $class_id =  $session->get('class_id');
        }

        if ($session->has('subject_group_subject_id')) {
            $subject_group_subject_id =  trim($session->get('subject_group_subject_id'));
        }


        if ($session->has('academic_year_id')) {
            $academic_year_id =  trim($session->get('academic_year_id'));
        }

        try {
            $subject_id = $post['SubjectTimetable']['subject_id'];
            $teacher_details_id = $post['SubjectTimetable']['teacher_details_id'];
            $time_from = $post['SubjectTimetable']['time_from'];
            $time_to = $post['SubjectTimetable']['time_to'];
            $day_id = $post['SubjectTimetable']['day_id'];
            $room_id   = $post['SubjectTimetable']['room_id'];
            $period   = $post['SubjectTimetable']['period'];
            $subject_timetable_id   = $post['SubjectTimetable']['subject_timetable_id'];
            if (!empty($subject_id)) {
                $count = count($subject_id);
                for ($i = 0; $i < $count; $i++) {
                    $merge_form_data[] = [
                        'subject_id' => $subject_id[$i],
                        'teacher_details_id' => $teacher_details_id[$i],
                        'time_from' => $time_from[$i],
                        'time_to' => $time_to[$i],
                        'room_id' => $room_id[$i],
                        'period' => $period[$i],
                        'subject_timetable_id' => $subject_timetable_id[$i]

                    ];
                }


                if (!empty($merge_form_data)) {
                    foreach ($merge_form_data as $merge_form_data_data) {

                        $time_from_timestamp = strtotime($merge_form_data_data['time_from']);
                        $time_to_timestamp = strtotime($merge_form_data_data['time_to']);

                        // if ($time_from_timestamp < $time_to_timestamp) {


                        $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $class_id  = $class_id;
                        $section_id   = $section_id;
                        $subject_group_id   = $subject_group_subject_id;
                        if (!empty($merge_form_data_data['subject_timetable_id'])) {

                            $subject_timetable_update = SubjectTimetable::find()->where(['id' => $merge_form_data_data['subject_timetable_id']])->one();
                            //update all data with out teacher and time and class room change
                            $subject_timetable_update_check = SubjectTimetable::find()->where(['id' => $subject_timetable_update->id])
                                ->andWhere(['day_id' => $day_id])
                                ->andWhere(['campus_id' => $campus_id])
                                ->andWhere(['academic_year_id' => $academic_year_id])
                                ->andWhere(['class_id' => $class_id])
                                ->andWhere(['section_id' => $section_id])
                                ->andWhere(['time_from' => $merge_form_data_data['time_from']])
                                ->andWhere(['time_to' => $merge_form_data_data['time_to']])
                                ->andWHere(['room_id' => $merge_form_data_data['room_id']])
                                ->andWHere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                                ->one();
                            if (!empty($subject_timetable_update_check)) {
                                $subject_timetable_update_check->subject_id  = $merge_form_data_data['subject_id'];
                                $subject_timetable_update_check->campus_id  = $campus_id;
                                $subject_timetable_update_check->day_id  = $day_id;
                                $subject_timetable_update_check->class_id   = $class_id;
                                $subject_timetable_update_check->section_id   = $section_id;
                                $subject_timetable_update_check->subject_id  = $merge_form_data_data['subject_id'];
                                $subject_timetable_update_check->teacher_details_id  = $merge_form_data_data['teacher_details_id'];
                                $subject_timetable_update_check->time_from  = $merge_form_data_data['time_from'];
                                $subject_timetable_update_check->time_to  = $merge_form_data_data['time_to'];
                                $subject_timetable_update_check->room_id  = $merge_form_data_data['room_id'];
                                $subject_timetable_update_check->period  = $merge_form_data_data['period'];
                                $subject_timetable_update_check->academic_year_id   = $academic_year_id;
                                $subject_timetable_update_check->status   = SubjectTimetable::STATUS_ACTIVE;
                                $subject_timetable_update_check->save(false);
                                $subject_timetable_update_check->save(false);
                                $error = false;
                            } else {
                                $subject_timetable_with_time_from_update = SubjectTimetable::find()
                                    ->where(['campus_id' => $campus_id])
                                    ->andWhere(['day_id' => $day_id])
                                    ->andWhere(['class_id' => $class_id])
                                    ->andWhere(['section_id' => $section_id])
                                    ->andWhere(['academic_year_id' => $academic_year_id])
                                    ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                    ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                    ->all();

                                $subject_timetable_with_time_to_update = SubjectTimetable::find()
                                    ->where(['campus_id' => $campus_id])
                                    ->andWhere(['day_id' => $day_id])
                                    ->andWhere(['class_id' => $class_id])
                                    ->andWhere(['section_id' => $section_id])
                                    ->andWhere(['academic_year_id' => $academic_year_id])
                                    ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                    ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                    ->all();
                                if (!empty($subject_timetable_with_time_from_update) || !empty($subject_timetable_with_time_to_update)) {
                                    $time_check = false;

                                    if (!empty($subject_timetable_with_time_from_update)) {
                                        foreach ($subject_timetable_with_time_from_update as $subject_timetable_with_time_from_update_data) {
                                            $update_error[$subject_timetable_with_time_from_update_data->id] =   SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_update_data', $subject_timetable_with_time_from_update_data);
                                        }
                                    }

                                    if (!empty($subject_timetable_with_time_to_update)) {
                                        foreach ($subject_timetable_with_time_to_update as $subject_timetable_with_time_to_update_data) {
                                            $update_error[$subject_timetable_with_time_to_update_data->id] =    SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_update_data', $subject_timetable_with_time_to_update_data);
                                        }
                                    }
                                } else {
                                    $time_check = true;
                                }

                                if ($time_check === true) {

                                    $subject_timetable_with_time_from_teacher_update = SubjectTimetable::find()
                                        ->where(['campus_id' => $campus_id])
                                        ->andWhere(['day_id' => $day_id])
                                        ->andWhere(['academic_year_id' => $academic_year_id])
                                        ->andWHere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                                        ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                        ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                        ->all();

                                    $subject_timetable_with_time_to_teacher_update = SubjectTimetable::find()
                                        ->where(['campus_id' => $campus_id])
                                        ->andWhere(['day_id' => $day_id])
                                        ->andWhere(['academic_year_id' => $academic_year_id])
                                        ->andWHere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                                        ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                        ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                        ->all();
                                    // if (!empty($subject_timetable_with_time_from_teacher_update) || !empty($subject_timetable_with_time_to_teacher_update)) {
                                    //     $teacher_time_check = false;
                                    //     if (!empty($subject_timetable_with_time_from_teacher_update)) {
                                    //         foreach ($subject_timetable_with_time_from_teacher_update as $subject_timetable_with_time_from_teacher_update_data) {
                                    //             $update_error[$subject_timetable_with_time_from_teacher_update_data->id] =   SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_teacher_update_data', $subject_timetable_with_time_from_teacher_update_data);
                                    //         }
                                    //     }


                                    //     if (!empty($subject_timetable_with_time_to_teacher_update)) {
                                    //         foreach ($subject_timetable_with_time_to_teacher_update as $subject_timetable_with_time_to_teacher_update_data) {
                                    //             $update_error[$subject_timetable_with_time_to_teacher_update_data->id] =    SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_teacher_update_data', $subject_timetable_with_time_to_teacher_update_data);
                                    //         }
                                    //     }
                                    // } else {
                                    //     $teacher_time_check = true;
                                    // }
                                    $teacher_time_check = true;
                                    if ($teacher_time_check === true) {
                                        //class rooms check 

                                        $subject_timetable_with_time_from_room_update = SubjectTimetable::find()
                                            ->where(['campus_id' => $campus_id])
                                            ->andWhere(['day_id' => $day_id])
                                            ->andWhere(['academic_year_id' => $academic_year_id])
                                            ->andWHere(['room_id' => $merge_form_data_data['room_id']])
                                            ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                            ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                            ->all();
                                        $subject_timetable_with_time_to_room_update = SubjectTimetable::find()
                                            ->where(['campus_id' => $campus_id])
                                            ->andWhere(['day_id' => $day_id])
                                            ->andWhere(['academic_year_id' => $academic_year_id])
                                            ->andWHere(['room_id' => $merge_form_data_data['room_id']])
                                            ->andWhere(['not in', 'id', [$merge_form_data_data['subject_timetable_id']]])
                                            ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                            ->all();


                                        // if (!empty($subject_timetable_with_time_from_room_update) || !empty($subject_timetable_with_time_to_room_update)) {
                                        //     $room_time_check = false;
                                        //     if (!empty($subject_timetable_with_time_from_room_update)) {
                                        //         foreach ($subject_timetable_with_time_from_room_update as $subject_timetable_with_time_from_room_update_data) {
                                        //             $update_error[$subject_timetable_with_time_from_room_update_data->id] =   SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_room_update_data', $subject_timetable_with_time_from_room_update_data);
                                        //         }
                                        //     }


                                        //     if (!empty($subject_timetable_with_time_to_room_update)) {
                                        //         foreach ($subject_timetable_with_time_to_room_update as $subject_timetable_with_time_to_room_update_data) {
                                        //             $update_error[$subject_timetable_with_time_to_room_update_data->id] =     SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_room_update_data', $subject_timetable_with_time_to_room_update_data);
                                        //         }
                                        //     }
                                        // } else {
                                        //     $room_time_check = true;
                                        // }

                                        $room_time_check = true;

                                        if ($room_time_check === true) {
                                            //insert time table data
                                            $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_group_id])->andWhere(['subject_id' => $merge_form_data_data['subject_id']])->one();
                                            $subject_timetable_update->campus_id  = $campus_id;
                                            $subject_timetable_update->day_id  = $day_id;
                                            $subject_timetable_update->class_id   = $class_id;
                                            $subject_timetable_update->section_id   = $section_id;
                                            $subject_timetable_update->subject_id  = $merge_form_data_data['subject_id'];
                                            $subject_timetable_update->subject_group_subject_id   = $subject_group_subjects->id;
                                            $subject_timetable_update->teacher_details_id  = $merge_form_data_data['teacher_details_id'];
                                            $subject_timetable_update->time_from  = $merge_form_data_data['time_from'];
                                            $subject_timetable_update->time_to  = $merge_form_data_data['time_to'];
                                            $subject_timetable_update->room_id  = $merge_form_data_data['room_id'];
                                            $subject_timetable_update->period  = $merge_form_data_data['period'];
                                            $subject_timetable_update->academic_year_id   = $academic_year_id;
                                            $subject_timetable_update->save(false);
                                            $subject_timetable_update->status   = SubjectTimetable::STATUS_ACTIVE;
                                            $error = false;
                                        }
                                    }
                                }
                            }
                        } else {




                            //check class with time 
                            $subject_timetable_with_time_from = SubjectTimetable::find()
                                ->where(['campus_id' => $campus_id])
                                ->andWhere(['day_id' => $day_id])
                                ->andWhere(['class_id' => $class_id])
                                ->andWhere(['section_id' => $section_id])
                                ->andWhere(['academic_year_id' => $academic_year_id])
                                ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                ->all();
                            $subject_timetable_with_time_to = SubjectTimetable::find()
                                ->where(['campus_id' => $campus_id])
                                ->andWhere(['day_id' => $day_id])
                                ->andWhere(['class_id' => $class_id])
                                ->andWhere(['section_id' => $section_id])
                                ->andWhere(['academic_year_id' => $academic_year_id])
                                ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                ->all();
                            if (!empty($subject_timetable_with_time_from) || !empty($subject_timetable_with_time_to)) {
                                $time_check = false;

                                if (!empty($subject_timetable_with_time_from)) {
                                    foreach ($subject_timetable_with_time_from as $subject_timetable_with_time_from_data) {
                                        $insert_error[$subject_timetable_with_time_from_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_data', $subject_timetable_with_time_from_data);

                                        //update status of time table if data is exist
                                        SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_from_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_from_data->teacher_details_id);
                                    }
                                }

                                if (!empty($subject_timetable_with_time_to)) {
                                    foreach ($subject_timetable_with_time_to as $subject_timetable_with_time_to_data) {
                                        $insert_error[$subject_timetable_with_time_to_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_data', $subject_timetable_with_time_to_data);
                                        //update status of time table if data is exist
                                        SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_to_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_to_data->teacher_details_id);
                                    }
                                }
                            } else {
                                $time_check = true;
                            }

                            if ($time_check === true) {
                                //check teacher with time
                                $subject_timetable_with_time_from_teacher = SubjectTimetable::find()
                                    ->where(['campus_id' => $campus_id])
                                    ->andWhere(['day_id' => $day_id])
                                    ->andWhere(['academic_year_id' => $academic_year_id])
                                    ->andWHere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                                    ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                    ->all();

                                $subject_timetable_with_time_to_teacher = SubjectTimetable::find()
                                    ->where(['campus_id' => $campus_id])
                                    ->andWhere(['day_id' => $day_id])
                                    ->andWhere(['academic_year_id' => $academic_year_id])
                                    ->andWHere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                                    ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                    ->all();

                                // var_dump($subject_timetable_with_time_to_teacher);exit;
                                if (!empty($subject_timetable_with_time_from_teacher) || !empty($subject_timetable_with_time_to_teacher)) {
                                    $teacher_time_check = false;

                                    if (!empty($subject_timetable_with_time_from_teacher)) {
                                        foreach ($subject_timetable_with_time_from_teacher as $subject_timetable_with_time_from_teacher_data) {
                                            $insert_error[$subject_timetable_with_time_from_teacher_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_teacher_data', $subject_timetable_with_time_from_teacher_data);
                                            //update status of time table if data is exist
                                            SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_from_teacher_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_from_teacher_data->teacher_details_id);
                                        }
                                    }


                                    if (!empty($subject_timetable_with_time_to_teacher)) {
                                        foreach ($subject_timetable_with_time_to_teacher as $subject_timetable_with_time_to_teacher_data) {
                                            $insert_error[$subject_timetable_with_time_to_teacher_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_teacher_data', $subject_timetable_with_time_to_teacher_data);
                                            //update status of time table if data is exist
                                            SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_to_teacher_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_to_teacher_data->teacher_details_id);
                                        }
                                    }
                                } else {
                                    $teacher_time_check = true;
                                }
                                if ($teacher_time_check === true) {
                                    //class rooms check 

                                    $subject_timetable_with_time_from_room = SubjectTimetable::find()
                                        ->where(['campus_id' => $campus_id])
                                        ->andWhere(['day_id' => $day_id])
                                        ->andWhere(['academic_year_id' => $academic_year_id])
                                        ->andWHere(['room_id' => $merge_form_data_data['room_id']])
                                        ->andWhere(['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                        ->all();
                                    $subject_timetable_with_time_to_room = SubjectTimetable::find()
                                        ->where(['campus_id' => $campus_id])
                                        ->andWhere(['day_id' => $day_id])
                                        ->andWhere(['academic_year_id' => $academic_year_id])
                                        ->andWHere(['room_id' => $merge_form_data_data['room_id']])
                                        ->andWhere(['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']])
                                        ->all();


                                    if (!empty($subject_timetable_with_time_from_room) || !empty($subject_timetable_with_time_to_room)) {
                                        $room_time_check = false;

                                        if (!empty($subject_timetable_with_time_from_room)) {
                                            foreach ($subject_timetable_with_time_from_room as $subject_timetable_with_time_from_room_data) {
                                                $insert_error[$subject_timetable_with_time_from_room_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_from_room_data', $subject_timetable_with_time_from_room_data);
                                                //update status of time table if data is exist
                                                SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_from_room_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_from_room_data->teacher_details_id);
                                            }
                                        }


                                        if (!empty($subject_timetable_with_time_to_room)) {
                                            foreach ($subject_timetable_with_time_to_room as $subject_timetable_with_time_to_room_data) {
                                                $insert_error[$subject_timetable_with_time_to_room_data->id] = SubjectTimetable::getErrorTimeTableValues('subject_timetable_with_time_to_room_data', $subject_timetable_with_time_to_room_data);
                                                //update status of time table if data is exist
                                                SubjectTimetable::updateSubjectTimeTable($campus_id, $subject_timetable_with_time_to_room_data->id, $day_id, $class_id, $section_id, $academic_year_id, $subject_timetable_with_time_to_room_data->teacher_details_id);
                                            }
                                        }
                                    } else {
                                        $room_time_check = true;
                                    }
                                    if ($room_time_check === true) {
                                        //insert time table data
                                        $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_group_id])->andWhere(['subject_id' => $merge_form_data_data['subject_id']])->one();
                                        $subject_timetable = new  SubjectTimetable();
                                        $subject_timetable->campus_id  = $campus_id;
                                        $subject_timetable->day_id  = $day_id;
                                        $subject_timetable->class_id   = $class_id;
                                        $subject_timetable->section_id   = $section_id;
                                        $subject_timetable->subject_id  = $merge_form_data_data['subject_id'];
                                        $subject_timetable->subject_group_subject_id   = $subject_group_subjects->id;
                                        $subject_timetable->teacher_details_id  = $merge_form_data_data['teacher_details_id'];
                                        $subject_timetable->time_from  = $merge_form_data_data['time_from'];
                                        $subject_timetable->time_to  = $merge_form_data_data['time_to'];
                                        $subject_timetable->room_id  = $merge_form_data_data['room_id'];
                                        $subject_timetable->period  = $merge_form_data_data['period'];
                                        $subject_timetable->academic_year_id   = $academic_year_id;
                                        $subject_timetable->status   = SubjectTimetable::STATUS_ACTIVE;

                                        $subject_timetable->save(false);
                                        $error = false;
                                    }
                                }
                            }
                        }
                        // } else {

                        //     $time_error[] = [
                        //         'time_from_timestamp' => $time_from_timestamp,
                        //         'time_to_timestamp' => $time_to_timestamp,

                        //     ];
                        // }
                    }
                } else {
                    $error = true;
                    $msg = "time table data not found";
                }


                // if (!empty($time_error)) {
                //     $data['status'] = 'ok';
                //     $data['details'] = 'some data not saved time error occurred from_time < to_time please check ex:-if from time 2:00:00 to time 3:00:00 ';
                //     $data['time_error'] = true;
                // } else {

                if (empty($insert_error) && empty($update_error)) {
                    $data['status'] = 'ok';
                    $data['details'] = 'time table data saved';
                    $data['insert_or_update_error'] = false;
                } else {
                    $data['status'] = 'ok';
                    $data['details'] = 'time table data saved';
                    $data['insert_or_update_error'] = true;
                    $data['insert_error'] = array_filter($insert_error);
                    $data['update_error'] = array_filter($update_error);
                    SubjectTimetable::subjectTimeTableErrorReport($insert_error, $update_error);
                }
                // }
            } else {
                $data['status'] = 'nok';
                $data['error'] = 'Subject id not found';
            }
        } catch (Exception $e) {
            $data['status'] = 'nok';
            $data['error'] = $e->getMessage();
        }


        return json_encode($data);
    }

    // public function actionAddOrUpdateTimeTable()
    // {
    //     $data = [];
    //     $post = \Yii::$app->request->post();
    //     $session = Yii::$app->session;
    //     $section_id = '';
    //     $class_id = '';
    //     $subject_group_subject_id = '';
    //     $update_error = [];
    //     $insert_error = [];

    //     $error = true;
    //     if ($session->has('section_id')) {
    //         $section_id = $session->get('section_id');
    //     }

    //     if ($session->has('class_id')) {
    //         $class_id = $session->get('class_id');
    //     }

    //     if ($session->has('subject_group_subject_id')) {
    //         $subject_group_subject_id = trim($session->get('subject_group_subject_id'));
    //     }

    //     if ($session->has('academic_year_id')) {
    //         $academic_year_id = trim($session->get('academic_year_id'));
    //     }

    //     try {
    //         $subject_id = $post['SubjectTimetable']['subject_id'];
    //         $teacher_details_id = $post['SubjectTimetable']['teacher_details_id'];
    //         $time_from = $post['SubjectTimetable']['time_from'];
    //         $time_to = $post['SubjectTimetable']['time_to'];
    //         $day_id = $post['SubjectTimetable']['day_id'];
    //         $room_id = $post['SubjectTimetable']['room_id'];
    //         $period = $post['SubjectTimetable']['period'];
    //         $subject_timetable_id = $post['SubjectTimetable']['subject_timetable_id'];

    //         // Define all weekdays as strings
    //         $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    //         // If you want only weekdays (Monday-Friday), uncomment the line below:
    //         // $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    //         if (!empty($subject_id)) {
    //             $count = count($subject_id);
    //             for ($i = 0; $i < $count; $i++) {
    //                 $merge_form_data[] = [
    //                     'subject_id' => $subject_id[$i],
    //                     'teacher_details_id' => $teacher_details_id[$i],
    //                     'time_from' => $time_from[$i],
    //                     'time_to' => $time_to[$i],
    //                     'room_id' => $room_id[$i],
    //                     'period' => $period[$i],
    //                     'subject_timetable_id' => $subject_timetable_id[$i]
    //                 ];
    //             }

    //             if (!empty($merge_form_data)) {
    //                 // Loop through all weekdays
    //                 foreach ($weekdays as $current_day_id) {
    //                     foreach ($merge_form_data as $merge_form_data_data) {
    //                         $time_from_timestamp = strtotime($merge_form_data_data['time_from']);
    //                         $time_to_timestamp = strtotime($merge_form_data_data['time_to']);

    //                         if ($time_from_timestamp < $time_to_timestamp) {
    //                             $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
    //                             $class_id = $class_id;
    //                             $section_id = $section_id;
    //                             $subject_group_id = $subject_group_subject_id;

    //                             if (!empty($merge_form_data_data['subject_timetable_id']) && $current_day_id == $day_id) {
    //                                 // Update logic only for the original day
    //                                 $this->updateTimetableEntry($merge_form_data_data, $current_day_id, $campus_id, $academic_year_id, $class_id, $section_id, $subject_group_id, $update_error, $error);
    //                             } else {
    //                                 // Insert logic for all days (including original day if no ID exists)
    //                                 $this->insertTimetableEntry($merge_form_data_data, $current_day_id, $campus_id, $academic_year_id, $class_id, $section_id, $subject_group_id, $insert_error, $error);
    //                             }
    //                         } else {
    //                             $time_error[] = [
    //                                 'time_from_timestamp' => $time_from_timestamp,
    //                                 'time_to_timestamp' => $time_to_timestamp,
    //                                 'day_name' => $current_day_id
    //                             ];
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 $error = true;
    //                 $msg = "time table data not found";
    //             }

    //             // Response logic remains the same
    //             if (!empty($time_error)) {
    //                 $data['status'] = 'ok';
    //                 $data['details'] = 'some data not saved time error occurred from_time < to_time please check ex:-if from time 2:00:00 to time 3:00:00 ';
    //                 $data['time_error'] = true;
    //             } else {
    //                 if (empty($insert_error) && empty($update_error)) {
    //                     $data['status'] = 'ok';
    //                     $data['details'] = 'time table data saved for all weekdays';
    //                     $data['insert_or_update_error'] = false;
    //                 } else {
    //                     $data['status'] = 'ok';
    //                     $data['details'] = 'time table data saved for all weekdays with some conflicts';
    //                     $data['insert_or_update_error'] = true;
    //                     $data['insert_error'] = array_filter($insert_error);
    //                     $data['update_error'] = array_filter($update_error);
    //                     SubjectTimetable::subjectTimeTableErrorReport($insert_error, $update_error);
    //                 }
    //             }
    //         } else {
    //             $data['status'] = 'nok';
    //             $data['error'] = 'Subject id not found';
    //         }
    //     } catch (Exception $e) {
    //         $data['status'] = 'nok';
    //         $data['error'] = $e->getMessage();
    //     }

    //     return json_encode($data);
    // }

    // private function insertTimetableEntry($merge_form_data_data, $current_day_id, $campus_id, $academic_year_id, $class_id, $section_id, $subject_group_id, &$insert_error, &$error)
    // {
    //     // Check for conflicts for this specific day
    //     $conflicts = $this->checkTimeConflicts($merge_form_data_data, $current_day_id, $campus_id, $academic_year_id, $class_id, $section_id);

    //     if (!$conflicts['has_conflict']) {
    //         // No conflicts, insert the timetable entry
    //         $subject_group_subjects = SubjectGroupSubjects::find()
    //             ->where(['subject_group_id' => $subject_group_id])
    //             ->andWhere(['subject_id' => $merge_form_data_data['subject_id']])
    //             ->one();

    //         $subject_timetable = new SubjectTimetable();
    //         $subject_timetable->campus_id = $campus_id;
    //         $subject_timetable->day_id = $current_day_id;
    //         $subject_timetable->class_id = $class_id;
    //         $subject_timetable->section_id = $section_id;
    //         $subject_timetable->subject_id = $merge_form_data_data['subject_id'];
    //         $subject_timetable->subject_group_subject_id = $subject_group_subjects->id;
    //         $subject_timetable->teacher_details_id = $merge_form_data_data['teacher_details_id'];
    //         $subject_timetable->time_from = $merge_form_data_data['time_from'];
    //         $subject_timetable->time_to = $merge_form_data_data['time_to'];
    //         $subject_timetable->room_id = $merge_form_data_data['room_id'];
    //         $subject_timetable->period = $merge_form_data_data['period'];
    //         $subject_timetable->academic_year_id = $academic_year_id;
    //         $subject_timetable->status = SubjectTimetable::STATUS_ACTIVE;

    //         $subject_timetable->save(false);
    //         $error = false;
    //     } else {
    //         // Handle conflicts
    //         foreach ($conflicts['conflicts'] as $conflict) {
    //             $insert_error[$conflict->id] = SubjectTimetable::getErrorTimeTableValues('conflict_day_' . $current_day_id, $conflict);
    //         }
    //     }
    // }

    // private function checkTimeConflicts($merge_form_data_data, $day_id, $campus_id, $academic_year_id, $class_id, $section_id)
    // {
    //     $conflicts = [];
    //     $has_conflict = false;

    //     // Check class time conflicts
    //     $class_conflicts = SubjectTimetable::find()
    //         ->where(['campus_id' => $campus_id])
    //         ->andWhere(['day_id' => $day_id])
    //         ->andWhere(['class_id' => $class_id])
    //         ->andWhere(['section_id' => $section_id])
    //         ->andWhere(['academic_year_id' => $academic_year_id])
    //         ->andWhere([
    //             'or',
    //             ['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']],
    //             ['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']]
    //         ])
    //         ->all();

    //     if (!empty($class_conflicts)) {
    //         $conflicts = array_merge($conflicts, $class_conflicts);
    //         $has_conflict = true;
    //     }

    //     // Check teacher time conflicts
    //     $teacher_conflicts = SubjectTimetable::find()
    //         ->where(['campus_id' => $campus_id])
    //         ->andWhere(['day_id' => $day_id])
    //         ->andWhere(['academic_year_id' => $academic_year_id])
    //         ->andWhere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
    //         ->andWhere([
    //             'or',
    //             ['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']],
    //             ['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']]
    //         ])
    //         ->all();

    //     if (!empty($teacher_conflicts)) {
    //         $conflicts = array_merge($conflicts, $teacher_conflicts);
    //         $has_conflict = true;
    //     }

    //     // Check room conflicts
    //     $room_conflicts = SubjectTimetable::find()
    //         ->where(['campus_id' => $campus_id])
    //         ->andWhere(['day_id' => $day_id])
    //         ->andWhere(['academic_year_id' => $academic_year_id])
    //         ->andWhere(['room_id' => $merge_form_data_data['room_id']])
    //         ->andWhere([
    //             'or',
    //             ['between', 'time_from', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']],
    //             ['between', 'time_to', $merge_form_data_data['time_from'], $merge_form_data_data['time_to']]
    //         ])
    //         ->all();

    //     if (!empty($room_conflicts)) {
    //         $conflicts = array_merge($conflicts, $room_conflicts);
    //         $has_conflict = true;
    //     }

    //     return [
    //         'has_conflict' => $has_conflict,
    //         'conflicts' => $conflicts
    //     ];
    // }

    // private function updateTimetableEntry($merge_form_data_data, $day_id, $campus_id, $academic_year_id, $class_id, $section_id, $subject_group_id, &$update_error, &$error)
    // {
    //     // Your existing update logic here - keeping it as is for the original day
    //     $subject_timetable_update = SubjectTimetable::find()->where(['id' => $merge_form_data_data['subject_timetable_id']])->one();

    //     // Check if exact same entry exists
    //     $subject_timetable_update_check = SubjectTimetable::find()
    //         ->where(['id' => $subject_timetable_update->id])
    //         ->andWhere(['day_id' => $day_id])
    //         ->andWhere(['campus_id' => $campus_id])
    //         ->andWhere(['academic_year_id' => $academic_year_id])
    //         ->andWhere(['class_id' => $class_id])
    //         ->andWhere(['section_id' => $section_id])
    //         ->andWhere(['time_from' => $merge_form_data_data['time_from']])
    //         ->andWhere(['time_to' => $merge_form_data_data['time_to']])
    //         ->andWhere(['room_id' => $merge_form_data_data['room_id']])
    //         ->andWhere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
    //         ->one();

    //     if (!empty($subject_timetable_update_check)) {
    //         // Update existing entry
    //         $subject_timetable_update_check->subject_id = $merge_form_data_data['subject_id'];
    //         $subject_timetable_update_check->campus_id = $campus_id;
    //         $subject_timetable_update_check->day_id = $day_id;
    //         $subject_timetable_update_check->class_id = $class_id;
    //         $subject_timetable_update_check->section_id = $section_id;
    //         $subject_timetable_update_check->teacher_details_id = $merge_form_data_data['teacher_details_id'];
    //         $subject_timetable_update_check->time_from = $merge_form_data_data['time_from'];
    //         $subject_timetable_update_check->time_to = $merge_form_data_data['time_to'];
    //         $subject_timetable_update_check->room_id = $merge_form_data_data['room_id'];
    //         $subject_timetable_update_check->period = $merge_form_data_data['period'];
    //         $subject_timetable_update_check->academic_year_id = $academic_year_id;
    //         $subject_timetable_update_check->status = SubjectTimetable::STATUS_ACTIVE;
    //         $subject_timetable_update_check->save(false);
    //         $error = false;
    //     } else {
    //         // Handle conflicts and update logic as per your existing code
    //         // ... (keep your existing complex update logic here)
    //     }
    // }

    public function actionSubjectTimeTableDelete()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        try {
            $subject_timetable_id = $post['id'];
            $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
            if (!empty($subject_timetable)) {
                $subject_timetable_id = $subject_timetable->id;
                $student_class_attendance = StudentClassAttendance::find()->where(['subject_timetable_id' => $subject_timetable_id])->one();
                if (empty($student_class_attendance)) {
                    if ($subject_timetable->delete()) {
                        $data['status'] = 'ok';
                        $data['details'] = 'data deleted success';
                    } else {
                        $data['status'] = 'nok';
                        $data['error'] = "time table deleted failed";
                    }
                } else {
                    $data['status'] = 'nok';
                    $data['error'] = "time table deleted failed because student has Attendance this table ";
                }
            } else {
                $data['status'] = 'nok';
                $data['error'] = "details not found";
            }
        } catch (Exception $e) {
            $data['status'] = 'nok';
            $data['error'] = $e->getMessage();
        }

        return json_encode($data);
    }

    public function actionSearchTeacherTimeTable()
    {
        $post = Yii::$app->request->post();
        $teacher_details_id = $post['SubjectTimetable']['teacher_details_id'];

        $model = new SubjectTimetable();
        $teacherTimeTable = SubjectTimetable::find()->Where(['teacher_details_id' => $teacher_details_id])->all();


        return $this->render('index_teacher_time_table', [
            'teacherTimeTable' => $teacherTimeTable,
            'model' => $model
        ]);
    }



    /**
     * Import Subject Timetable from Excel file
     */
    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('importFile');

            if ($file) {
                $path = 'uploads/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);

                $spreadsheet = IOFactory::load($path);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Parse Header
                $headers = array_map('trim', array_shift($rows));
                $importData = [];

                foreach ($rows as $row) {
                    $rowData = array_combine($headers, $row);
                    $rowData['time_from'] = $this->formatTimeFromExcel($rowData['time_from']);
                    $rowData['time_to'] = $this->formatTimeFromExcel($rowData['time_to']);
                    $importData[] = $rowData;
                }

                // Get context (from session or fixed)
                $session = Yii::$app->session;
                $academic_year_id = $session->get('academic_year_id');
                $class_id = $session->get('class_id');
                $section_id = $session->get('section_id');
                $subject_group_subject_id = $session->get('subject_group_subject_id');
                $campus_id = \app\models\User::getCampusesByUser(Yii::$app->user->identity->id);

                $result = $this->processImportedTimetableData(
                    $importData,
                    $campus_id,
                    $class_id,
                    $section_id,
                    $subject_group_subject_id,
                    $academic_year_id
                );

                Yii::$app->session->setFlash($result['insert_or_update_error'] ? 'warning' : 'success', $result['details']);

                return $this->redirect(['index']);
            }
        }


        Yii::$app->session->setFlash('error', 'No file uploaded');
        return $this->redirect(['index']);
    }
    /**
     * Format time from Excel to HH:MM:SS format
     */
    private function formatTimeFromExcel($timeValue)
    {
        if (empty($timeValue)) {
            return false;
        }

        // If it's already a string in correct format
        if (is_string($timeValue) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeValue)) {
            return $timeValue;
        }

        // If it's a string in HH:MM format
        if (is_string($timeValue) && preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) {
            return $timeValue . ':00';
        }

        // If it's an Excel time value (decimal)
        if (is_numeric($timeValue)) {
            try {
                $dateTime = Date::excelToDateTimeObject($timeValue);
                return $dateTime->format('H:i:s');
            } catch (Exception $e) {
                return false;
            }
        }

        // Try to parse other time formats
        $timestamp = strtotime($timeValue);
        if ($timestamp !== false) {
            return date('H:i:s', $timestamp);
        }

        return false;
    }

    /**
     * Process imported timetable data using existing validation logic
     */
    private function processImportedTimetableData($importData, $campus_id, $class_id, $section_id, $subject_group_subject_id, $academic_year_id)
    {
        $update_error = [];
        $insert_error = [];
        $success_count = 0;
        $error_count = 0;

        foreach ($importData as $merge_form_data_data) {
            $day_id = $merge_form_data_data['day_id'];

            $time_from_timestamp = strtotime($merge_form_data_data['time_from']);
            $time_to_timestamp = strtotime($merge_form_data_data['time_to']);

            if ($time_from_timestamp < $time_to_timestamp) {
                // 🔍 Find room_id using class_room_title
                $room_title = trim($merge_form_data_data['room_id']);
                $room = ClassRooms::find()
                    ->where(['class_room_title' => $room_title, 'campus_id' => $campus_id])
                    ->one();

                if (!$room) {
                    $error_count++;
                    $insert_error[] = "Class room '{$room_title}' not found in campus.";
                    continue;
                }

                $room_id = $room->id;

                if (!empty($merge_form_data_data['subject_timetable_id'])) {
                    // ====== UPDATE LOGIC ======
                    $subject_timetable_update = SubjectTimetable::find()->where(['id' => $merge_form_data_data['subject_timetable_id']])->one();

                    if (!$subject_timetable_update) {
                        $error_count++;
                        $update_error[$merge_form_data_data['subject_timetable_id']] = "Subject timetable record not found";
                        continue;
                    }

                    // Check for existing record with same parameters
                    $subject_timetable_update_check = SubjectTimetable::find()->where(['id' => $subject_timetable_update->id])
                        ->andWhere(['day_id' => $day_id])
                        ->andWhere(['campus_id' => $campus_id])
                        ->andWhere(['academic_year_id' => $academic_year_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['time_from' => $merge_form_data_data['time_from']])
                        ->andWhere(['time_to' => $merge_form_data_data['time_to']])
                        ->andWhere(['room_id' => $room_id])
                        ->andWhere(['teacher_details_id' => $merge_form_data_data['teacher_details_id']])
                        ->one();

                    if (!empty($subject_timetable_update_check)) {
                        // Simple update - no conflicts
                        $subject_timetable_update_check->subject_id = $merge_form_data_data['subject_id'];
                        $subject_timetable_update_check->period = $merge_form_data_data['period'];
                        $subject_timetable_update_check->status = SubjectTimetable::STATUS_ACTIVE;
                        $subject_timetable_update_check->save(false);
                        $success_count++;
                    } else {
                        $conflicts = []; // Skipping conflict check as requested

                        if (empty($conflicts)) {
                            $subject_group_subjects = SubjectGroupSubjects::find()
                                ->where(['subject_group_id' => $subject_group_subject_id])
                                ->andWhere(['subject_id' => $merge_form_data_data['subject_id']])
                                ->one();

                            if ($subject_group_subjects) {
                                $subject_timetable_update->campus_id = $campus_id;
                                $subject_timetable_update->day_id = $day_id;
                                $subject_timetable_update->class_id = $class_id;
                                $subject_timetable_update->section_id = $section_id;
                                $subject_timetable_update->subject_id = $merge_form_data_data['subject_id'];
                                $subject_timetable_update->subject_group_subject_id = $subject_group_subjects->id;
                                $subject_timetable_update->teacher_details_id = $merge_form_data_data['teacher_details_id'];
                                $subject_timetable_update->time_from = $merge_form_data_data['time_from'];
                                $subject_timetable_update->time_to = $merge_form_data_data['time_to'];
                                $subject_timetable_update->room_id = $room_id;
                                $subject_timetable_update->period = $merge_form_data_data['period'];
                                $subject_timetable_update->academic_year_id = $academic_year_id;
                                $subject_timetable_update->status = SubjectTimetable::STATUS_ACTIVE;
                                $subject_timetable_update->save(false);
                                $success_count++;
                            } else {
                                $error_count++;
                                $update_error[$merge_form_data_data['subject_timetable_id']] = "Subject not found in subject group";
                            }
                        } else {
                            $error_count++;
                            $update_error[$merge_form_data_data['subject_timetable_id']] = $conflicts;
                        }
                    }
                } else {
                    // ====== INSERT LOGIC ======
                    $conflicts = []; // Skipping conflict check as requested

                    if (empty($conflicts)) {
                        $subject_group_subjects = SubjectGroupSubjects::find()
                            ->where(['subject_group_id' => $subject_group_subject_id])
                            ->andWhere(['subject_id' => $merge_form_data_data['subject_id']])
                            ->one();

                        if ($subject_group_subjects) {
                            $subject_timetable = new SubjectTimetable();
                            $subject_timetable->campus_id = $campus_id;
                            $subject_timetable->day_id = $day_id;
                            $subject_timetable->class_id = $class_id;
                            $subject_timetable->section_id = $section_id;
                            $subject_timetable->subject_id = $merge_form_data_data['subject_id'];
                            $subject_timetable->subject_group_subject_id = $subject_group_subjects->id;
                            $subject_timetable->teacher_details_id = $merge_form_data_data['teacher_details_id'];
                            $subject_timetable->time_from = $merge_form_data_data['time_from'];
                            $subject_timetable->time_to = $merge_form_data_data['time_to'];
                            $subject_timetable->room_id = $room_id;
                            $subject_timetable->period = $merge_form_data_data['period'];
                            $subject_timetable->academic_year_id = $academic_year_id;
                            $subject_timetable->status = SubjectTimetable::STATUS_ACTIVE;
                            $subject_timetable->save(false);
                            $success_count++;
                        } else {
                            $error_count++;
                            $insert_error[] = "Subject {$merge_form_data_data['subject_id']} not found in subject group";
                        }
                    } else {
                        $error_count++;
                        $insert_error[] = $conflicts;
                    }
                }
            } else {
                $error_count++;
                // Suppress "Invalid time range" error as requested
            }
        }

        // Return result
        $result = [
            'status' => 'ok',
            'success_count' => $success_count,
            'error_count' => $error_count,
            'total_processed' => count($importData)
        ];

        if (!empty($insert_error) || !empty($update_error)) {
            $result['details'] = "Import completed with some errors";
            $result['insert_or_update_error'] = true;
            $result['insert_error'] = array_filter($insert_error);
            $result['update_error'] = array_filter($update_error);
        } else {
            $result['details'] = "All records imported successfully";
            $result['insert_or_update_error'] = false;
        }

        if ($result['insert_or_update_error']) {
            $message = "<strong>Import completed with some errors:</strong><br><ul>";
            foreach ($result['insert_error'] as $err) {
                $message .= "<li>" . htmlspecialchars($err) . "</li>";
            }
            foreach ($result['update_error'] as $key => $err) {
                $message .= "<li>ID {$key}: " . htmlspecialchars($err) . "</li>";
            }
            $message .= "</ul>";
            Yii::$app->session->setFlash('error', $message);
        }

        return $result;
    }


    /**
     * Check for timetable conflicts
     */
    private function checkTimetableConflicts($data, $campus_id, $class_id, $section_id, $academic_year_id, $day_id, $exclude_id = null)
    {
        $conflicts = [];

        // Build base query
        $baseQuery = SubjectTimetable::find()
            ->where(['campus_id' => $campus_id])
            ->andWhere(['day_id' => $day_id])
            ->andWhere(['academic_year_id' => $academic_year_id]);

        if ($exclude_id) {
            $baseQuery->andWhere(['not in', 'id', [$exclude_id]]);
        }

        // Check class time conflicts
        $classConflicts = (clone $baseQuery)
            ->andWhere(['class_id' => $class_id])
            ->andWhere(['section_id' => $section_id])
            ->andWhere([
                'or',
                ['between', 'time_from', $data['time_from'], $data['time_to']],
                ['between', 'time_to', $data['time_from'], $data['time_to']]
            ])
            ->all();

        if (!empty($classConflicts)) {
            $conflicts[] = "Class schedule conflict detected";
        }

        // Check teacher conflicts
        $teacherConflicts = (clone $baseQuery)
            ->andWhere(['teacher_details_id' => $data['teacher_details_id']])
            ->andWhere([
                'or',
                ['between', 'time_from', $data['time_from'], $data['time_to']],
                ['between', 'time_to', $data['time_from'], $data['time_to']]
            ])
            ->all();

        if (!empty($teacherConflicts)) {
            $conflicts[] = "Teacher schedule conflict detected";
        }

        // Check room conflicts
        $roomConflicts = (clone $baseQuery)
            ->andWhere(['room_id' => $data['room_id']])
            ->andWhere([
                'or',
                ['between', 'time_from', $data['time_from'], $data['time_to']],
                ['between', 'time_to', $data['time_from'], $data['time_to']]
            ])
            ->all();

        if (!empty($roomConflicts)) {
            $conflicts[] = "Room schedule conflict detected";
        }

        return implode(', ', $conflicts);
    }
}
