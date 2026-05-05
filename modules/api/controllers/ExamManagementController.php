<?php

namespace app\modules\api\controllers;

use app\modules\admin\models\base\Campus;
use app\modules\admin\models\base\TeacherAttenddence;
use app\modules\exammanagement\models\base\ExamSchedules;
use yii;
use app\models\User;
use yii\helpers\Url;
use yii\filters\AccessRule;
use app\components\RazorPay;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\AuthSettings;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Auth;

use app\modules\admin\models\AuthSession;
use app\modules\admin\models\ClassTeacher;

use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\LeaveRequests;
use app\modules\admin\models\StudentAssessment;
use app\modules\api\controllers\BKController;

use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\StudentDairy;
use app\modules\admin\models\StudentHasAssessment;
use app\modules\admin\models\StudentHasDairy;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\TeacherDetails;
use kartik\mpdf\Pdf;
use Exception;
use app\components\SendOtp;
use app\modules\admin\models\AttendanceSettings;
use app\modules\admin\models\base\ExamsResult as BaseExamsResult;
use app\modules\admin\models\base\Subjects;
use app\modules\admin\models\ClassRooms;
use app\modules\admin\models\Exams;
use app\modules\admin\models\ExamsResult;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\NoticeBoards;
use app\modules\admin\models\SpecialDays;
use app\modules\admin\models\StudentNoticeBoards;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentHasNotice;
use app\modules\admin\models\UserOtp;
use app\modules\admin\models\WebSetting;
use app\modules\exammanagement\models\base\MarksDivition;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevision;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults;
use app\modules\exammanagement\models\base\TeacherClassAndSubjects;
use yii\web\UploadedFile;

