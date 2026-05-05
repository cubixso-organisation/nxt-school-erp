<?php

namespace app\modules\admin\controllers;

use app\components\SendOtp;
use app\components\Toast;
use Yii;
use app\models\User;
use app\modules\admin\models\base\Campus;
use app\modules\admin\models\base\StudentClass;
use app\modules\admin\models\base\SubjectTimetable;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\search\StudentClassAttendanceSearch;
use app\modules\admin\models\StudentDetails;
use DateTime;
use Exception;
use PHPUnit\Util\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json as HelpersJson;

/**
 * StudentClassAttendanceController implements the CRUD actions for StudentClassAttendance model.
 */
class StudentClassAttendanceController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'generate-attendance', 'update-attendance', 'fetch-attendance-data', 'student-sections', 'students', 'index-old'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'generate-attendance', 'update-attendance', 'fetch-attendance-data', 'student-sections', 'students', 'index-old'],
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
     * Lists all StudentClassAttendance models.
     * @return mixed
     */



    public function actionIndex()
    {
        $searchModel = new StudentClassAttendanceSearch();
        //  $dataProvider = null;

        // Determine the search method based on the user role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }

        $studentId = Yii::$app->request->get('student_id');
        $campusId = (new User())->getCampusId();
        $students = StudentDetails::find()->where(['campus_id' => $campusId])->all();

        $classes = StudentClass::find()->where(['campus_id' => $campusId])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'classes' => $classes, // Pass class data to view
            'campusId' => $campusId, // Pass campus ID to the view
            'students' => $students,
            'studentId' => $studentId

        ]);
    }

    public function actionIndexOld()
    {
        $searchModel = new StudentClassAttendanceSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index_old', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionStudentSections()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $class_id = $parents[0]; // Class ID from dropdown
                $sections = ClassSections::find()->where(['student_class_id' => $class_id])->all();
                foreach ($sections as $section) {
                    $out[] = ['id' => $section->id, 'name' => $section->section_name];
                }
                echo HelpersJson::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo HelpersJson::encode(['output' => '', 'selected' => '']);
    }

    public function actionStudents()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $section_id = $parents[0]; // Section ID from dropdown
                $students = StudentDetails::find()->where(['section_id' => $section_id])->all();
                foreach ($students as $student) {
                    $out[] = ['id' => $student->id, 'name' => $student->student_name];
                }
                echo HelpersJson::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo HelpersJson::encode(['output' => '', 'selected' => '']);
    }
    // Separate action for fetching attendance data based on year and student ID
    public function actionFetchAttendanceData($studentId, $year)
    {
        $attendanceData = [];
        $attendanceModels = StudentClassAttendance::find()
            ->where(['student_id' => $studentId])
            ->andWhere(['between', 'YEAR(date)', $year, $year])
            ->all();

        foreach ($attendanceModels as $attendance) {
            // Check if subjectTimetable exists
            if ($attendance->subjectTimetable) {
                // Fetch teacher details only if timetable exists
                $teacher = TeacherDetails::find()->where(['id' => $attendance->subjectTimetable->teacher_details_id])->one();

                // Check if teacher exists
                $teacherName = $teacher ? $teacher->name : 'Unknown'; // Fallback to 'Unknown' if teacher is not found

                // Get time of day
                $getTimeOfDay = SubjectTimetable::getTimeOfDay($attendance->subjectTimetable->time_from);

                // Add to attendance data
                $attendanceData[] = [
                    'title' => $attendance->subject->subject_name . ': ' . ($attendance->status == 1 ? 'Present' : 'Absent'),
                    'date' => date('Y-m-d', strtotime($attendance->date)), // Format date to YYYY-MM-DD
                    'className' => $attendance->status == 1 ? 'attendance-entry' : 'attendance-entry absent',
                    'starttime' => $attendance->subjectTimetable->time_from,
                    'endtime' => $attendance->subjectTimetable->time_to,
                    'getTimeOfDay' => $getTimeOfDay,
                    'teacher' => $teacherName // Use the teacher name, or 'Unknown'
                ];
            } else {
                // Handle case where there is no subject timetable
                $attendanceData[] = [
                    'title' => $attendance->subject->subject_name . ': ' . ($attendance->status == 1 ? 'Present' : 'Absent'),
                    'date' => date('Y-m-d', strtotime($attendance->date)),
                    'className' => $attendance->status == 1 ? 'attendance-entry' : 'attendance-entry absent',
                    'teacher' => 'No Timetable'
                ];
            }
        }

        // Return or process $attendanceData as needed
        return $this->asJson($attendanceData);
    }









    /**
     * Displays a single StudentClassAttendance model.
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



    public function actionGenerateAttendance()
    {
        try {
            $get = Yii::$app->request->get();
            $section_id = '';
            $dateFound = isset($get["date-studentclassattendancesearch-date-disp"]);

            // Check if section_id exists in the request
            if (isset($get["StudentClassAttendanceSearch"]["section_id"])) {
                $section_id = $get["StudentClassAttendanceSearch"]["section_id"];
            }

            // If section_id exists but date is missing, just perform a search and filter
            if (!empty($section_id) && !$dateFound) {
                // Filter by section without generating new attendance
                $searchModel = new StudentClassAttendanceSearch();

                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                    $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, '', $section_id);
                } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
                    $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
                } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
                }

                // Render the attendance view with search model and data provider
                return $this->render('generateAttendance', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }

            // If both section_id and date are present, proceed with attendance generation
            if (!empty($get)) {
                $date = $get["date-studentclassattendancesearch-date-disp"] ?? (string)date('d-m-Y');
                $dateObj = DateTime::createFromFormat('d-m-Y', $date);

                // Validate if the date format is correct
                if (!$dateObj) {
                    throw new Exception('Invalid date format. Please use d-m-Y format.');
                }

                $date = $dateObj->format('Y-m-d');
                $day = date('l', strtotime($date));

                if (empty($day) || empty($section_id)) {
                    $error = (new Toast)->error('error', 'Please select a valid date and class section.');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // Fetch the timetable for the selected day and section
                $campusId = (new User())->getCampusId();
                $subjectTimeTable = SubjectTimetable::find()
                    ->where(['campus_id' => $campusId])
                    ->andWhere(['section_id' => $section_id])
                    ->andWhere(['day_id' => $day])
                    ->all();

                if (!empty($subjectTimeTable)) {
                    foreach ($subjectTimeTable as $timetable) {
                        $studentDetails = StudentDetails::find()
                            ->where(['campus_id' => $campusId])
                            ->andWhere(['section_id' => $section_id])
                            ->all();

                        foreach ($studentDetails as $sDetail) {
                            $checkAttendanceAlreadyCreated = StudentClassAttendance::find()
                                ->where(['student_id' => $sDetail->id])
                                ->andWhere(['subject_timetable_id' => $timetable->id])
                                ->andWhere(['date' => $date])
                                ->one();

                            if (!$checkAttendanceAlreadyCreated) {
                                // Creating new attendance
                                $studentClassAttendance = new StudentClassAttendance();
                                $studentClassAttendance->student_id = $sDetail->id;
                                $studentClassAttendance->teacher_id = $timetable->teacher_details_id;
                                $studentClassAttendance->subject_timetable_id = $timetable->id;
                                $studentClassAttendance->academic_year_id = $timetable->academic_year_id;
                                $studentClassAttendance->subject_group_id = $timetable->subject_group_subject_id;
                                $studentClassAttendance->subject_id = $timetable->subject_id;
                                $studentClassAttendance->date = $date;
                                $studentClassAttendance->period = $timetable->period;
                                $studentClassAttendance->status = StudentClassAttendance::STATUS_ABSENT;
                                $studentClassAttendance->created_on = $date;

                                if (!$studentClassAttendance->save(false)) {
                                    throw new Exception('Failed to save attendance for student ID: ' . $sDetail->id);
                                }
                            }
                        }
                    }
                }
            }

            // Instantiate the search model and handle different user roles
            $searchModel = new StudentClassAttendanceSearch();

            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, '', $section_id);
            } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
                $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
            } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
            }

            // Render the attendance view with search model and data provider
            return $this->render('generateAttendance', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    // In your current model (e.g., Attendance model)
    public function getStudent()
    {
        return $this->hasOne(StudentDetails::className(), ['id' => 'student_id']);
    }

    /**
     * Creates a new StudentClassAttendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentClassAttendance();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StudentClassAttendance model.
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


    function actionUpdateAttendance()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        if (empty($post)) {
            return ['status' => false, 'error' => 'No data posted'];
        }

        $classAttendanceIds = $post['data'];
        $status = $post['status'];

        if (empty($classAttendanceIds) || empty($status)) {
            return ['status' => false, 'error' => 'Missing class attendance IDs or status'];
        }

        $errors = [];
        $successCount = 0;

        foreach ($classAttendanceIds as $id) {
            try {
                $studentClassAttendance = StudentClassAttendance::findOne($id);
                if ($studentClassAttendance === null) {
                    $errors[] = "Attendance record not found for ID: $id";
                    continue;
                }

                $studentClassAttendance->status = $status;

                if (!$studentClassAttendance->save(false)) {
                    $errors[] = "Failed to update attendance for ID: $id";
                    continue;
                }

                $successCount++;

                // Send SMS if Absent
                if (in_array((string)$status, ['2', 'a', 'Absent'], true)) {
                    $student = $studentClassAttendance->student;
                    $subject = $studentClassAttendance->subject;
                    $campusId = (new User())->getCampusId();
                    $campusName = Campus::find()->where(['id' => $campusId])->one()->name_of_the_educational_Institution ?? 'nxt schools';

                    $student_name = $student->student_name ?? 'No Name';
                    $sudentClass = $student->studentClass->title ?? 'No Class';
                    $sudentSection = $student->section->section_name ?? 'No Section';
                    $contact_no = $student->parent->contact_number ?? '9490599376';

                    if (!empty($contact_no)) {
                        $sms = "Dear Parent/Guardian, This is to inform you that your ward, $student_name of $sudentClass-$sudentSection, was absent from school today. -Estudent Regards, $campusName";
                        $sms_url = urlencode($sms);
                        $template_id = '1007530594033050923';
                        $sender = 'ESTDNT';
                        $route = 7;

                        $SendOtpData = new SendOtp();
                        $SendOtpData->sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
                    }
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                $errors[] = "Error updating ID $id: " . $e->getMessage();
            }
        }

        return [
            'status' => empty($errors),
            'message' => $successCount > 0 ? "Successfully updated $successCount record(s)" : null,
            'errors' => $errors,
        ];
    }




    /**
     * Deletes an existing StudentClassAttendance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        // $model = $this->findModel($id);
        // if(!empty($model)){
        //     $model->status = StudentClassAttendance::STATUS_DELETE;
        //     $model->save(false); 
        // }

        // return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = StudentClassAttendance::find()->where([
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
     * Finds the StudentClassAttendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentClassAttendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentClassAttendance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
