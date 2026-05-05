<?php

namespace app\modules\exammanagement\controllers;

use app\components\FirebaseNotification;
use app\modules\admin\models\base\ExamsResult;
use DocumentGenerator;
use Yii;
use app\models\User;
use app\modules\admin\models\base\StudentClassAttendance;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\Exams;
use app\modules\documentgenerator\models\base\ScheduledExamMarksDevision;
use app\modules\exammanagement\models\base\MarksheetSetting;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevision as BaseScheduledExamMarksDevision;
use app\modules\exammanagement\models\ExamStudentMarksheet;
use app\modules\exammanagement\models\Grade;
use app\modules\exammanagement\models\GradeDefination;
use app\modules\exammanagement\models\ScheduledExamMarksDevisionResults;
use app\modules\exammanagement\models\search\ExamStudentMarksheetSearch;
use Exception;
use kartik\mpdf\Pdf;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * ExamStudentMarksheetController implements the CRUD actions for ExamStudentMarksheet model.
 */
class ExamStudentMarksheetController extends Controller
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
                            'generate-exam-wise-marksheet',
                            'silver-crest-marksheet',
                            'update-all-grade'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'update-all-grade'],
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
     * Lists all ExamStudentMarksheet models.
     * @return mixed
     */
    public function actionIndex()
    {



        $searchModel = new ExamStudentMarksheetSearch();
        $get = Yii::$app->request->get('ExamStudentMarksheetSearch');
        // var_dump($get);exit;

        if (!empty($get)) {
            $session_id = isset($get["session_id"]) ? $get["session_id"] : 0;
            $class_id = isset($get["class_id"]) ? $get["class_id"] : 0;
            $section_id = isset($get["section_id"]) ? $get["section_id"] : 0;
            $exam_id = isset($get["exam_id"]) ? $get["exam_id"] : 0;


            // Results

            $examResults = ExamsResult::find()
                ->where(['academic_year_id' => $session_id])
                ->andWhere(['class_id' => $class_id])
                ->andWhere(['section_id' => $section_id])
                ->andWhere(['exam_id' => $exam_id])
                ->all();




            foreach ($examResults as $examResult) {

                $checkDataAlredy = ExamStudentMarksheet::find()->where(['student_id' => $examResult->student_id])
                    ->andWhere(['session_id' => $examResult->academic_year_id])->andWhere(['exam_id' => $examResult->exam_id])->one();
                if (empty($checkDataAlredy)) {



                    $model =  new ExamStudentMarksheet();
                    $model->user_id = $examResult->user_id;
                    $model->campus_id = $examResult->campus_id;
                    $model->student_id = $examResult->student_id;
                    $model->session_id = $examResult->academic_year_id;
                    $model->class_id = $examResult->class_id;
                    $model->section_id  = $examResult->section_id;
                    $model->exam_id  = $examResult->exam_id;
                    $model->status  = ExamStudentMarksheet::STATUS_ACTIVE;

                    $exam = Exams::find()->where(['id' => $examResult->exam_id])->one();

                    if (!empty($exam)) {
                        $examTotalMarks = $exam->total_percentage_or_gpa ?? 0;
                        $model->total_marks = $examTotalMarks;

                        $scoredMarks = ExamsResult::find()
                            ->where(['academic_year_id' => $session_id])
                            ->andWhere(['class_id' => $class_id])
                            ->andWhere(['section_id' => $section_id])
                            ->andWhere(['student_id' => $examResult->student_id])
                            ->andWhere(['exam_id' => $exam_id])
                            ->sum('marks_scored');

                        if ($scoredMarks != 0) {
                            $model->total_obtained_marks = $scoredMarks;
                            $calcTotalPercentage = ($model->total_obtained_marks / $model->total_marks) * 100;
                            $model->total_percentage = round($calcTotalPercentage, 2);
                        } else {
                            $model->total_obtained_marks = 0;
                            $model->total_percentage = 0;
                        }
                    }

                    if ($model->save(false)) {
                        $grade = Grade::find()->where(['campus_id' => (new User())->getCampusId()])
                            ->andWhere(['status' => Grade::STATUS_ACTIVE])
                            ->andWhere(['maximum_exam_marks' => $model->total_marks])
                            ->andWhere(['section_id' => $examResult->section_id])
                            ->one();

                        if (!empty($grade)) {
                            $gradeDefination = GradeDefination::find()
                                ->where(['grade_id' => $grade->id])
                                ->andWhere(['<=', 'min_marks', $model->total_obtained_marks])
                                ->andWhere(['>=', 'max_marks', $model->total_obtained_marks])
                                ->one();
                            if (!empty($gradeDefination)) {
                                $model->total_grade = $gradeDefination->grade;
                                $model->total_cgpa = $gradeDefination->cgpa;
                                $model->save(false);
                            }
                        }
                    }
                } else {
                    $checkDataAlredy->user_id = $examResult->user_id;
                    $checkDataAlredy->campus_id = $examResult->campus_id;
                    $checkDataAlredy->student_id = $examResult->student_id;
                    $checkDataAlredy->session_id = $examResult->academic_year_id;
                    $checkDataAlredy->class_id = $examResult->class_id;
                    $checkDataAlredy->section_id  = $examResult->section_id;
                    $checkDataAlredy->exam_id  = $examResult->exam_id;
                    $checkDataAlredy->status  = ExamStudentMarksheet::STATUS_ACTIVE;

                    $exam = Exams::find()->where(['id' => $examResult->exam_id])->one();

                    if (!empty($exam)) {
                        $examTotalMarks = $exam->total_percentage_or_gpa ?? 0;
                        $checkDataAlredy->total_marks = $examTotalMarks;

                        $scoredMarks = ExamsResult::find()
                            ->where(['academic_year_id' => $session_id])
                            ->andWhere(['class_id' => $class_id])
                            ->andWhere(['section_id' => $section_id])
                            ->andWhere(['student_id' => $examResult->student_id])
                            ->andWhere(['exam_id' => $exam_id])
                            ->sum('marks_scored');

                        if ($scoredMarks != 0) {
                            $checkDataAlredy->total_obtained_marks = $scoredMarks;
                            $calcTotalPercentage = ($checkDataAlredy->total_obtained_marks / $checkDataAlredy->total_marks) * 100;
                            $checkDataAlredy->total_percentage = $calcTotalPercentage;
                        } else {
                            $checkDataAlredy->total_obtained_marks = 0;
                            $checkDataAlredy->total_percentage = 0;
                        }
                    }

                    if ($checkDataAlredy->save(false)) {
                        $grade = Grade::find()->where(['campus_id' => (new User())->getCampusId()])
                            ->andWhere(['status' => Grade::STATUS_ACTIVE])
                            ->andWhere(['maximum_exam_marks' => $checkDataAlredy->total_marks])
                            ->andWhere(['section_id' => $examResult->section_id])
                            ->one();

                        if (!empty($grade)) {
                            $gradeDefination = GradeDefination::find()
                                ->where(['grade_id' => $grade->id])
                                ->andWhere(['<=', 'min_marks', $checkDataAlredy->total_obtained_marks])
                                ->andWhere(['>=', 'max_marks', $checkDataAlredy->total_obtained_marks])
                                ->one();
                            if (!empty($gradeDefination)) {
                                $checkDataAlredy->total_grade = $gradeDefination->grade;
                                $checkDataAlredy->total_cgpa = $gradeDefination->cgpa;
                                $checkDataAlredy->save(false);
                            }
                        }
                    }
                }
            }
        }





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }



        if (Yii::$app->request->post('hasEditable')) {
            $postId = Yii::$app->request->post('editableKey');
            $model = ExamStudentMarksheet::findOne($postId);

            $out = Json::encode(['output' => '', 'message' => '']);
            // $categoryArr = Yii::$app->request->post('Category');
            $post = [];
            $posted = current($_POST['ExamStudentMarksheet']);
            $post['ExamStudentMarksheet'] = $posted;

            if ($model->load($post)) {

                $output = '';


                // Update Marks_type






                if (array_key_exists('total_grade', $post['ExamStudentMarksheet'])) {
                    if ($post['ExamStudentMarksheet']['total_grade'] != Null || $post['ExamStudentMarksheet']['total_grade']  != "") {
                        $grade = $post['ExamStudentMarksheet']['total_grade'];

                        $oldScore = $model->total_grade;

                        $model->total_grade = $grade;
                        $model->save(false);
                        $out = Json::encode(['output' =>  $model->total_grade, 'message' => '']);
                    }
                }

                if (array_key_exists('total_cgpa', $post['ExamStudentMarksheet'])) {

                    if ($post['ExamStudentMarksheet']['total_cgpa'] != Null || $post['ExamStudentMarksheet']['total_cgpa']  != "") {
                        $grade = $post['ExamStudentMarksheet']['total_cgpa'];

                        $oldScore = $model->total_cgpa;

                        $model->total_cgpa = $grade;
                        $model->save(false);
                        $out = Json::encode(['output' =>  $model->total_cgpa, 'message' => '']);
                    }
                }
            }
            return $out;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionGenerateExamWiseMarksheet($id = '')
    {
        $data = [];
        $structure = [];

        // Fetch Exam Wise Marksheet details
        $examWiseMarksheet = ExamStudentMarksheet::find()->where(['id' => $id])->one();
        if (!$examWiseMarksheet) {
            Yii::$app->session->setFlash('error', "Details Not Found");
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Fetch Marksheet Template Settings
        $getMarksheetTemplateSettings = MarksheetSetting::find()->where(['campus_id' => $examWiseMarksheet->campus_id])->one();
        if (!$getMarksheetTemplateSettings) {
            Yii::$app->session->setFlash('error', "Missing Marksheet Template Settings");
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Fetch Exam Results
        $examResults = ExamsResult::find()
            ->where(['exam_id' => $examWiseMarksheet->exam_id])
            ->andWhere(['student_id' => $examWiseMarksheet->student_id])
            ->all();

        if (empty($examResults)) {
            Yii::$app->session->setFlash('error', "Result Not Found");
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Fetch Student Details
        $studentDetails = StudentDetails::find()->where(['id' => $examWiseMarksheet->student_id])->one();
        if (empty($studentDetails)) {
            Yii::$app->session->setFlash('error', "Student Details Not Found");
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Initialize arrays for divisions and marks
        $devisionArray = [];
        $ddMarks = [];

        // Iterate over each exam result to build marks and division arrays
        foreach ($examResults as $result) {
            $scheduledExamDevisions = BaseScheduledExamMarksDevision::find()
                ->where(['exam_schedule_id' => $result->exam_scheduled_id])
                ->all();

            foreach ($scheduledExamDevisions as $sed) {
                if (!empty($sed)) {
                    $devisionArray[] = $sed->marksDevision->title;
                }
            }

            $divisionResults = ScheduledExamMarksDevisionResults::find()
                ->where(['student_id' => $studentDetails->id])
                ->andWhere(['exam_result_id' => $result->exams_result_id])
                ->all();

            $subjectMarks = [
                'subject' => $result->subject->subject_name,
                'scores' => [],
                'total' => 0,
                'grade' => ''
            ];

            foreach ($divisionResults as $ds) {
                $subjectMarks['scores'][$ds->marksDevision->title] = $ds->marks_scored;
                $subjectMarks['total'] += $ds->marks_scored; // Calculate total marks
            }

            $subjectMarks['grade'] = $result->grade; // Assume grade is pre-calculated
            $ddMarks[] = $subjectMarks;
        }

        // Fetch and process attendance data
        $currentYear = date('Y');
        $currentMonth = date('m');
        $lastDayOfMonth = date("Y-m-t", strtotime("$currentYear-$currentMonth-01"));

        $attendanceData = StudentClassAttendance::find()
            ->select([
                new Expression('YEAR(date) as year'),
                new Expression('MONTH(date) as month'),
                new Expression('COUNT(DISTINCT date) as total_working_days'),
                new Expression('SUM(CASE WHEN status = ' . StudentClassAttendance::STATUS_PRESENT . ' THEN 1 ELSE 0 END) as total_present_days')
            ])
            ->where(['student_id' => $studentDetails->id])
            ->andWhere(['academic_year_id' => $examWiseMarksheet->session_id])
            ->andWhere(['<=', 'date', $lastDayOfMonth])  // Fixed here
            ->groupBy(new Expression('YEAR(date), MONTH(date)'))
            ->orderBy(new Expression('YEAR(date), MONTH(date)'))
            ->asArray()
            ->all();

        // Initialize the attendance array
        $attendanceReport = [];

        foreach ($attendanceData as $data) {
            $monthName = date("F", mktime(0, 0, 0, $data['month'], 10)); // Convert month number to name

            $attendanceReport[] = [
                'month' => $monthName,
                'working_days' => (int)$data['total_working_days'],
                'present_days' => (int)$data['total_present_days']
            ];
        }

        // Prepare the final structure
        $structure = [
            'header_image_url' => $getMarksheetTemplateSettings->marksheet_header_image,
            'profile_image' => $studentDetails->profile_photo ?? "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png",
            'exam_name' => $examWiseMarksheet->exam->name_of_exam ?? "N/A",
            'principal_signature_image' => $getMarksheetTemplateSettings->principal_signature ?? "https://onlinepngtools.com/images/examples-onlinepngtools/george-walker-bush-signature.png",
            'student_details' => [
                'student_name' => $studentDetails->student_name ?? "N/A",
                'father_name' => $studentDetails->parent->name_of_the_father ?? "N/A",
                'mother_name' => $studentDetails->parent->name_of_the_mother ?? "N/A",
                'gender' => $studentDetails->user->gender ?? "N/A",
                'id_no' => $studentDetails->id ?? "N/A",
                'class' => $studentDetails->studentClass->title ?? "N/A",
                'session' => $studentDetails->session->title ?? "N/A",
                'date' => date('d-M-Y')
            ],
            'total_marks' => $examWiseMarksheet->total_obtained_marks ?? "N/A",
            'percentage' => $examWiseMarksheet->total_percentage ?? "N/A",
            'headers' => array_unique($devisionArray),
            'marks' => $ddMarks,
            'graphs' => [
                'marks_chart' => [
                    'labels' => array_column($ddMarks, 'subject'),
                    'data' => array_column($ddMarks, 'total'),
                    'backgroundColor' => "rgba(79, 15, 20, .6)",
                    'borderColor' => "rgba(79, 15, 20, 1)"
                ],
                'attendance_chart' => [
                    'labels' => ["Present Days", "Absent Days"],
                    'data' => [
                        array_sum(array_column($attendanceReport, 'present_days')),
                        array_sum(array_column($attendanceReport, 'working_days')) - array_sum(array_column($attendanceReport, 'present_days'))
                    ],
                    'backgroundColor' => ["rgba(79, 15, 20, .6)", "rgba(150, 150, 150, .6)"],
                    'borderColor' => ["rgba(79, 15, 20, 1)", "rgba(150, 150, 150, 1)"]
                ]
            ],
            'attendance' => $attendanceReport
        ];

        // Output the final JSON structure
        $finalStructure = json_encode($structure, JSON_PRETTY_PRINT);

        $generatePdf = (new FirebaseNotification())->generateMarksheetPdf($finalStructure);
        // var_dump($generatePdf);exit;
        return $generatePdf;
    }



    // Helper function to calculate grade
    private function calculateGrade($totalMarks)
    {
        if ($totalMarks >= 90) {
            return 'A+';
        } elseif ($totalMarks >= 80) {
            return 'A';
        } elseif ($totalMarks >= 70) {
            return 'B+';
        } elseif ($totalMarks >= 60) {
            return 'B';
        } elseif ($totalMarks >= 50) {
            return 'C+';
        } else {
            return 'C';
        }
    }


    // Silver crest school marksheet
    public function actionSilverCrestMarksheet($id = '')
{
    $data = [];
    $structure = [];

    // Fetch Exam Wise Marksheet details
    $examWiseMarksheet = ExamStudentMarksheet::find()->where(['id' => $id])->one();

    if (!$examWiseMarksheet) {
        Yii::$app->session->setFlash('error', "Details Not Found");
        return $this->redirect(Yii::$app->request->referrer);
    }

    // Fetch Marksheet Template Settings
    $getMarksheetTemplateSettings = MarksheetSetting::find()->where(['campus_id' => $examWiseMarksheet->campus_id])->one();
    if (!$getMarksheetTemplateSettings) {
        Yii::$app->session->setFlash('error', "Missing Marksheet Template Settings");
        return $this->redirect(Yii::$app->request->referrer);
    }

    // Fetch Exam Results
    $examResults = ExamsResult::find()
        ->where(['exam_id' => $examWiseMarksheet->exam_id])
        ->andWhere(['student_id' => $examWiseMarksheet->student_id])
        ->all();
        // var_dump($examResults);exit;
    if (empty($examResults)) {
        Yii::$app->session->setFlash('error', "Result Not Found");
        return $this->redirect(Yii::$app->request->referrer);
    }

    // Fetch Student Details
    $studentDetails = StudentDetails::find()->where(['id' => $examWiseMarksheet->student_id])->one();
    if (empty($studentDetails)) {
        Yii::$app->session->setFlash('error', "Student Details Not Found");
        return $this->redirect(Yii::$app->request->referrer);
    }

    // Initialize arrays for divisions and marks
    $devisionArray = [];
    $ddMarks = [];

    // Iterate over each exam result to build marks and division arrays
    foreach ($examResults as $result) {
        $scheduledExamDevisions = BaseScheduledExamMarksDevision::find()
            ->where(['exam_schedule_id' => $result->exam_scheduled_id])
            ->all();

        foreach ($scheduledExamDevisions as $sed) {
            if (!empty($sed)) {
                $devisionArray[] = isset($sed->marksDevision->short_hand) ? $sed->marksDevision->short_hand : $sed->marksDevision->title;
            }
        }

        $divisionResults = ScheduledExamMarksDevisionResults::find()
            ->where(['student_id' => $studentDetails->id])
            ->andWhere(['exam_result_id' => $result->exams_result_id])
            ->all();

        $subjectMarks = [
            'subject' => $result->subject->subject_name,
            'scores' => [],
            'total' => 0,
            'grade' => '',
            'cgpa' => ''
        ];

        foreach ($divisionResults as $ds) {
            $subjectMarks['scores'][$ds->marksDevision->title] = $ds->marks_scored;
            $subjectMarks['total'] = $result->total_marks;
        }

        $subjectMarks['grade'] = $result->grade;
        $subjectMarks['cgpa'] = $result->cgpa;

        $ddMarks[] = $subjectMarks;
    }

    // Fetch and process attendance data
    $examDate = $scheduledExamDevisions[0]->examSchedule->exam_date ?? null;
    if (!$examDate) {
        Yii::$app->session->setFlash('error', "Exam Date Not Found");
        return $this->redirect(Yii::$app->request->referrer);
    }

    $examYear = date('Y', strtotime($examDate));
    $examMonth = date('m', strtotime($examDate));

    $attendanceData = StudentClassAttendance::find()
        ->select([
            new Expression('YEAR(date) as year'),
            new Expression('MONTH(date) as month'),
            new Expression('COUNT(DISTINCT date) as total_working_days'),
            new Expression('COUNT(DISTINCT CASE WHEN status = ' . StudentClassAttendance::STATUS_PRESENT . ' THEN date ELSE NULL END) as total_present_days')
        ])
        ->where(['student_id' => $studentDetails->id])
        ->andWhere(['academic_year_id' => $examWiseMarksheet->session_id])
        ->andWhere(['YEAR(date)' => $examYear])
        ->andWhere(['<=', 'MONTH(date)', $examMonth])
        ->groupBy(new Expression('YEAR(date), MONTH(date)'))
        ->orderBy(new Expression('YEAR(date), MONTH(date)'))
        ->asArray()
        ->all();

    $attendanceReport = [];
    foreach ($attendanceData as $data) {
        $monthName = date("F", mktime(0, 0, 0, $data['month'], 10));
        $attendanceReport[] = [
            'month' => $monthName,
            'working_days' => (int)($data['total_working_days'] ?? 0),
            'present_days' => (int)($data['total_present_days'] ?? 0)
        ];
    }

    $totalRows = count($attendanceReport);
    $halfRows = ceil($totalRows / 2);

    $attendanceLeftColumn = array_slice($attendanceReport, 0, $halfRows);
    $attendanceRightColumn = array_slice($attendanceReport, $halfRows);

    // Prepare the final structure
    $structure = [
        'header_image_url' => $getMarksheetTemplateSettings->marksheet_header_image,
        'profile_image' => $studentDetails->profile_photo,
        'exam_name' => $examWiseMarksheet->exam->name_of_exam,
        'principal_signature_image' => $getMarksheetTemplateSettings->principal_signature,
        'student_id' => $studentDetails->id,
        'student_details' => [
            'student_name' => $studentDetails->student_name,
            'father_name' => $studentDetails->parent->name_of_the_father,
            'mother_name' => $studentDetails->parent->name_of_the_mother ?? "N/A",
            'gender' => $studentDetails->user->gender ?? "N/A",
            'id_no' => $studentDetails->admission_number ?? "N/A",
            'class' => $studentDetails->studentClass->title ?? "N/A",
            'session' => $examWiseMarksheet->session->title ?? "N/A",
            'date' => date('d-M-Y'),
            'dob' => $studentDetails->date_of_birth ?? "",
            'contact_no' => $studentDetails->parent->contact_number ?? "",
            'roll_no' => $studentDetails->rool_number ?? ""
        ],
        'total_marks' => $examWiseMarksheet->total_obtained_marks,
        'total_marks_sum' => $examWiseMarksheet->total_marks,
        'total_grade' => $examWiseMarksheet->total_grade ?? "",
        'percentage' => round($examWiseMarksheet->total_percentage, 2),
        'total_cgpa' => (string)$examWiseMarksheet->total_cgpa ?? "N/A",
        'headers' => array_unique($devisionArray),
        'marks' => $ddMarks,
        'graphs' => [
            'marks_chart' => [
                'labels' => array_column($ddMarks, 'subject'),
                'data' => array_column($ddMarks, 'total'),
                'backgroundColor' => "rgba(79, 15, 20, .6)",
                'borderColor' => "rgba(79, 15, 20, 1)"
            ],
            'attendance_chart' => [
                'labels' => ["Present Days", "Absent Days"],
                'data' => [
                    array_sum(array_column($attendanceReport, 'present_days')),
                    array_sum(array_column($attendanceReport, 'working_days')) - array_sum(array_column($attendanceReport, 'present_days'))
                ],
                'backgroundColor' => ["rgba(79, 15, 20, .6)", "rgba(150, 150, 150, .6)"],
                'borderColor' => ["rgba(79, 15, 20, 1)", "rgba(150, 150, 150, 1)"]
            ]
        ],
        'attendance' => $attendanceReport,
        'attendanceLeftColumn' => $attendanceLeftColumn,
        'attendanceRightColumn' => $attendanceRightColumn,
    ];

    $finalStructure = json_encode($structure, JSON_PRETTY_PRINT);

    // Ensure single execution
    $isRedirected = false;
    $generatePdf = (new FirebaseNotification())->silverCrestMarksheet($finalStructure);
    $jsonResponse = $generatePdf->content;

    $responseData = json_decode($jsonResponse, true);

    if ($responseData && $responseData['success'] == true && !$isRedirected) {
        $pdfUrl = $responseData['url'];
        $isRedirected = true;
        return $this->redirect($pdfUrl);
    } else {
        Yii::$app->session->setFlash('error', "Error generating PDF or accessing the response.");
        return $this->redirect(Yii::$app->request->referrer);
    }
}


    public function actionUpdateAllGrade()
    {
        // Retrieve POST data
        $post = Yii::$app->request->post();
        // var_dump($post);
        // exit;

        // Extract data from POST request
        $sectionId = Yii::$app->request->post('sectionId');
        $exam_id = Yii::$app->request->post('examid');


        $totalMarksScored = 0;



        //*********** checking for the grade **********//

        // var_dump($sectionId);
        // var_dump($post);
        // exit;


        $examResults = ExamStudentMarksheet::find()
            ->where(['exam_id' => $exam_id])
            ->andWhere(['section_id' => $sectionId])->all();

        if (!empty($examResults)) {
            foreach ($examResults as $examResult) {
                $totalMarksScored = $examResult->total_obtained_marks ?? 0;

                // var_dump($examResult->section_id);
                // var_dump($examResult->total_marks);
                
                // exit;

                $grade = Grade::find()->where(['campus_id' => (new User())->getCampusId()])
                    ->andWhere(['status' => Grade::STATUS_ACTIVE])
                    ->andWhere(['maximum_exam_marks' => $examResult->total_marks])
                    ->andWhere(['section_id' => $examResult->section_id])
                    ->one()
                    ;


                if (!empty($grade)) {
                    $gradeDefination = GradeDefination::find()
                        ->where(['grade_id' => $grade->id])
                        ->andWhere(['<=', 'min_marks', $totalMarksScored])
                        ->andWhere(['>=', 'max_marks', $totalMarksScored])
                        ->one()
                        ;

                        // var_dump($gradeDefination->createCommand()->getRawSql()
                // );exit;
                }

                    $examResult->total_grade = $gradeDefination->grade??"NA";
                    $examResult->total_cgpa = $gradeDefination->cgpa??"NA";
                    $examResult->save(false);
                


                
            }
        }
        return $this->asJson(['status' => 'OK']);
    }

    // public function actionGenerateExamWiseMarksheet($id = '')
    // {
    //     // try {
    //     // Check if $id is empty or not provided
    //     if ($id === '') {
    //         throw new \Exception('$id parameter is empty.');
    //     }

    //     // Exam Results
    //     $examWiseMarksheet = ExamStudentMarksheet::find()->where(['id' => $id])->one();

    //     // Check if $examWiseMarksheet is null
    //     if (!$examWiseMarksheet) {
    //         throw new \Exception('Exam student marksheet not found for the provided ID.');
    //     }

    //     // Check if $examWiseMarksheet has necessary attributes (exam_id, student_id, etc.)
    //     if (!$examWiseMarksheet->exam_id || !$examWiseMarksheet->student_id || !$examWiseMarksheet->campus || !$examWiseMarksheet->session || !$examWiseMarksheet->class || !$examWiseMarksheet->student) {
    //         throw new \Exception('One or more necessary attributes of examWiseMarksheet are null.');
    //     }

    //     $examResults = ExamsResult::find()->where(['exam_id' => $examWiseMarksheet->exam_id])->andWhere(['student_id' => $examWiseMarksheet->student_id])->all();

    //     // Check if $examResults is null or empty
    //     if (!$examResults) {
    //         throw new \Exception('No exam results found for the provided exam and student.');
    //     }


    //     $chartPath = $this->renderPartial('_bar_chart');

    //     var_dump($chartPath);exit;
    //     // Render PDF content
    //     $content = $this->renderPartial('_exam_wise_marksheet_pdf', [
    //         'examWiseMarksheet' => $examWiseMarksheet,
    //         'examResults' => $examResults
    //     ]);

    //     $hostInfo = Yii::$app->request->hostInfo;
    //     $baseUrl = Yii::$app->request->baseUrl;
    //     $folderPath = Yii::getAlias('@webroot/uploads/examwisemarksheets/') . $examWiseMarksheet->campus->name_of_the_educational_Institution . '/' . $examWiseMarksheet->session->title . '/' . $examWiseMarksheet->class->title . '/';

    //     // Check if $folderPath exists or create it
    //     if (!file_exists($folderPath)) {
    //         if (!mkdir($folderPath, 0777, true)) {
    //             throw new \Exception('Failed to create directory for saving PDF.');
    //         }
    //     }

    //     $pdfFilePath = $folderPath . 'exam_marksheet_' . $examWiseMarksheet->campus->name_of_the_educational_Institution . $examWiseMarksheet->session->title . $examWiseMarksheet->class->title . $examWiseMarksheet->student->student_name . '.pdf';

    //     // Setup kartik\mpdf\Pdf component
    //     $pdf = new Pdf([
    //         'mode' => Pdf::MODE_CORE,
    //         'format' => Pdf::FORMAT_A4,
    //         'orientation' => Pdf::ORIENT_PORTRAIT,
    //         'destination' => Pdf::DEST_FILE,
    //         'filename' => $pdfFilePath,
    //         'content' => $content,
    //         'cssFile' => '@app/themes/pdfstyle.css',
    //         'cssInline' => '.kv-heading-1{font-size:18px}',
    //         'options' => [
    //             'marginTop' => 0,  // Set top margin to 0
    //             'defaultheaderline' => 0,  // Disable header line
    //             'defaulfooterline' => 0,   // Disable footer line
    //         ],
    //         'methods' => [
    //             'SetHeader' => false,      // Disable the header
    //             'SetFooter' => false,      // Disable the footer
    //             'SetDisplayMode' => 'fullpage',  // Set display mode to full page
    //         ],
    //     ]);

    //     // Render PDF and save it to file
    //     $pdf->render();

    //     // Determine URL for the saved file
    //     $pdfFileName = basename($pdfFilePath);
    //     $pdfUrl = $hostInfo . $baseUrl . Url::to('/uploads/examwisemarksheets/') . $examWiseMarksheet->campus->name_of_the_educational_Institution . '/' . $examWiseMarksheet->session->title . '/' . $examWiseMarksheet->class->title . '/' . $pdfFileName;

    //     // Update marksheet_url column with the full URL
    //     $examWiseMarksheet->marksheet_url = $pdfUrl;
    //     $examWiseMarksheet->save(false); // Save without validation

    //     // Redirect user to the generated PDF
    //     return $this->redirect($pdfUrl);
    //     // } catch (\Exception $e) {
    //     //     Yii::$app->session->setFlash('error', $e->getMessage());
    //     //     // Handle error, perhaps redirect to an error page
    //     //     return $this->redirect(['/site/error']);
    //     // }
    // }


    /**
     * Displays a single ExamStudentMarksheet model.
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
     * Creates a new ExamStudentMarksheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExamStudentMarksheet();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExamStudentMarksheet model.
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
     * Deletes an existing ExamStudentMarksheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = ExamStudentMarksheet::STATUS_DELETE;
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
            $model = ExamStudentMarksheet::find()->where([
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
     * Finds the ExamStudentMarksheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExamStudentMarksheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExamStudentMarksheet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