class ExamManagementController extends BKController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:51276', 'http://localhost:50674', 'https://web.estudent.tech'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'PUT'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [

                    'class' => AccessRule::className()
                ],

                'rules' => [
                    [
                        'actions' => [
                            'check',
                            'index',
                            'send-otp',
                            'resend-otp',
                            'verify-otp',
                            'teacher-profile',
                            'teacher-class',
                            'teacher-sections',
                            'exams',
                            'subjects',
                            'students',
                            'update-marks',
                            'mark-absent',
                            'update-grade-and-answersheet',
                            'view-result-detail',
                            'update-marks-sheet',
                            'save-marks-results'




                        ],

                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [

                        'actions' => [
                            'check',
                            'index',
                            'send-otp',
                            'verify-otp',
                            'resend-otp',
                            'teacher-profile',
                            'teacher-class',
                            'teacher-sections',
                            'exams',
                            'subjects',
                            'students',
                            'update-marks',
                            'mark-absent',
                            'update-grade-and-answersheet',

                            'view-result-detail',
                            'update-marks-sheet',
                            'save-marks-results'







                        ],

                        'allow' => true,
                        'roles' => [

                            '?',
                            '*',

                        ]
                    ]
                ]
            ]

        ]);
    }


    public function actionIndex()
    {
        $data['details'] =  ['Hello'];
        return $this->sendJsonResponse($data);
    }



    public function actionTeacherClass()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {
                $teacherClassAndSubject = TeacherClassAndSubjects::find()->where(['teacher_user_id' => $user_id])->all();
                if (empty($teacherClassAndSubject)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No class assigned to this teacher";
                    return $this->sendJsonResponse($data);
                } else {
                    $classArr = [];
                    foreach ($teacherClassAndSubject as $tcs) {
                        $classId = $tcs->section->studentClass->id;
                        // Check if class ID already exists in $classArr
                        if (!isset($classArr[$classId])) {
                            $classArr[$classId] = $tcs->section->studentClass->asJson();
                        }
                    }
                }

                if (!empty($classArr)) {
                    $data['status'] = self::API_OK;
                    $data['detail'] = array_values($classArr); // Reset array keys to ensure sequential numbering
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No data found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }


    public function actionTeacherSections()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {

                $classId = $post['class_id'];
                if (empty($classId)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Required Class Id";
                    return $this->sendJsonResponse($data);
                }


                $classes = StudentClass::find()->where(['campus_id' => (new User())->getTeacherCampus($user_id)])->andWhere(['id' => $classId])->one();
                if (empty($classes)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Invalid Class";
                    return $this->sendJsonResponse($data);
                } else {
                    $classSections = ClassSections::find()->where(['student_class_id' => $classes->id])->all();

                    if (empty($classSections)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Section Found";
                        return $this->sendJsonResponse($data);
                    } else {

                        $sections = [];
                        foreach ($classSections as $classSection) {
                            $latestRecord = TeacherClassAndSubjects::find()
                                ->where(['section_id' => $classSection->id])
                                ->orderBy(['id' => SORT_DESC])
                                ->one();

                            if (!empty($latestRecord)) {
                                $sections[] = $latestRecord->asJson();
                            }

                            // var_dump($teacherStudentAndClass);exit;


                        }

                        // var_dump($sections);
                        // exit;

                        $data['status'] = self::API_OK;
                        $data['details'] = $sections;
                    }
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }
    public function actionExams()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $classId = $post['class_id'];
            $sectionId = $post['section_id'];
            if (!empty($user_id)) {
                $campusId = (new User())->getTeacherCampus($user_id);
                $currentAcademicYear = (new Campus())->getCurrentSession($campusId);
                if (empty($currentAcademicYear)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                    return $this->sendJsonResponse($data);
                }
                if (!$currentAcademicYear) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Academic Year Is Not Set";
                    return $this->sendJsonResponse($data);
                }

                // Get all exam schedules first
                $allScheduleExams = ExamSchedules::find()
                    ->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])
                    ->andWhere(['campus_id' => $campusId])
                    ->andWhere(['session_id' => $currentAcademicYear])
                    ->all();

                if (empty($allScheduleExams)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Exam Found";
                } else {
                    // Filter unique exams by exam_id
                    $uniqueExams = [];
                    $seenExamIds = [];

                    foreach ($allScheduleExams as $exam) {
                        if (!in_array($exam->exam_id, $seenExamIds)) {
                            $uniqueExams[] = $exam->asJson();
                            $seenExamIds[] = $exam->exam_id;
                        }
                    }

                    // Checking for teacher having multiple subject or single
                    $teacherClassAndSubject = TeacherClassAndSubjects::find()
                        ->select('COUNT(DISTINCT subject_id) as subject_count')
                        ->where(['teacher_user_id' => $user_id])
                        ->scalar();

                    $data['status'] = self::API_OK;
                    $data['details'] = $uniqueExams;

                    if ($teacherClassAndSubject == 0) {
                        $data['single_subject'] = TeacherClassAndSubjects::NO_SUBJECT;
                        $data['subject_id'] = null;
                    } else if ($teacherClassAndSubject > 1) {
                        $data['single_subject'] = TeacherClassAndSubjects::MULTIPLE_SUBJECT;
                        $data['subject_id'] = null;
                    } else {
                        $teacherClassAndSubjects = TeacherClassAndSubjects::find()
                            ->where(['teacher_user_id' => $user_id])
                            ->one();
                        $data['single_subject'] = TeacherClassAndSubjects::SINGLE_SUBJECT;
                        $data['subject_id'] = $teacherClassAndSubjects->subject_id;
                        $data['subject_name'] = $teacherClassAndSubjects->subject->subject_name ?? "";
                    }
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }

    public function actionSubjects()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {

            $classId = $post['class_id'];
            $sectionId = $post['section_id'];

            $examId = $post['exam_id'];
            if (!empty($user_id)) {
                $scheduleExam = ExamSchedules::find()->where(['class_id' => $classId])->andWhere(['section_id' => $sectionId])->andWhere(['exam_id' => $examId])->andWhere(['campus_id' => (new User())->getTeacherCampus($user_id)])->all();
                if (empty($scheduleExam)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Exam Found";
                } else {
                    $sub = [];
                    foreach ($scheduleExam as $exam) {
                        $subject = Subjects::find()->where(['id' => $exam->subject_id])->andWhere(['status' => Subjects::STATUS_ACTIVE])->one();
                        // var_dump($subject);exit;
                        $sub[] = $subject->asJson();
                    }
                    $data['status'] = self::API_OK;
                    $data['details'] = $sub;
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }


    public function actionStudents()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $classId = $post['class_id'];
            $sectionId = $post['section_id'];

            $examId = $post['exam_id'];
            $subjectId = $post['subject_id'];
            $search = isset($post['search_key']) ? $post['search_key'] : "";

            $page = isset($post['subject_id']) ? $post['page'] : 0;
            if (!empty($user_id)) {
                if (empty($search)) {
                    $students = ExamsResult::find()
                        ->where(['class_id' => $classId])
                        ->andWhere(['section_id' => $sectionId])
                        ->andWhere(['exam_id' => $examId])->andWhere(['subject_id' => $subjectId])
                        ->andWhere(['campus_id' => $campusId])
                        ->andWhere(['academic_year_id' => $currentAcademicYear]);
                } else {
                    $students = ExamsResult::find()->joinWith('student as stu')
                        ->where(['exams_result.class_id' => $classId])
                        ->andWhere(['exams_result.section_id' => $sectionId])
                        ->andWhere(['exams_result.exam_id' => $examId])
                        ->andWhere(['exams_result.subject_id' => $subjectId])
                        ->andWhere(['exams_result.campus_id' => $campusId])
                        ->andWhere(['exams_result.academic_year_id' => $currentAcademicYear])
                        ->andFilterWhere(['LIKE', 'stu.student_name', $search]);
                }



                $student = new ActiveDataProvider([
                    'query' => $students,
                    'sort' => [
                        'defaultOrder' => [
                            'created_on' => SORT_DESC,
                        ],
                    ],
                    'pagination' => [
                        'pageSize' => 1000,
                        'page' => $page,
                    ],
                ]);

                $totalRecords = $student->getTotalCount(); // Total number of records
                $pageSize = 10; // Pagination size
                $totalPages = ceil($totalRecords / $pageSize); // Total number of pages

                $data['total_pages'] = (int)$totalPages; // Include total pages in the response data

                $data['current_page'] = (int)$page;
                // var_dump($students->createCommand()->getRawSql());exit;
                if (!empty($student)) {
                    foreach ($student->models as $stu) {
                        $list[] = $stu->asStudentListJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;

                        $exam = Exams::find()->where(['id' => $examId])->one();
                        $class = StudentClass::find()->where(['id' => $classId])->one();
                        $sectionId = ClassSections::find()->where(['id' => $sectionId])->one();
                        $data['exam_name'] = $exam->name_of_exam ?? "";
                        $data['class_name'] = $class->title ?? "";
                        $data['section_name'] = $class->section_name ?? "";
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Data Not Found");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "No Data Found");
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }


    // Update Marks
    public function actionMarkAbsent()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $classId = $post['class_id'] ?? 0;
            $sectionId = $post['section_id'] ?? 0;

            $examId = $post['exam_id'] ?? 0;
            $subjectId = $post['subject_id'] ?? 0;
            $studentId = $post['student_id'] ?? 0;

            if (!empty($user_id)) {
                $students = ExamsResult::find()
                    ->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])
                    ->andWhere(['exam_id' => $examId])
                    ->andWhere(['subject_id' => $subjectId])
                    ->andWhere(['campus_id' => $campusId])
                    ->andWhere(['academic_year_id' => $currentAcademicYear])
                    ->andWhere(['user_id' => $studentId])->one();
                if (empty($students)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Invalid Details");
                    return $this->sendJsonResponse($data);
                } else {


                    $students->attandance = ExamsResult::ABSENT;
                    if ($students->save(false)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = "Student mark as absent";
                    }
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }
    public function actionUpdateMarks()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $classId = $post['class_id'] ?? 0;
            $sectionId = $post['section_id'] ?? 0;

            $examId = $post['exam_id'] ?? 0;
            $subjectId = $post['subject_id'] ?? 0;
            $studentId = $post['student_id'] ?? 0;
            $marksScored = $post['marks_scored'] ?? 0;

            if (!empty($user_id)) {
                $students = ExamsResult::find()
                    ->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])
                    ->andWhere(['exam_id' => $examId])
                    ->andWhere(['subject_id' => $subjectId])
                    ->andWhere(['campus_id' => $campusId])
                    ->andWhere(['academic_year_id' => $currentAcademicYear])
                    ->andWhere(['student_id' => $studentId])->one();
                if (empty($students)) {
                    // var_dump($students);exit;
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Invalid Details");
                    return $this->sendJsonResponse($data);
                } else {
                    if ($students->total_marks == 0 || empty($students->total_marks)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Total Marks for the subject can't be 0 or empty");
                        return $this->sendJsonResponse($data);
                    }

                    if ($marksScored > $students->total_marks) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Scored Marks Can't be Greater Than Total Marks");
                        return $this->sendJsonResponse($data);
                    }

                    $students->marks_scored = $marksScored;


                    if ($marksScored != 0) {
                        $pecentage = ($marksScored / $students->total_marks) * 100;
                        $students->pecentage = number_format($pecentage, 1);
                    } else {
                        $students->pecentage = 0;
                    }
                    $students->status = ExamsResult::MARKS_UPDATED;

                    $students->attandance = ExamsResult::PRESENT;
                    if ($students->save(false)) {
                        $data['status'] = self::API_OK;
                        $data['details']['marks_scored'] = (string)$marksScored;
                        $data['details']['percentage'] = (string)$students->pecentage;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Something went wrong.";
                    }
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }

    // Update Grade and Answersheet

    public function actionUpdateGradeAndAnswersheet()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $classId = $post['class_id'] ?? 0;
            $sectionId = $post['section_id'] ?? 0;

            $examId = $post['exam_id'] ?? 0;
            $subjectId = $post['subject_id'] ?? 0;
            $studentId = $post['student_id'] ?? 0;
            $marksType = $post['marks_type'] ?? 1;
            $gradeGpa = $post['grade_cgpa'] ?? "";
            $answerSheet = $post['answer_sheet'] ?? "";

            if (!empty($user_id)) {
                $students = ExamsResult::find()
                    ->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])
                    ->andWhere(['exam_id' => $examId])
                    ->andWhere(['subject_id' => $subjectId])
                    ->andWhere(['campus_id' => $campusId])
                    ->andWhere(['academic_year_id' => $currentAcademicYear])
                    ->andWhere(['user_id' => $studentId])->one();
                if (empty($students)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Invalid Details");
                    return $this->sendJsonResponse($data);
                } else {

                    if ($marksType == ExamsResult::marks_type_grade) {
                        $students->marks_type = ExamsResult::marks_type_grade;

                        $students->grade = $gradeGpa;
                    } else if ($marksType == ExamsResult::marks_type_gpa) {
                        if ($gradeGpa > 10) {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "CGPA Can'nt be greater than 10.";
                            return $this->sendJsonResponse($data);
                        }

                        $students->marks_type = ExamsResult::marks_type_gpa;

                        $students->cgpa = $gradeGpa;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Invalid marks type.";
                        return $this->sendJsonResponse($data);
                    }
                    $students->marks_sheet = $answerSheet;
                    if ($students->save(false)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = "Data updated succesfully";
                    }
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }

    // View Result Detail


    public function actionViewResultDetail()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $classId = $post['class_id'] ?? 0;
            $sectionId = $post['section_id'] ?? 0;

            $examId = $post['exam_id'] ?? 0;
            $subjectId = $post['subject_id'] ?? 0;
            $studentId = $post['student_id'] ?? 0;

            if (!empty($user_id)) {
                $students = ExamsResult::find()
                    ->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])
                    ->andWhere(['exam_id' => $examId])
                    ->andWhere(['subject_id' => $subjectId])
                    ->andWhere(['campus_id' => $campusId])
                    ->andWhere(['academic_year_id' => $currentAcademicYear])
                    ->andWhere(['user_id' => $studentId])->one();
                if (empty($students)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Invalid Details");
                    return $this->sendJsonResponse($data);
                } else {


                    $data['status'] = self::API_OK;
                    $data['details'] = $students->asStudentListJson($students->user_id);
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }

    public function actionUpdateMarksSheet()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected. Please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            // Fetch divisions for the campus
            $divisions = MarksDivition::find()
                ->andWhere(['campus_id' => $campusId])
                ->all();

            if (empty($divisions)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "No divisions found for the campus.");
            } else {
                // Prepare the division data for response
                $divisionData = [];
                foreach ($divisions as $division) {
                    $divisionData[] = [
                        'id' => $division->id,
                        'short_hand' => $division->short_hand
                    ];
                }

                // Return successful response with divisions data
                $data['status'] = self::API_OK;
                $data['data'] = $divisionData;
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "An error occurred: " . $e->getMessage());
            Yii::error("Exception: " . $e->getMessage(), __METHOD__);
        }

        return $this->sendJsonResponse($data);
    }

    public function actionSaveMarksResults()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        try {
            $campusId = (new User())->getTeacherCampus($user_id);
            $currentAcademicYear = (new Campus())->getCurrentSession($campusId);

            if (empty($currentAcademicYear)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Academic year for the campus is not selected. Please update the academic year.");
                return $this->sendJsonResponse($data);
            }

            $divisionIds = isset($post['marks_devision_id']) ? explode(',', $post['marks_devision_id']) : [];
            $marksScored = isset($post['marks_scored']) ? explode(',', $post['marks_scored']) : [];
            $studentId = $post['student_id'] ?? 0;
            $examid = $post['exam_id'] ?? 0;
            $examresults = ExamsResult::find()->where(['student_id' => $studentId])->andWhere(['exam_id' => $examid])->one();

            Yii::info("Received data: student_id={$studentId}, divisionIds=" . json_encode($divisionIds) . ", marksScored=" . json_encode($marksScored), __METHOD__);

            // Handling the image upload
            $uploadedFile = UploadedFile::getInstanceByName('mark_sheet');
            if ($uploadedFile) {
                $filePath = 'uploads/marksheets/' . uniqid() . '.' . $uploadedFile->extension;
                if ($uploadedFile->saveAs($filePath)) {
                    // Save the file path to the exam_result table
                    $examresults->mark_sheet = $filePath;
                    if (!$examresults->save()) {
                        Yii::error("Failed to save the mark sheet path. Errors: " . json_encode($examresults->errors), __METHOD__);
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Failed to save the mark sheet path.");
                        return $this->sendJsonResponse($data);
                    }
                } else {
                    Yii::error("Failed to save the uploaded mark sheet.", __METHOD__);
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Failed to save the uploaded mark sheet.");
                    return $this->sendJsonResponse($data);
                }
            }

            if (!empty($user_id)) {
                foreach ($divisionIds as $index => $divisionId) {
                    $marks = isset($marksScored[$index]) ? $marksScored[$index] : 0;

                    $record = ScheduledExamMarksDevisionResults::find()
                        ->where([
                            'student_id' => $studentId,
                            'marks_devision_id' => $divisionId
                        ])
                        ->one();
                    $scheduleexamid = ScheduledExamMarksDevision::find()->where(['marks_devision_id' => $divisionId])->andWhere(['exam_schedule_id' => $examresults->exam_scheduled_id])->andWhere(['campus_id' => $campusId])->one();

                    if ($record) {
                        $record->marks_scored = $marks;
                        $record->updated_on = date('Y-m-d H:i:s');
                        $record->update_user_id = Yii::$app->user->id;
                    } else {
                        $record = new ScheduledExamMarksDevisionResults();
                        $record->student_id = $studentId;
                        $record->marks_devision_id = $divisionId;
                        $record->scheduled_exam_devision_id = $scheduleexamid->id;
                        $record->marks_scored = $marks;
                        $record->exam_result_id = $examresults->exams_result_id;
                        $record->exam_schedule_id = $examresults->exam_scheduled_id;

                        $record->status = 1;
                        $record->created_on = date('Y-m-d H:i:s');
                        $record->updated_on = date('Y-m-d H:i:s');
                        $record->update_user_id = Yii::$app->user->id;
                        $record->create_user_id = Yii::$app->user->id;
                    }

                    if (!$record->save()) {
                        Yii::error("Failed to save marks for division ID {$divisionId}. Errors: " . json_encode($record->errors), __METHOD__);

                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Failed to save marks for division ID {$divisionId}. Error: " . json_encode($record->errors));
                        return $this->sendJsonResponse($data);
                    }
                }

                $data['status'] = self::API_OK;
                $data['message'] = Yii::t("app", "Marks and mark sheet saved successfully.");
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "User not authenticated");
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "An error occurred: " . $e->getMessage());
            Yii::error("Exception: " . $e->getMessage(), __METHOD__);
        }

        return $this->sendJsonResponse($data);
    }
}
