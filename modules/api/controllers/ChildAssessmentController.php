<?php

namespace app\modules\api\controllers;


use app\modules\api\controllers\BKController;
use yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
use app\components\AuthSettings;
use app\components\FirebaseNotification;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\User;
use app\modules\admin\models\Auth;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\BusDetails;
use app\modules\admin\models\BusRoute;
use app\modules\admin\models\BusStatus;
use app\modules\admin\models\Category;
use app\modules\admin\models\DriverHasBus;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\StudentAttendanceBus;
use app\modules\admin\models\StudentDetails;
use app\modules\hostelmanagement\models\base\Hostels;
use app\modules\hostelmanagement\models\base\Rooms;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\components\SendOtp;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\Exams;
use app\modules\admin\models\UserOtp;
use app\modules\childassessment\models\base\ChildMerit;
use app\modules\childassessment\models\base\MeritsAssignedToClass;
use app\modules\childassessment\models\base\StudentMeritMarks;
use app\modules\hostelmanagement\models\Rooms as ModelsRooms;
use Exception;

class ChildAssessmentController extends BKController
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
                            'create-hostel',
                            'create-or-update-room',
                            'get-hostel-students',
                            'get-hostel-rooms',
                            'get-hostel-students',
                            'dashboard',
                            'update-profile',
                            'logout',
                            'get-hostel-details',
                            'teacher-has-student',
                            'get-students',
                            'get-student-merits',
                            'update-student-merits',
                            'get-student-merit-details',
                            'exam-list',
                            'check-marks',
                            'check-marks-parent'
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
                            'resend-otp',
                            'verify-otp',
                            'create-hostel',
                            'create-or-update-room',
                            'get-hostel-students',
                            'get-hostel-rooms',
                            'get-hostel-students',
                            'dashboard',
                            'update-profile',
                            'logout',
                            'get-hostel-details',
                            'teacher-has-student',
                            'get-students',
                            'get-student-merits',
                            'update-student-merits',
                            'get-student-merit-details',
                            'exam-list',
                            'check-marks',
                            'check-marks-parent'



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



    // public function actionGetStudents()
    // {
    //     $data = [];
    //     $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
    //     $auth = new AuthSettings();
    //     $post = Yii::$app->request->post();
    //     $user_id = $auth->getAuthSession($headers);

    //     if (!empty($user_id)) {
    //         try {
    //             $campus_id =  User::getCampusesByUser($user_id);
    //             if (empty($campus_id)) {
    //                 $data['status'] = self::API_NOK;
    //                 $data['error'] = 'Campus not found for the user';
    //             }
    //             $student_details = StudentDetails::find()->where(['campus_id' => $campus_id])->all();
    //             if (empty($student_details)) {
    //                 $data['status'] = self::API_NOK;
    //                 $data['error'] = "No Students Found";
    //                 return $this->sendJsonResponse($data);
    //             } else {
    //                 $list = [];
    //                 foreach ($student_details as $results) {
    //                     $list[] = $results->asJson();
    //                 }
    //                 if (!empty($list)) {
    //                     $data['status'] = self::API_OK;
    //                     $data['details'] = $list;
    //                 }
    //             }
    //         } catch (Exception $e) {
    //             $data['status'] = self::API_NOK;
    //             $data['error'] = $e->getMessage();
    //         }
    //     } else {
    //         $data['status'] = self::API_NOK;
    //         $data['error'] = Yii::t("app", "Session Not Found");
    //     }

    //     return $this->sendJsonResponse($data);
    // }


    public function actionGetStudentMerits()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        // $class_id = $post['class_id'];
        // $section_id = $post['section_id'];
        $exam_id = $post['exam_id'];

        if (!empty($user_id)) {
            try {

                $teacherDetails = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (empty($teacherDetails)) {
                    $data = [
                        'status' => self::API_NOK,
                        'message' => 'Teacher details not found',
                    ];
                    return $this->sendJsonResponse($data);
                }

                $child_merits = MeritsAssignedToClass::find()->andWhere(['campus_id' => $teacherDetails->campus_id])->andWhere(['class_id' => $teacherDetails->class_id])->andWhere(['section_id' => $teacherDetails->section_id])->andWhere(['exam_id' => $exam_id])->all();

                if (empty($child_merits)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Merits";
                    return $this->sendJsonResponse($data);
                }
                if (!empty($child_merits)) {
                    $list = [];
                    foreach ($child_merits as $results) {
                        $list = $results->asJsonForChildMerits();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Merits Found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No user found";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionDashboard()
    {

        $data = [];
        $dd = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $post = Yii::$app->request->post();
        $user_id = $auth->getAuthSession($headers);
        $filter = isset($post['filter']) ? $post['filter'] : '';
        $page = isset($post['page']) ? $post['page'] : 0;
        if (!empty($user_id)) {
            try {
                $hostel = Hostels::find()->where(['warden_id' => $user_id])->one();
                if (empty($hostel)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "No Hostel Assigned");
                    return $this->sendJsonResponse($data);
                } else {
                    $hostellars = Hostellers::find()->where(["hostel_id" => $hostel->id])->count();
                    $roomCount = ModelsRooms::find()->where(["hostel_id" => $hostel->id])->count();

                    $dd = [
                        'hostel_id' => $hostel->id,
                        'hostel_name' => $hostel->name,
                        'hostel_type' => $hostel->type_id,
                        'hostellars_count' => $hostellars,
                        'hostel_rooms_count' => $roomCount,
                    ];
                    $data['status'] = self::API_OK;

                    $data['details'] = $dd;
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }


    public function actionUpdateProfile()
    {
        $data = [];
        $param = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            try {
                if (!empty($post)) {
                    $model = User::find()->where(['id' => $user_id])->andWhere(['user_role' => User::ROLE_WARDEN])->one();

                    if (!empty($model)) {
                        $model->first_name =  $post['User']['first_name'];
                        if (!empty($post['User']['profile_image'])) {
                            // $profile_image = $model->profileImage($post['User']['profile_image'], $model->first_name);
                            $model->profile_image = $post['User']['profile_image'];
                        }
                        if (!empty($post['User']['email'])) {
                            $model->email = $post['User']['email'];
                        }
                        // $model->username = $model->email;

                        if ($model->save(false)) {

                            $data['status'] = self::API_OK;
                            $data['details'] = $model->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = Yii::t("app", 'Something Went Wrong');
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "User Not Found");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Data Not Posted");
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "User Not Found");
        }

        return $this->sendJsonResponse($data);
    }

    public function actionLogout()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        //$userID = Yii::$app->request->post();
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $model = AuthSession::find()->where(['create_user_id' => $user_id])->one();
            if (!empty($model)) {
                $model->delete();
                if (Yii::$app->user->logout(false)) {
                    $data['status'] = self::API_OK;
                }
                $data['details'] = array("Successfully Logged Out");
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = array();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = ["User Not Found"];
        }
        return $this->sendJsonResponse($data);
    }


    public function actionTeacherHasStudent()
    {
        $data = [];
        $headers = isset(Yii::$app->request->headers['auth_code']) ? Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $post = Yii::$app->request->post();
        $user_id = $auth->getAuthSession($headers);
        $page = isset($post['page']) ? $post['page'] : 0;
        $search = isset($post['search']) ? $post['search'] : '';

        if (!empty($user_id)) {
            try {
                $teacherDetails = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                // var_dump($teacherDetails->class_id);
                // var_dump($teacherDetails->campus_id);
                // exit;
                if (!empty($teacherDetails)) {
                    $query = StudentDetails::find()
                        ->where(['student_class_id' => $teacherDetails->class_id, 'section_id' => $teacherDetails->section_id]);
                    if (!empty($search)) {
                        $query->andWhere(['like', 'student_name', $search]);
                        // You can add more fields to search here as needed
                    }
                    $students = new ActiveDataProvider([
                        'query' => $query,
                        'sort' => [
                            'defaultOrder' => [
                                'id' => SORT_DESC,
                            ],
                        ],
                        'pagination' => [
                            'pageSize' => 10,
                            'page' => $page,
                        ],
                    ]);

                    $totalPages = ceil($students->getTotalCount() / 20); // Calculate total pages

                    $data['total_pages'] = (int)$totalPages; // Include total pages in the response data
                    $data['current_page'] = (int)$page; // Include current page in the response data

                    if (!empty($students)) {
                        foreach ($students->models as $student) {
                            $list[] = $student->asJsonStudentDetails();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = Yii::t("app", "Student Data Not Found");
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Student Data Not Found");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Teacher Details Not Found");
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }

    public function actionUpdateStudentMerits()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $merit_id = $post['merit_id'];
        $student_id = $post['student_id'];
        $marks = $post['marks'];
        $exam_id = $post['exam_id'];
        $remarks = $post['remarks'];

        if (!empty($user_id)) {
            try {

                $teacherDetails = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (empty($teacherDetails)) {
                    $data = [
                        'status' => self::API_NOK,
                        'message' => 'Teacher details not found',
                    ];
                    return $this->sendJsonResponse($data);
                }
                $child_merits = StudentMeritMarks::find()->where(['child_merit_id' => $merit_id])->andWhere(['student_details_id' => $student_id])->andWhere(['exam_id' => $exam_id])->one();
                if (empty($child_merits)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Merits for the student found";
                    return $this->sendJsonResponse($data);
                }
                if (!empty($child_merits)) {
                    $child_merits->academic_year_id = $child_merits->academic_year_id;
                    $child_merits->campus_id = $child_merits->academic_year_id;
                    $child_merits->child_merit_id  = $child_merits->child_merit_id;
                    $child_merits->max_marks   = $child_merits->max_marks;
                    $child_merits->teacher_details_id = $teacherDetails->id;
                    $child_merits->student_details_id = $child_merits->student_details_id;
                    $child_merits->marks_scored = $marks;
                    $child_merits->remarks = $remarks;

                    if ($child_merits->save(false)) {
                        $stu_avg  = StudentMeritMarks::find()->andWhere(['student_details_id' => $student_id])->all();
                        $count = 0;
                        $avg = 0;
                        $total_secured_marks = 0;
                        foreach ($stu_avg as $marks) {
                            // var_dump($marks->marks_scored);
                            $total_secured_marks = $marks->marks_scored + $total_secured_marks;
                            $count = $count + 1;
                        }

                        $avg =  $total_secured_marks / $count;
                        // // var_dump($avg);
                        // exit;
                        $data['status'] = self::API_OK;
                        $data['average'] = $avg;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Marks Updation Failed";
                        return $this->sendJsonResponse($data);
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Merits Found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No user found";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionGetStudentMeritDetails()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $student_id = isset($post['student_id']) ? $post['student_id'] : null; // Ensure student_id is set
        $exam_id = isset($post['exam_id']) ? $post['exam_id'] : null; // Ensure student_id is set

        if (!empty($user_id)) {
            try {
                $teacherDetails = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacherDetails)) {
                    if ($student_id !== null) {
                        $query = StudentMeritMarks::find()->where(['student_details_id' => $student_id])->andWhere(['exam_id' => $exam_id])->all();

                        if (!empty($query)) {
                            foreach ($query as $results) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $results->asJsonForStudentMerit();
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Unable to fetch data";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Invalid Student ID");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Teacher Details Not Found");
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }




    public function actionExamList()
    {
        $data = [];
        $headers = isset(Yii::$app->request->headers['auth_code']) ?
            Yii::$app->request->headers['auth_code'] :
            Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        if (!empty($user_id)) {
            try {
                $teacherDetail = TeacherDetails::find()->where(['user_id' => $user_id])->one();

                if ($teacherDetail === null) {
                    // If teacherDetail is null, check for student details
                    if (isset($post['student_id'])) {
                        $studentDetail = StudentDetails::find()->where(['id' => $post['student_id']])->one();
                        if ($studentDetail !== null) {
                            $classId = $studentDetail->student_class_id;
                            $sectionId = $studentDetail->section_id;
                        } else {
                            throw new \Exception('Student details not found');
                        }
                    } else {
                        throw new \Exception('Student ID not provided');
                    }
                } else {
                    // If teacherDetail is found, use teacher's class and section
                    $classId = $teacherDetail->class_id;
                    $sectionId = $teacherDetail->section_id;
                }

                $studentMeritSchedules = MeritsAssignedToClass::find()->where(['class_id' => $classId])
                    ->andWhere(['section_id' => $sectionId])->groupBy('exam_id')->all();

                if (empty($studentMeritSchedules)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Merit Not Assigned");
                } else {
                    $examsList = [];
                    foreach ($studentMeritSchedules as $studentMeritSchedule) {
                        $exam = Exams::findOne($studentMeritSchedule->exam_id);
                        if ($exam !== null) {
                            $examsList[] = ['id' => $exam->id, 'name' => $exam->name_of_exam];
                        }
                    }
                    $data['status'] = self::API_OK;
                    $data['details']['exam'] = $examsList;
                }
            } catch (\Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }




    public function actionCheckMarks()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $student_id = isset($post['student_id']) ? $post['student_id'] : null; // Ensure student_id is set
        $exam_id = isset($post['exam_id']) ? $post['exam_id'] : null; // Ensure student_id is set

        if (!empty($user_id)) {
            try {
                $merit = [];
                $dd = [];
                $teacherDetails = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                $studentMeritMarks = StudentMeritMarks::find()->where(['exam_id' => $exam_id])->andWhere(['student_details_id' => $student_id])->all();
                $count = 0;
                $avg = 0;
                $total_secured_marks = 0;
                foreach ($studentMeritMarks as $studentMeritMark) {
                    $merit['merit_id'] = $studentMeritMark->child_merit_id;
                    $merit['merit_name'] = $studentMeritMark->childMerit->name;
                    $merit['max_marks'] = $studentMeritMark->max_marks;
                    $merit['marks_scored'] = $studentMeritMark->marks_scored;
                    $merit['remarks'] = $studentMeritMark->remarks;
                    $dd[] = $merit;

                    $total_secured_marks = $studentMeritMark->marks_scored + $total_secured_marks;
                    $count = $count + 1;
                }
                if ($count == 0) {
                    $avg = 0;
                } else {
                    $avg =  $total_secured_marks / $count;
                }

                if (!empty($dd)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $dd;
                    $data['total'] = $avg;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not Found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }

    // parent check marks

    public function actionCheckMarksParent()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $student_id = isset($post['student_id']) ? $post['student_id'] : null; // Ensure student_id is set
        $exam_id = isset($post['exam_id']) ? $post['exam_id'] : null; // Ensure student_id is set

        if (!empty($user_id)) {
            try {
                $merit = [];
                $dd = [];
                $studentMeritMarks = StudentMeritMarks::find()->where(['exam_id' => $exam_id])->andWhere(['student_details_id' => $student_id])->all();
                $count = 0;
                $avg = 0;
                $total_secured_marks = 0;
                foreach ($studentMeritMarks as $studentMeritMark) {
                    $merit['merit_id'] = $studentMeritMark->child_merit_id;
                    $merit['merit_name'] = $studentMeritMark->childMerit->name;
                    $merit['max_marks'] = $studentMeritMark->max_marks;
                    $merit['marks_scored'] = $studentMeritMark->marks_scored;
                    $dd[] = $merit;

                    $total_secured_marks = $studentMeritMark->marks_scored + $total_secured_marks;
                    $count = $count + 1;
                }


                $avg =  $total_secured_marks / $count;
                if (!empty($dd)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $dd;
                    $data['total'] = $avg;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not Found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "Session Not Found");
        }
        return $this->sendJsonResponse($data);
    }
}
