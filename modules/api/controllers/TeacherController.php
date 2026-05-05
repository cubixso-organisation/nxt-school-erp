<?php

namespace app\modules\api\controllers;

use app\modules\admin\models\base\TeacherAttenddence;
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
use app\modules\admin\models\Campus;
use app\modules\api\controllers\BKController;

use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\StudentDairy;
use app\modules\admin\models\StudentHasAssessment;
use app\modules\admin\models\StudentHasDairy;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\staffmanagement\models\StaffDetails;
use app\modules\staffmanagement\models\StaffSalary;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\TemporaryAssignTeacher;
use kartik\mpdf\Pdf;
use Exception;
use app\components\SendOtp;
use app\modules\admin\models\AttendanceSettings;
use app\modules\admin\models\base\NoticeBoards as BaseNoticeBoards;
use app\modules\admin\models\base\StudentClassAttendance as BaseStudentClassAttendance;
use app\modules\admin\models\base\StudentFaces;
use app\modules\admin\models\Exams;
use app\modules\admin\models\ExamsResult;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\NoticeBoards;
use app\modules\admin\models\SpecialDays;
use app\modules\admin\models\StudentNoticeBoards;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentHasNotice;
use app\modules\admin\models\UserOtp;
use app\modules\admin\models\WebSetting;

class TeacherController extends BKController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:58382', 'https://web.estuden t.tech'],
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
                            'class-teacher',
                            'time-table',
                            'write-dairy',
                            'check-dairy',
                            'check-assessment',
                            'student-class-attendance-start',
                            'student-class-attendance-list',
                            'daily-attendance',
                            'next-class',
                            'leave-requests',
                            'accept-leave-request',
                            'reject-leave-request',
                            'class-and-section',
                            'submit-day-attendance',
                            'today-birthdays',
                            'list-assessment',
                            'write-assessment',
                            'dairy-list',
                            'student-list-by-class-and-section',
                            'class-wise-attendance',
                            'class-wise-dairy',
                            'write-notice',
                            'notice-board',
                            'today-birthdays-class-wise',
                            'special-days',
                            'class-wise-time-table',
                            'class-wise-assignment',
                            'check-academic-year',
                            'student-search',
                            'get-subjects-by-section-id',
                            'my-notification',
                            'mark-attendance',
                            'exams',
                            'upload-mark-sheet',
                            'view-exam-result',
                            'student-list-by-classwise',
                            'exam-result-by-student-id',
                            'check-out',
                            'logout',
                            'teacher-payroll-details',
                            'test-sms',
                            'notice-details',
                            'noti-count',
                            'clear-notifications',
                            'student-details-by-id'


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
                            'class-teacher',
                            'time-table',
                            'write-dairy',
                            'check-dairy',
                            'check-assessment',
                            'student-class-attendance-start',
                            'student-class-attendance-list',
                            'daily-attendance',
                            'next-class',
                            'leave-requests',
                            'accept-leave-request',
                            'reject-leave-request',
                            'class-and-section',
                            'submit-day-attendance',
                            'today-birthdays',
                            'write-assessment',
                            'list-assessment',
                            'dairy-list',
                            'student-list-by-class-and-section',
                            'class-wise-attendance',
                            'write-notice',
                            'notice-board',
                            'today-birthdays-class-wise',
                            'special-days',
                            'class-wise-dairy',
                            'class-wise-time-table',
                            'class-wise-assignment',
                            'check-academic-year',
                            'student-search',
                            'get-subjects-by-section-id',
                            'my-notification',
                            'mark-attendance',
                            'exams',
                            'upload-mark-sheet',
                            'view-exam-result',
                            'student-list-by-classwise',
                            'exam-result-by-student-id',
                            'check-out',
                            'logout',
                            'teacher-payroll-details',
                            'test-sms',
                            'notice-details',
                            'noti-count',
                            'clear-notifications',
                            'student-details-by-id'







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
    public function actionCheck()
    {
        $data = [];

        $headers = getallheaders();
        $auth_code = isset($headers['auth_code']) ? $headers['auth_code'] : null;
        if ($auth_code == null) {
            $auth_code = \Yii::$app->request->get('auth_code');
        }
        if ($auth_code) {
            $auth_session = AuthSession::find()->where([
                'auth_code' => $auth_code,
            ])->one();
            if ($auth_session) {
                $user = $auth_session->createUser;
                $data['status'] = self::API_OK;
                $data['detail'] = $user->asJson();
                if (isset($_POST['AuthSession'])) {
                    $auth_session->device_token = $_POST['AuthSession']['device_token'];
                    if ($auth_session->save()) {
                        $data['auth_session'] = Yii::t("app", 'Auth Session updated');
                    } else {
                        $data['error'] = $auth_session->flattenErrors;
                    }
                }
            } else {
                $data['error'] = Yii::t("app", 'session not found');
            }
        } else {
            $data['error'] = Yii::t("app", 'Auth code not found');
            $data['auth'] = isset($auth_code) ? $auth_code : '';
        }

        return $this->sendJsonResponse($data);
    }

    public function actionSendOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::role_teacher])->one();
            $webSetting = new WebSetting();

            $templateId = $webSetting->getSettingBykey('sms_template_id');
            $apiKey = $webSetting->getSettingBykey('sms_api_key');
            $senderId = $webSetting->getSettingBykey('sender_id');
            if (!empty($user_check)) {

                $otp = rand(1111, 9999);
                $key = $apiKey;
                // $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
                $sms = 'Dear Customer, Your OTP for Estudent is ' . $otp . '. Please do not share OTP with anyone. Regards, EStudent';
                $sms_url = urlencode($sms);
                $template_id = $templateId;
                // $template_id = '1707168312544700319';
                $sender = $senderId;
                $route = 7;
                $SendOtpData =   new SendOtp();
                $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);

                // var_dump($send_otp);exit;
                if (strlen($send_otp) > 4) {

                    $date = date('Y-m-d H:i:s');
                    $user_otp  = new UserOtp();
                    $user_otp->contact_number = $contact_no;
                    $user_otp->otp = $otp;
                    $user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +5 minutes"));
                    $user_otp->messageid = $send_otp;
                    $user_otp->status = UserOtp::STATUS_PENDING;
                    $user_otp->save(false);


                    $data['status'] = self::API_OK;
                    $data['details'] = $send_otp;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = $send_otp;
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "User details Not found");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No data posted");
        }
        return $this->sendJsonResponse($data);
    }

    public function actionResendOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::role_teacher])->one();

            if (!empty($user_check)) {

                $otp = rand(1111, 9999);
                $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
                $sms = 'Dear Teacher, ' . $otp . ' is the OTP for login into Teacher App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
                $sms_url = urlencode($sms);
                $template_id = '1707168312544700319';
                $sender = 'DEVCIT';
                $route = 7;
                $SendOtpData =   new SendOtp();
                $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);
                if (strlen($send_otp) > 4) {

                    $date = date('Y-m-d H:i:s');
                    $user_otp  = new UserOtp();
                    $user_otp->contact_number = $contact_no;
                    $user_otp->otp = $otp;
                    $user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +5 minutes"));
                    $user_otp->messageid = $send_otp;
                    $user_otp->status = UserOtp::STATUS_PENDING;
                    $user_otp->save(false);


                    $data['status'] = self::API_OK;
                    $data['details'] = $send_otp;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = $send_otp;
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "User details Not found");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No data posted");
        }
        return $this->sendJsonResponse($data);
    }

    public function actionVerifyOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();
        try {
            if (!empty($post)) {
                $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
                $otp_code = $post['otp_code'];

                if (empty($contact_no)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Contact Details Not Found");
                    return $this->sendJsonResponse($data);
                }
                if (empty($post['device_token'])) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Device Token Can't be empty");
                    return $this->sendJsonResponse($data);
                }


                $setting = new WebSetting();
                $numbers = $setting->getSettingByKey('teacher');
                $explodeNumber  = explode(',', $numbers);




                if (in_array($post['contact_no'], $explodeNumber)) {
                    $otp_match = true;
                } else {

                    $user_otp = UserOtp::find()->where(['contact_number' => $contact_no])->andWhere(['otp' => $otp_code])->one();
                    if (!empty($user_otp)) {
                        $now_date_time = date('Y-m-d H:i:s');
                        $expire_date_and_time = $user_otp->expire_date_and_time;

                        if (strtotime($expire_date_and_time) > strtotime($now_date_time)) {
                            $otp_match = true;
                            $user_otp->status = UserOtp::STATUS_VERIFIED;
                            $user_otp->save(false);
                        } else {
                            $otp_match = false;
                            $msg = 'otp expired';
                        }
                    } else {
                        $otp_match = false;
                        $msg = 'otp verification failed';
                    }
                }
                // var_dump($otp_match);
                // exit;







                if ($otp_match  === true) {
                    //check user details exist


                    $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::role_teacher])->one();



                    $teacher_details = TeacherDetails::find()->where(['user_id' => $user_check->id])->one();
                    // var_dump($teacher_details);exit;


                    if (!empty($teacher_details)) {
                        $providerId = User::role_teacher;
                        $number =  $contact_no;
                        $auth_id = $number;
                        $auth = Auth::find()->where(['source' => $providerId])
                            ->andWhere(['source_id' => $post['contact_no']])
                            ->one();

                        if ($auth) {

                            $user = $auth->user;
                            $user->device_token = !empty($post['device_token']) ? $post['device_token'] : '';
                            $user->device_type = !empty($post['device_type']) ? $post['device_type'] : '';
                            Yii::$app->user->login($user);
                            $data['status'] = self::API_OK;
                            $data['details'] = $user;
                            $data['auth_code'] = AuthSession::newSession($user)->auth_code;
                        } else {
                            $auth = new Auth();
                            $auth->user_id = $user_check->id;
                            $auth->source = $providerId;
                            $auth->source_id = $auth_id;
                            if ($auth->save(false)) {
                                $user = $auth->user;
                                $user->device_token = $post['device_token'];
                                $user->device_type = $post['device_type'];
                                Yii::$app->user->login($user);
                                $data['status'] = self::API_OK;
                                $data['details'] = $user;
                                $data['auth_code'] = AuthSession::newSession($user)->auth_code;
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Getting Error here";
                            }
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "You Not Have Access Contact To School Admin");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", $msg);
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "No  Data Posted");
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }

    public function actionCheckAcademicYear()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {

            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

            if (!empty($teacher_details)) {
                $academic_year_id = $teacher_details->getAcademicId();
                return $academic_year_id;
            }
        }

        return $this->sendJsonResponse($data);
    }

    public function actionTeacherProfile()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $data['status'] = self::API_OK;
                $data['details'] = $teacher_details->asJson();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionClassTeacher()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {

                $data['status'] = self::API_OK;
                $data['details'] = $teacher_details->asJson();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionTimeTable()
    {


        $data = [];
        $temp = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';
                $formattedDate = !empty($date) ? date('Y-m-d', strtotime($date)) : '';

                if (!empty($date)) {

                    $day_id = date('l', strtotime($date));
                    if (!(new Campus())->getCurrentSession($teacher_details->campus_id)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Session for the campus is not set";
                        return $this->sendJsonResponse($data);
                    }

                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])->andWhere(['teacher_details_id' => $teacher_details->id])->andWhere(['academic_year_id' => (new Campus())->getCurrentSession($teacher_details->campus_id)])->all();
                    if (!empty($subject_timetable)) {
                        foreach ($subject_timetable as $subject_timetable_data) {
                            $list[] = $subject_timetable_data->asJson();
                        }

                        // check for temporary class for today


                        $temporaryAssinment = TemporaryAssignTeacher::find()->where(['replaced_teacher_detail_id' => $teacher_details->id])->andWhere(['date' => $formattedDate])->all();

                        // var_dump($temporaryAssinment);exit;
                        if (!empty($temporaryAssinment)) {
                            foreach ($temporaryAssinment as $assingment) {
                                $temp[] = $assingment->asTempAssignJson();
                            }
                        }
                        $mergeResponse = array_merge($list, $temp);


                        if (!empty($mergeResponse)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $mergeResponse;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Time table data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Date required";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionClassWiseTimeTable()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';
                if (!empty($date)) {
                    $class_id = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
                    $section_id  = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
                    $day_id = date('l', strtotime($date));
                    if (!(new Campus())->getCurrentSession($teacher_details->campus_id)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Session for the campus is not set";
                        return $this->sendJsonResponse($data);
                    }


                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['academic_year_id' => (new Campus())->getCurrentSession($teacher_details->campus_id)])
                        ->all();
                    if (!empty($subject_timetable)) {
                        foreach ($subject_timetable as $subject_timetable_data) {
                            $list[] = $subject_timetable_data->asJson();
                        }

                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Time table data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Date required";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionWriteDairy()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        // var_dump($user_id);
        // exit;
        if (!empty($user_id)) {


            try {



                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

                if (!empty($teacher_details)) {
                    $dairy = !empty($post['dairy']) ? $post['dairy'] : '';
                    $remarks = !empty($post['remarks']) ? $post['remarks'] : '';
                    $submission_date = !empty($post['submission_date']) ? $post['submission_date'] : '';
                    $document = !empty($post['document']) ? $post['document'] : '';
                    $subject_timetable_id = !empty($post['subject_timetable_id']) ? $post['subject_timetable_id'] : '';
                    $send_all = !empty($post['send_all']) ? $post['send_all'] : 'false';
                    $selected_student_id = !empty($post['selected_student_id']) ? $post['selected_student_id'] : '';
                    $file_type = !empty($post['file_type']) ? $post['file_type'] : '';


                    $campus_id  = $teacher_details->campus_id;
                    $teacher_details_id  = $teacher_details->id;
                    $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();

                    $academic_year_id = $subject_timetable->academic_year_id;
                    $class_id  = $subject_timetable->class_id;
                    $section_id   = $subject_timetable->section_id;
                    $subject_id    = $subject_timetable->subject_id;
                    $date = date('Y-m-d');
                    $StudentDairyCheck = StudentDairy::find()->where(['teacher_details_id' => $teacher_details_id])
                        ->andWhere(['subject_timetable_id' => $subject_timetable_id])
                        ->andWhere(['academic_year_id' => $academic_year_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['subject_id' => $subject_id])
                        ->andWhere(['created_on' => $date])

                        ->one();

                    if (!empty($StudentDairyCheck)) {
                        $student_dairy =    StudentDairy::find()->where(['id' => $StudentDairyCheck->id])->one();
                    } else {
                        $student_dairy =  new  StudentDairy();
                    }
                    $student_dairy->campus_id  = $campus_id;
                    $student_dairy->teacher_details_id    = $teacher_details_id;
                    $student_dairy->subject_timetable_id  = $subject_timetable_id;
                    $student_dairy->academic_year_id  = $academic_year_id;
                    $student_dairy->class_id   = $class_id;
                    $student_dairy->section_id  = $section_id;
                    $student_dairy->subject_id  = $subject_id;
                    $student_dairy->dairy  = $dairy;
                    $student_dairy->file_type  = $file_type;

                    $student_dairy->remarks  = $remarks;
                    $student_dairy->submission_date  = $submission_date;
                    $student_dairy->document  = $document;
                    $student_dairy->status  = StudentDairy::STATUS_ACTIVE;
                    if ($student_dairy->save(false)) {



                        if ($send_all == 'true' || $send_all == true) {

                            $student_details = StudentDetails::find()->where(['student_class_id' => $class_id])->andWhere(['section_id' => $section_id])->all();
                            if (!empty($student_details)) {
                                foreach ($student_details as $student_details_id) {
                                    $student_id  = $student_details_id->id;
                                    $student_has_dairy_check  =   StudentHasDairy::find()->where(['student_id' => $student_id])->andWhere(['student_dairy_id' => $student_dairy->id])->one();
                                    if (!empty($student_has_dairy_check)) {
                                        $student_has_dairy =   StudentHasDairy::find()->where(['id' => $student_has_dairy_check->id])->one();
                                    } else {
                                        $student_has_dairy =  new StudentHasDairy();
                                    }

                                    $student_has_dairy->student_id  = $student_id;
                                    $student_has_dairy->student_dairy_id   = $student_dairy->id;
                                    $student_has_dairy->date   = date('Y-m-d');
                                    $student_has_dairy->is_read   = StudentHasDairy::is_read_no;
                                    if (empty($student_has_dairy->status)) {
                                        $student_has_dairy->status   = StudentHasDairy::STATUS_PENDING;
                                    }
                                    $student_has_dairy->save(false);

                                    $student_name = !empty($student_has_dairy->student->student_name) ? $student_has_dairy->student->student_name : 'No Name';
                                    $subject_name =   !empty($student_dairy->subject->subject_name) ? $student_dairy->subject->subject_name : '';

                                    $title = 'New Diary';
                                    $body = "Dear parent Diary Created for $student_name subject $subject_name";
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $student_has_dairy->student->parent->user_id, $title, $body, $type, 'student_dairy', $student_has_dairy->student_dairy_id);
                                }
                            }
                        } else {
                            if (!empty($selected_student_id)) {
                                $selected_student_id_arr = explode(',', $selected_student_id);
                                if (!empty($selected_student_id_arr)) {
                                    foreach ($selected_student_id_arr as $student_id) {
                                        $student_has_dairy_check  =   StudentHasDairy::find()->where(['student_id' => $student_id])->andWhere(['student_dairy_id' => $student_dairy->id])->one();
                                        if (!empty($student_has_dairy_check)) {
                                            $student_has_dairy =   StudentHasDairy::find()->where(['id' => $student_has_dairy_check->id])->one();
                                        } else {
                                            $student_has_dairy =  new StudentHasDairy();
                                        }

                                        $student_has_dairy->student_id  = $student_id;
                                        $student_has_dairy->student_dairy_id   = $student_dairy->id;
                                        $student_has_dairy->date   = date('Y-m-d');
                                        $student_has_dairy->is_read   = StudentHasDairy::is_read_no;
                                        if (empty($student_has_dairy->status)) {
                                            $student_has_dairy->status   = StudentHasDairy::STATUS_PENDING;
                                        }
                                        $student_has_dairy->save(false);


                                        $student_name = !empty($student_has_dairy->student->student_name) ? $student_has_dairy->student->student_name : 'No Name';
                                        $subject_name =   !empty($student_dairy->subject->subject_name) ? $student_dairy->subject->subject_name : '';

                                        $title = 'New Diary';
                                        $body = "Dear parent Diary Created for $student_name subject $subject_name";
                                        $type = '';
                                        Yii::$app->notification->UserNotification('', $student_has_dairy->student->parent->user_id, $title, $body, $type, 'student_dairy', $student_has_dairy->student_dairy_id);
                                    }
                                }
                            }
                        }





                        $data['status'] = self::API_OK;
                        $data['details'] = $student_dairy->asJson();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Dairy data not updated";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Profile details not found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }






    public function actionCheckDairy()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $id = !empty($post['id']) ? $post['id'] : '';
                if (!empty($id)) {
                    $student_dairy = StudentDairy::find()->where(['id' => $id])->one();
                    if (!empty($student_dairy)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $student_dairy->asJson();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Dairy not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "id not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }






    public function actionCheckAssessment()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $id = !empty($post['id']) ? $post['id'] : '';
                if (!empty($id)) {
                    $StudentAssessment = StudentAssessment::find()->where(['id' => $id])->one();
                    if (!empty($StudentAssessment)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $StudentAssessment->asJsonList();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Assignment not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "id not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Profile details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionStudentClassAttendanceStart(): array
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $subject_timetable_id = !empty($post['subject_timetable_id']) ? $post['subject_timetable_id'] : '';
                $class_id = !empty($post['class_id']) ? $post['class_id'] : '';
                $subject_id = !empty($post['subject_id']) ? $post['subject_id'] : '';
                $section_id = !empty($post['section_id']) ? $post['section_id'] : '';
                $date = !empty($post['date']) ? $post['date'] : '';


                if (!empty($subject_timetable_id)) {
                    $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();



                    if (!empty($subject_timetable)) {
                        $class_id  = $subject_timetable->class_id;
                        $section_id   = $subject_timetable->section_id;
                        $subject_id   = $subject_timetable->subject_id;


                        $academic_year_id    = $subject_timetable->academic_year_id;
                        $subject_group_subject_id = $subject_timetable->subject_group_subject_id;
                        $subject_group_subjects = SubjectGroupSubjects::find()->where(['id' => $subject_group_subject_id])->one();
                        $subject_group_id = $subject_group_subjects->subject_group_id;
                        $student_details = StudentDetails::find()->where(['student_class_id' => $class_id])->andWhere(['section_id' => $section_id])->all();
                        if (!empty($student_details)) {
                            foreach ($student_details as $student_details_data) {
                                $student_class_attendance_check =   StudentClassAttendance::find()->where(['student_id' => $student_details_data->id])
                                    ->andWhere(['teacher_id' => $teacher_details->id])
                                    ->andWhere(['subject_timetable_id' => $subject_timetable_id])
                                    ->andWhere(['date' => date('Y-m-d')])->one();
                                if (empty($student_class_attendance_check)) {
                                    $student_class_attendance = new StudentClassAttendance();
                                    $student_class_attendance->status = StudentClassAttendance::STATUS_UNMARKED;
                                } else {
                                    $student_class_attendance =   StudentClassAttendance::find()->where(['id' => $student_class_attendance_check->id])->one();
                                    if (in_array($student_class_attendance->status, [StudentClassAttendance::STATUS_PRESENT, StudentClassAttendance::STATUS_ABSENT])) {
                                        continue;
                                    }
                                }


                                $student_class_attendance->student_id   = $student_details_data->id;
                                $student_class_attendance->teacher_id    = $teacher_details->id;
                                $student_class_attendance->subject_timetable_id    = $subject_timetable_id;
                                $student_class_attendance->academic_year_id  = $academic_year_id;
                                $student_class_attendance->subject_group_id   = $subject_group_id;
                                $student_class_attendance->period    = $subject_timetable->period;
                                $student_class_attendance->subject_id    = $subject_id;
                                $student_class_attendance->date    = date('Y-m-d');
                                if (empty($student_class_attendance->status)) {
                                    if ($student_details_data->status == StudentDetails::STATUS_LEAVE) {
                                        $student_class_attendance->status   = StudentClassAttendance::STATUS_LEAVE;
                                    }
                                }
                                $student_class_attendance->save(false);
                            }
                            $data['status'] = self::API_OK;
                            $data['details'] = "Attendance generated success";
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "student details not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table not found";
                    }
                } else if (!empty($date) && !empty($class_id) && !empty($section_id)) {
                    $day_id = date('l', strtotime($date));
                    $academic_year_id = $teacher_details->getAcademicId();
                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['teacher_details_id' => $teacher_details->id])
                        ->andWhere(['academic_year_id' => $academic_year_id])->one();



                    if (!empty($subject_timetable)) {
                        $class_id  = $subject_timetable->class_id;
                        $section_id   = $subject_timetable->section_id;
                        $subject_id   = $subject_timetable->subject_id;


                        $academic_year_id    = $subject_timetable->academic_year_id;
                        $subject_group_subject_id = $subject_timetable->subject_group_subject_id;
                        $subject_group_subjects = SubjectGroupSubjects::find()->where(['id' => $subject_group_subject_id])->one();
                        $subject_group_id = $subject_group_subjects->subject_group_id;
                        $student_details = StudentDetails::find()->where(['student_class_id' => $class_id])->andWhere(['section_id' => $section_id])->all();
                        if (!empty($student_details)) {
                            foreach ($student_details as $student_details_data) {
                                $student_class_attendance_check =   StudentClassAttendance::find()->where(['student_id' => $student_details_data->id])
                                    ->andWhere(['teacher_id' => $teacher_details->id])
                                    ->andWhere(['subject_timetable_id' => $subject_timetable->id])
                                    ->andWhere(['date' => date('Y-m-d')])->one();

                                // var_dump(
                                //     $student_class_attendance_check->createCommand()->getRawSql()
                                // );
                                // exit;
                                if (empty($student_class_attendance_check)) {
                                    $student_class_attendance = new  StudentClassAttendance();
                                } else {
                                    $student_class_attendance =   StudentClassAttendance::find()->where(['id' => $student_class_attendance_check->id])->one();
                                }
                                $id = $student_class_attendance_check ? $student_class_attendance_check->id : null;

                                $student_id = $student_details_data->id;
                                $teacher_id = $teacher_details->id;
                                $subject_timetable_id = $subject_timetable->id;
                                $academic_year_id = $academic_year_id;
                                $subject_group_id = $subject_group_id;
                                $period = $subject_timetable->period;
                                $subject_id = $subject_id;
                                $date = date('Y-m-d');
                                $status = empty($student_class_attendance->status) ?
                                    ($student_details_data->status == StudentDetails::STATUS_ACTIVE ? StudentClassAttendance::STATUS_PRESENT : StudentClassAttendance::STATUS_LEAVE)
                                    : $student_class_attendance->status;

                                Yii::$app->db->createCommand("
                                INSERT INTO student_class_attendance (
                                    id,
                                    student_id,
                                    teacher_id,
                                    subject_timetable_id,
                                    academic_year_id,
                                    subject_group_id,
                                    period,
                                    subject_id,
                                    date,
                                    status
                                ) VALUES (
                                    :id,
                                    :student_id,
                                    :teacher_id,
                                    :subject_timetable_id,
                                    :academic_year_id,
                                    :subject_group_id,
                                    :period,
                                    :subject_id,
                                    :date,
                                    :status
                                )
                                ON DUPLICATE KEY UPDATE
                                    student_id = VALUES(student_id),
                                    teacher_id = VALUES(teacher_id),
                                    subject_timetable_id = VALUES(subject_timetable_id),
                                    academic_year_id = VALUES(academic_year_id),
                                    subject_group_id = VALUES(subject_group_id),
                                    period = VALUES(period),
                                    subject_id = VALUES(subject_id),
                                    date = VALUES(date),
                                    status = VALUES(status)
                            ", [
                                    ':id' => $id,
                                    ':student_id' => $student_id,
                                    ':teacher_id' => $teacher_id,
                                    ':subject_timetable_id' => $subject_timetable_id,
                                    ':academic_year_id' => $academic_year_id,
                                    ':subject_group_id' => $subject_group_id,
                                    ':period' => $period,
                                    ':subject_id' => $subject_id,
                                    ':date' => $date,
                                    ':status' => $status,
                                ])->execute();
                            }
                            $data['status'] = self::API_OK;
                            $data['details'] = "Attendance generated success";
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "student details not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Perameter Required";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionStudentClassAttendanceList($sort = '')
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $academic_year_id = $teacher_details->getAcademicId();
                $subject_timetable_id = !empty($post['subject_timetable_id']) ? $post['subject_timetable_id'] : '';
                $search = !empty($post['search']) ? $post['search'] : '';
                $status = !empty($post['status']) ? $post['status'] : '';
                $page = !empty($post['page']) ? $post['page'] : '';
                $class_id  = !empty($post['class_id']) ? $post['class_id'] : '';
                $section_id  = !empty($post['section_id']) ? $post['section_id'] : '';
                $subject_id  = !empty($post['subject_id']) ? $post['subject_id'] : '';

                $date  = date('Y-m-d');
                $day_id = date('l', strtotime($date));



                if (!empty($subject_timetable_id)) {
                    $subject_timetable_data = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
                } else {
                    $subject_timetable_data = SubjectTimetable::find()
                        ->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['teacher_details_id' => $teacher_details->id])
                        ->andWhere(['academic_year_id' => $academic_year_id])
                        ->andWhere(['subject_id' => $subject_id])
                        ->one();
                }

                $query = StudentClassAttendance::find()->innerJoinWith('student as stu')
                    ->where(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['stu.academic_year_id' => $academic_year_id])
                    ->andWhere(['stu.student_class_id' => $class_id])
                    ->andWhere(['stu.section_id' => $section_id])
                    ->andWhere(['<>', 'stu.status', 3]);

                if (!empty($subject_timetable_id)) {

                    $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
                    $query->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id]);
                } else {
                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['teacher_details_id' => $teacher_details->id])
                        ->andWhere(['academic_year_id' => $academic_year_id])->one();

                    $query->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable->id]);
                }

                $query->andWhere(['student_class_attendance.date' => $date]);



                if (!empty($search)) {
                    $query->andFilterWhere([
                        'or',
                        ['like', 'student_details.student_name', $search],
                        ['like', 'student_details.rool_number', $search],
                    ]);
                }

                if (!empty($status)) {
                    $query->andWhere(['student_class_attendance.status' => $status]);
                }




                $student_class_attendance = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ],
                    ],
                    'pagination' => [
                        'pageSize' => 100,
                        'page' => $page,
                    ],
                ]);



                $total = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])->count();

                $total_present = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])
                    ->andWhere(['student_class_attendance.status' => StudentClassAttendance::STATUS_PRESENT])
                    ->count();

                $total_absent = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])
                    ->andWhere(['student_class_attendance.status' => StudentClassAttendance::STATUS_ABSENT])
                    ->count();





                if (!empty($student_class_attendance)) {
                    foreach ($student_class_attendance->models as $student_class_attendance_data) {
                        $list[] = $student_class_attendance_data->asJsonDailyAttendance();
                    }

                    // var_dump($list);exit;
                    if (!empty($list)) {

                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                        $data['total'] = $total;
                        $data['total_present'] = $total_present;
                        $data['total_absent'] = $total_absent;
                        $data['date'] = date('Y-m-d');
                        $data['class'] = $subject_timetable->class->title;
                        $data['section'] = $subject_timetable->section->section_name;
                        $data['time_from'] = date('h:i A', strtotime($subject_timetable_data->time_from ?? ""));
                        $data['time_to'] = date('h:i A', strtotime($subject_timetable_data->time_to ?? ""));
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "attendance data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "attendance data not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionDailyAttendance()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $student_class_attendance_id = !empty($post['student_class_attendance_id']) ? $post['student_class_attendance_id'] : '';
                $status = !empty($post['status']) ? $post['status'] : '';
                $student_class_attendance = StudentClassAttendance::find()->where(['id' => $student_class_attendance_id])->one();
                if (!empty($student_class_attendance)) {
                    $student_class_attendance->status = $status;
                    if ($student_class_attendance->save(false)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $student_class_attendance->asJsonDailyAttendance();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Failed to add attendance";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionNextClass()
    {


        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

                if (!empty($teacher_details)) {
                    $date  = date('Y-m-d');
                    $day_id = date('l', strtotime($date));
                    $campus_id  = $teacher_details->campus_id;
                    $attendance_settings = AttendanceSettings::find()->where(['campus_id' => $campus_id])->andWhere(['status' => AttendanceSettings::STATUS_ACTIVE])->one();
                    if (!empty($attendance_settings)) {
                        $attendance_settings_id  = $attendance_settings->id;

                        $now_time = date('H:i:s');
                        $subject_timetable_next_class =  SubjectTimetable::find()
                            ->innerJoinWith('attendanceTimeTables as att')
                            ->where(['subject_timetable.teacher_details_id' => $teacher_details->id])
                            ->andWhere(['subject_timetable.day_id' => $day_id])
                            ->andWhere(['att.attendance_settings_id' => $attendance_settings_id])
                            ->andWhere(['<=', 'time_from', $now_time])
                            ->andWhere(['>=', 'time_to', $now_time])
                            ->one();
                        // var_dump($subject_timetable_next_class);
                        // exit;
                        if (!empty($subject_timetable_next_class)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $subject_timetable_next_class->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "subject time time table not found";
                        }
                    } else {
                        $subject_timetable = SubjectTimetable::find()->where(['teacher_details_id' => $teacher_details->id])->andWhere(['day_id' => $day_id])->all();

                        if (!empty($subject_timetable)) {
                            foreach ($subject_timetable as $subject_timetable_data) {
                                $time_from = $subject_timetable_data->time_from;
                                $time_to = $subject_timetable_data->time_to;
                                $now_time = date('H:i:s');
                                $subject_timetable_next_class =  SubjectTimetable::find()->where(['teacher_details_id' => $teacher_details->id])->andWhere(['day_id' => $day_id])
                                    ->andWhere(['<=', 'time_from', $now_time])
                                    ->andWhere(['>=', 'time_to', $now_time])
                                    ->one();
                            }

                            // var_dump(
                            //     $subject_timetable_next_class->createCommand()->getRawSql()
                            // );
                            // exit;

                            if (!empty($subject_timetable_next_class)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $subject_timetable_next_class->asJson();
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "subject time time table not found";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "subject time time table not found";
                        }
                    }
                } else {

                    $data['status'] = self::API_NOK;
                    $data['error'] = "teacher details not found";
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



    public function actionLeaveRequests()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {


                $leave_requests =   LeaveRequests::find()->where(['class_teacher_id' => $teacher_details->id])->all();

                if (!empty($leave_requests)) {
                    foreach ($leave_requests as $leave_requests_data) {
                        $list[] = $leave_requests_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Leave request data not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Leave request data not found.";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }


        return $this->sendJsonResponse($data);
    }

    public function actionAcceptLeaveRequest()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $leave_requests_id = !empty($post['leave_requests_id']) ? $post['leave_requests_id'] : '';
                if (!empty($leave_requests_id)) {
                    $leave_requests = LeaveRequests::find()->where(['id' => $leave_requests_id])->one();
                    if (!empty($leave_requests)) {
                        $leave_requests->status = LeaveRequests::STATUS_ACCEPTED;

                        if ($leave_requests->save(false)) {
                            $student_id = $leave_requests->student_id;
                            $from_date = strtotime($leave_requests->from_date);
                            $to_date = strtotime($leave_requests->to_date);

                            while ($from_date <= $to_date) {
                                $current_date = date('Y-m-d', $from_date);

                                $attendance = BaseStudentClassAttendance::find()
                                    ->where(['student_id' => $student_id])
                                    ->andWhere(['teacher_id' => $teacher_details->id])
                                    ->andWhere(['date' => $current_date])
                                    ->one();

                                if (empty($attendance)) {
                                    $attendance = new BaseStudentClassAttendance();
                                    $attendance->student_id = $student_id;
                                    $attendance->teacher_id = $teacher_details->id;
                                    $attendance->date = $current_date;
                                }

                                $attendance->status = StudentClassAttendance::STATUS_LEAVE;
                                $attendance->save(false);

                                $from_date = strtotime("+1 day", $from_date);
                            }
                            $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                            $title = 'Leave Accepted';
                            $body = "Leave Has Been Successfully Accepted By Teacher";
                            $type = '';
                            Yii::$app->notification->UserNotification('', $student_details->parent->user_id, $title, $body, $type);


                            $data['status'] = self::API_OK;
                            $data['details'] = $leave_requests->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "leave request status updated failed";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "leave request data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "leave_requests_id not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionRejectLeaveRequest()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $leave_requests_id = !empty($post['leave_requests_id']) ? $post['leave_requests_id'] : '';
                $rejection_reason = !empty($post['rejection_reason']) ? $post['rejection_reason'] : '';
                if (!empty($leave_requests_id)) {
                    if (!empty($rejection_reason)) {

                        $leave_requests = LeaveRequests::find()->where(['id' => $leave_requests_id])->one();
                        if (!empty($leave_requests)) {
                            $leave_requests = LeaveRequests::find()->where(['id' => $leave_requests_id])->one();
                            $leave_requests->status = LeaveRequests::STATUS_REJECT;
                            $leave_requests->rejection_reason = $rejection_reason;

                            if ($leave_requests->save(false)) {
                                $student_id  = $leave_requests->student_id;
                                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                                if (!empty($student_details)) {
                                    $student_details->status = StudentDetails::STATUS_ACTIVE;
                                    $student_details->save(false);
                                }

                                $title = 'Leave Rejected';
                                $body = "Leave Has Been Rejected By Teacher";
                                $type = '';
                                Yii::$app->notification->UserNotification('', $student_details->parent->user_id, $title, $body, $type);

                                $data['status'] = self::API_OK;
                                $data['details'] = $leave_requests->asJson();
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "leave request status updated failed";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "leave request data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "rejection reason required";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "leave_requests_id not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionClassAndSection()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {

                $class_teacher = ClassTeacher::find()->where(['teacher_details_id' => $teacher_details->id])->andWhere(['status' => ClassTeacher::STATUS_ACTIVE])->all();
                if (!empty($class_teacher)) {
                    foreach ($class_teacher as $class_teacher_data) {
                        $list[] = $class_teacher_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "class and section details not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "class and section details not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionDairyList()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';


                if (!empty($date)) {
                    $day_id = date('l', strtotime($date));

                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])->andWhere(['teacher_details_id' => $teacher_details->id])->all();
                    if (!empty($subject_timetable)) {
                        foreach ($subject_timetable as $subject_timetable_data) {
                            $list[] = $subject_timetable_data->asJsonDairy($date);
                        }

                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Time table data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Date required";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }






    public function actionListAssessment()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';
                $day_id = date('l', strtotime($date));


                $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])->andWhere(['teacher_details_id' => $teacher_details->id])->all();
                if (!empty($subject_timetable)) {
                    foreach ($subject_timetable as $subject_timetable_data) {
                        $list[] = $subject_timetable_data->asJsonAssignment($date);
                    }

                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Time table data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Time table data not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionClassWiseDairy()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';
                if (!empty($teacher_details)) {
                    $class_id  = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
                    $section_id   = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
                    $student_dairy = StudentDairy::find()
                        ->where(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['created_on' => $date])->all();
                    if (!empty($student_dairy)) {
                        foreach ($student_dairy as $student_dairy_data) {
                            $list[] = $student_dairy_data->asJsonClassWiseDairy();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Dairy data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Dairy data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "class and section details not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionClassWiseAssignment()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {


            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

            $class_id  = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
            $section_id   = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';

            if (!empty($teacher_details)) {
                $date = !empty($post['date']) ? $post['date'] : '';
                if (!empty($date)) {
                    $student_assessment = StudentAssessment::find()
                        ->where(['created_on' => $date])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])->all();
                    if (!empty($student_assessment)) {
                        foreach ($student_assessment as $student_assessment_data) {
                            $list[] = $student_assessment_data->asJsonClassWiseAssignment();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "assignment data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "assignment data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "date is required";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionSubmitDayAttendance()
    {



        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $studentClassAttendanceArr = [];
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {

                $json_data =  !empty($post['json_data']) ? $post['json_data'] : '';
                $json_data_decode =   json_decode($json_data);

                if (!empty($json_data_decode)) {
                    foreach ($json_data_decode as $json_data_decode_one) {
                        $student_class_attendance = studentClassAttendance::find()->where(['id' => $json_data_decode_one->student_class_attendance_id])->one();
                        if (!empty($student_class_attendance)) {
                            $student_class_attendance->status = $json_data_decode_one->status;
                            $student_class_attendance->mode = studentClassAttendance::MANUAL_MODE;
                            $student_class_attendance->save(false);
                            if ($json_data_decode_one->status == studentClassAttendance::STATUS_PRESENT && $student_class_attendance->student->status == StudentDetails::STATUS_LEAVE) {
                                $student_details = StudentDetails::find()->where(['id' => $student_class_attendance->student_id])->one();
                                if (!empty($student_details)) {
                                    $student_details->status = StudentDetails::STATUS_ACTIVE;
                                    $student_details->save(false);
                                }
                            }

                            if ($student_class_attendance->status == studentClassAttendance::STATUS_ABSENT) {
                                $studentClassAttendanceArr[] = $student_class_attendance->id;
                            }

                            if ($student_class_attendance->status == studentClassAttendance::STATUS_PRESENT) {
                                $studentPresentData[] = $student_class_attendance->id;
                            }
                        }
                    }
                    if (!empty($studentPresentData)) {
                        $studentPresentFilter = array_filter($studentPresentData);
                        $student_class_attendance_presents = studentClassAttendance::find()->where(['in', 'id', $studentPresentFilter])->all();
                        if (!empty($student_class_attendance_presents)) {
                            foreach ($student_class_attendance_presents as $student_class_attendance_present_data) {
                                if ($student_class_attendance_present_data->status == studentClassAttendance::STATUS_PRESENT) {

                                    $student_name = !empty($student_class_attendance_present_data->student->student_name) ? $student_class_attendance_present_data->student->student_name : 'No Name';
                                    $class = !empty($student_class_attendance_present_data->subject->subject_name) ? $student_class_attendance_present_data->subject->subject_name : 'No Name';

                                    $title = 'Student Attendance info';
                                    $body = "Dear Sir/Madam, $student_name Marked Present  $class today . " . date('Y-m-d');
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $student_class_attendance_present_data->student->parent->user_id, $title, $body, $type);
                                }
                            }
                        }
                    }




                    if (!empty($studentClassAttendanceArr)) {
                        $studentClassAttendanceArrFilter = array_filter($studentClassAttendanceArr);
                        $student_class_attendance_abs = studentClassAttendance::find()->where(['in', 'id', $studentClassAttendanceArrFilter])->andWhere(['message_sent' => 0])->all();
                        if (!empty($student_class_attendance_abs)) {
                            foreach ($student_class_attendance_abs as $student_class_attendance_abs_data) {
                                if ($student_class_attendance_abs_data->status == studentClassAttendance::STATUS_ABSENT) {
                                    $student_class_attendance_abs_data->mode = studentClassAttendance::MANUAL_MODE; // Set mode to MANUAL_MODE
                                    $student_class_attendance_abs_data->save(false);


                                    if ($student_class_attendance_abs_data->student->campus_id == 71 || $student_class_attendance_abs_data->student->campus_id == 55 || $student_class_attendance_abs_data->student->campus_id == 81 || $student_class_attendance_abs_data->student->campus_id == 89 || $student_class_attendance_abs_data->student->campus_id == 73) {
                                        $student_name = !empty($student_class_attendance_abs_data->student->student_name)
                                            ? substr($student_class_attendance_abs_data->student->student_name, 0, 22)
                                            : 'No Name';
                                        $student_name = strtoupper($student_name);
                                        $class = !empty($student_class_attendance_abs_data->subject->subject_name) ? $student_class_attendance_abs_data->subject->subject_name : 'No Name';
                                        $contact_no = $student_class_attendance_abs_data->student->parent->contact_number;
                                        $sudentClass = isset($student_class_attendance_abs_data->student->studentClass->title) ? $student_class_attendance_abs_data->student->studentClass->title : "";
                                        $sudentSection = isset($student_class_attendance_abs_data->student->section->section_name) ? $student_class_attendance_abs_data->student->section->section_name : "";
                                        $campusName = isset($student_class_attendance_abs_data->student->campus->name_of_the_educational_Institution)
                                            ? substr($student_class_attendance_abs_data->student->campus->name_of_the_educational_Institution, 0, 30)
                                            : "";
                                        $sms = "Dear Parent/Guardian, This is to inform you that your ward, $student_name of $sudentClass-$sudentSection, was absent from school today. -Estudent Regards, $campusName";
                                        // $sms = "Dear Sir/Madam,your ward, $student_name did not attended SUBJECT $class class today Regards, Estudent";
                                        $sms_url = urlencode($sms);
                                        $template_id = '1007530594033050923';
                                        $sender = 'ESTDNT';
                                        $route = 7;
                                        $SendOtpData = new SendOtp();
                                        // $send_otp = $SendOtpData-    >sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
                                        $student_class_attendance_abs_data->message_sent = 1;
                                        $student_class_attendance_abs_data->save(false);
                                    } else {
                                        $student_name = !empty($student_class_attendance_abs_data->student->student_name) ? $student_class_attendance_abs_data->student->student_name : 'No Name';
                                        $class = !empty($student_class_attendance_abs_data->subject->subject_name) ? $student_class_attendance_abs_data->subject->subject_name : 'No Name';
                                        $contact_no = $student_class_attendance_abs_data->student->parent->contact_number;
                                        $sms = "Dear Sir/Madam,your ward, $student_name did not attended SUBJECT $class class today Regards, Estudent";
                                        $sms_url = urlencode($sms);
                                        $template_id = '1007540324428275979';
                                        $sender = 'ESTDNT';
                                        $route = 7;
                                        $SendOtpData = new SendOtp();
                                        $send_otp = $SendOtpData->sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
                                    }




                                    $title = 'Student Attendance info';
                                    $body = "Dear Sir/Madam, $student_name did not  $class today . " . date('Y-m-d');
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $student_class_attendance_abs_data->student->parent->user_id, $title, $body, $type);


                                    if (strlen($send_otp) > 4) {
                                    }
                                }
                            }
                        }
                    }

                    $data['status'] = self::API_OK;
                    $data['details'] = "Attendance Update Success";
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Json data not found";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionTodayBirthdays()
    {



        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $date = date('m-d');
                $student_details = StudentDetails::find()
                    ->where(['campus_id' => $teacher_details->campus_id])
                    ->andFilterWhere(['like', 'date_of_birth', $date])
                    ->all();
                if (!empty($student_details)) {
                    foreach ($student_details as $student_details_data) {
                        $list[] = $student_details_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Birthdays not found to day";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Birthdays not found to day";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionTodayBirthdaysClassWise()
    {



        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            $class_id  = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
            $section_id   = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
            if (!empty($teacher_details)) {
                $date = date('m-d');
                $student_details = StudentDetails::find()
                    ->where(['campus_id' => $teacher_details->campus_id])
                    ->andWhere(['student_class_id' => $class_id])
                    ->andWhere(['section_id' => $section_id])
                    ->andFilterWhere(['like', 'date_of_birth', $date])
                    ->all();
                if (!empty($student_details)) {
                    foreach ($student_details as $student_details_data) {
                        $list[] = $student_details_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Birthdays not found to day";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Birthdays not found to day";
                }
            } else {

                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    //     public function actionWriteAssessment()
    //     {
    //         $data = [];
    //         $post = Yii::$app->request->post();

    //         try {
    //             $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
    //             $auth = new AuthSettings();
    //             $user_id = $auth->getAuthSession($headers);
    //             if (!empty($user_id)) {

    //                 $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
    //                 if (!empty($teacher_details)) {
    //                     $assessment = !empty($post['assessment']) ? $post['assessment'] : '';
    //                     $submission_date = !empty($post['submission_date']) ? $post['submission_date'] : '';
    //                     $document = !empty($post['document']) ? $post['document'] : '';
    //                     $subject_timetable_id = !empty($post['subject_timetable_id']) ? $post['subject_timetable_id'] : '';
    //                     $send_all = !empty($post['send_all']) ? $post['send_all'] : 'false';
    //                     $selected_student_id = !empty($post['selected_student_id']) ? $post['selected_student_id'] : '';
    //                     $file_type = !empty($post['file_type']) ? $post['file_type'] : '';
    //                     $remarks = !empty($post['remarks']) ? $post['remarks'] : '';

    // // var_dump($subject_timetable_id);exit;


    //                     $campus_id  = $teacher_details->campus_id;
    //                     $teacher_details_id  = $teacher_details->id;
    //                     $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
    //                     $academic_year_id = $subject_timetable->academic_year_id;
    //                     $class_id  = $subject_timetable->class_id;
    //                     $section_id   = $subject_timetable->section_id;
    //                     $subject_id    = $subject_timetable->subject_id;


    //                     $StudentAssessmentCheck = StudentAssessment::find()->where(['teacher_details_id' => $teacher_details_id])
    //                         ->andWhere(['subject_timetable_id' => $subject_timetable_id])
    //                         ->andWhere(['academic_year_id' => $academic_year_id])
    //                         ->andWhere(['class_id' => $class_id])
    //                         ->andWhere(['section_id' => $section_id])
    //                         ->andWhere(['subject_id' => $subject_id])
    //                         ->one();
    //                     if (!empty($StudentAssessmentCheck)) {
    //                         $StudentAssessment =    StudentAssessment::find()->where(['id' => $StudentAssessmentCheck->id])->one();
    //                     } else {
    //                         $StudentAssessment =  new  StudentAssessment();
    //                     }
    //                     $StudentAssessment->campus_id  = $campus_id;
    //                     $StudentAssessment->teacher_details_id    = $teacher_details_id;
    //                     $StudentAssessment->subject_timetable_id  = $subject_timetable_id;
    //                     $StudentAssessment->academic_year_id  = $academic_year_id;
    //                     $StudentAssessment->class_id   = $class_id;
    //                     $StudentAssessment->section_id  = $section_id;
    //                     $StudentAssessment->subject_id  = $subject_id;
    //                     $StudentAssessment->remarks  = $remarks;

    //                     $StudentAssessment->assessment  = $assessment;
    //                     $StudentAssessment->submission_date  = $submission_date;
    //                     $StudentAssessment->file_type  = $file_type;
    //                     $StudentAssessment->document  = $document;
    //                     $StudentAssessment->status  = StudentAssessment::STATUS_ACTIVE;
    //                     // var_dump($StudentAssessment);exit;
    //                     if ($StudentAssessment->save(false)) {
    //                         if ($send_all == 'true') {
    //                             $student_details = StudentDetails::find()->where(['student_class_id' => $class_id])->andWhere(['section_id' => $section_id])->all();
    //                         //    var_dump($student_details);exit;
    //                             if (!empty($student_details)) {
    //                                 foreach ($student_details as $student_details_data) {
    //                                     $student_id  = $student_details_data->id;
    //                                     $StudentHasAssessmentCheck  =   StudentHasAssessment::find()->where(['student_id' => $student_id])->andWhere(['student_assessment_id' => $StudentAssessment->id])->one();
    //                                     if (!empty($StudentHasAssessmentCheck)) {
    //                                         $StudentHasAssessment =   StudentHasAssessment::find()->where(['id' => $StudentHasAssessmentCheck->id])->one();
    //                                     } else {
    //                                         $StudentHasAssessment =  new StudentHasAssessment();
    //                                     }

    //                                     $StudentHasAssessment->student_id  = $student_id;
    //                                     $StudentHasAssessment->student_assessment_id    = $StudentAssessment->id;
    //                                     $StudentHasAssessment->date   = date('Y-m-d');
    //                                     $StudentHasAssessment->is_read   = StudentHasAssessment::is_read_no;
    //                                     $StudentHasAssessment->status   = StudentHasAssessment::STATUS_PENDING;
    //                                     $StudentHasAssessment->save(false);
    // // var_dump($StudentHasAssessment);exit;
    //                                     $student_name = !empty($StudentHasAssessment->student->student_name) ? $StudentHasAssessment->student->student_name : 'No Name';
    //                                     $subject_name =   !empty($StudentAssessment->subject->subject_name) ? $StudentAssessment->subject->subject_name : '';

    //                                     $title = 'Student Assignment Info';
    //                                     $body = "Dear parent Assignment Created for $student_name subject $subject_name";
    //                                     $type = '';
    //                                     Yii::$app->notification->UserNotification('', $StudentHasAssessment->student->parent->user_id, $title, $body, $type,"student_assessment");
    //                                 }
    //                             }
    //                         } else {
    //                             if (!empty($selected_student_id)) {
    //                                 $selected_student_id_arr = explode(',', $selected_student_id);
    //                                 if (!empty($selected_student_id_arr)) {
    //                                     foreach ($selected_student_id_arr as $student_id) {
    //                                         $studentHasAssessmentCheck  =   studentHasAssessment::find()->where(['student_id' => $student_id])->andWhere(['student_assessment_id' => $StudentAssessment->id])->one();
    //                                         if (!empty($studentHasAssessmentCheck)) {
    //                                             $studentHasAssessment =   studentHasAssessment::find()->where(['id' => $studentHasAssessmentCheck->id])->one();
    //                                         } else {
    //                                             $studentHasAssessment =  new studentHasAssessment();
    //                                         }

    //                                         $studentHasAssessment->student_id  = $student_id;
    //                                         $studentHasAssessment->student_assessment_id    = $StudentAssessment->id;
    //                                         $studentHasAssessment->date   = date('Y-m-d');
    //                                         $studentHasAssessment->is_read   = studentHasAssessment::is_read_no;
    //                                         $studentHasAssessment->status   = studentHasAssessment::STATUS_PENDING;
    //                                         $studentHasAssessment->save(false);

    //                                         $student_name = !empty($studentHasAssessment->student->student_name) ? $studentHasAssessment->student->student_name : 'No Name';
    //                                         $subject_name =   !empty($StudentAssessment->subject->subject_name) ? $StudentAssessment->subject->subject_name : '';


    //                                         // $title = 'Student Assignment Info';
    //                                         // $body = "Dear parent Assignment Created for $student_name subject $subject_name";
    //                                         // $type = '';
    //                                         // Yii::$app->notification->UserNotification('', $studentHasAssessment->student->parent->user_id, $title, $body, $type,"student_assessment");
    //                                     }
    //                                 }
    //                             }
    //                         }





    //                         $data['status'] = self::API_OK;
    //                         $data['details'] = $StudentAssessment->asJson();
    //                     } else {
    //                         $data['status'] = self::API_NOK;
    //                         $data['error'] = "StudentAssessment data not updated";
    //                     }
    //                 } else {
    //                     $data['status'] = self::API_NOK;
    //                     $data['error'] = "Profile details not found";
    //                 }
    //             } else {
    //                 $data['status'] = self::API_NOK;
    //                 $data['error'] = "No User found.";
    //             }
    //         } catch (Exception $e) {
    //             $data['status'] = self::API_NOK;
    //             $data['error'] = $e->getMessage();
    //         }
    //     }

    public function actionWriteAssessment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [];
        $post = Yii::$app->request->post();

        try {
            // Get auth code from headers or query params
            $headers = \Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (empty($user_id)) {
                throw new \Exception("No User found.");
            }

            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (empty($teacher_details)) {
                throw new \Exception("Profile details not found.");
            }

            // Extract POST parameters
            $assessment = $post['assessment'] ?? '';
            $submission_date = $post['submission_date'] ?? '';
            $document = $post['document'] ?? '';
            $subject_timetable_id = $post['subject_timetable_id'] ?? '';
            $send_all = $post['send_all'] ?? 'false';
            $selected_student_id = $post['selected_student_id'] ?? '';
            $file_type = $post['file_type'] ?? '';
            $remarks = $post['remarks'] ?? '';

            // Teacher and timetable details
            $campus_id = $teacher_details->campus_id;
            $teacher_details_id = $teacher_details->id;

            $subject_timetable = SubjectTimetable::findOne($subject_timetable_id);
            if (empty($subject_timetable)) {
                throw new \Exception("Subject timetable not found.");
            }

            $academic_year_id = $subject_timetable->academic_year_id;
            $class_id = $subject_timetable->class_id;
            $section_id = $subject_timetable->section_id;
            $subject_id = $subject_timetable->subject_id;

            // Check if an assessment already exists
            $StudentAssessment = StudentAssessment::find()
                ->where([
                    'teacher_details_id' => $teacher_details_id,
                    'subject_timetable_id' => $subject_timetable_id,
                    'academic_year_id' => $academic_year_id,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'subject_id' => $subject_id,
                ])
                ->one();

            if (empty($StudentAssessment)) {
                $StudentAssessment = new StudentAssessment();
            }

            // Assign values to StudentAssessment
            $StudentAssessment->campus_id = $campus_id;
            $StudentAssessment->teacher_details_id = $teacher_details_id;
            $StudentAssessment->subject_timetable_id = $subject_timetable_id;
            $StudentAssessment->academic_year_id = $academic_year_id;
            $StudentAssessment->class_id = $class_id;
            $StudentAssessment->section_id = $section_id;
            $StudentAssessment->subject_id = $subject_id;
            $StudentAssessment->remarks = $remarks;
            $StudentAssessment->assessment = $assessment;
            $StudentAssessment->submission_date = $submission_date;
            $StudentAssessment->file_type = $file_type;
            $StudentAssessment->document = $document;
            $StudentAssessment->status = StudentAssessment::STATUS_ACTIVE;

            // Save StudentAssessment and handle related data
            if ($StudentAssessment->save(false)) {
                if ($send_all === 'true') {
                    $student_details = StudentDetails::find()
                        ->where(['student_class_id' => $class_id, 'section_id' => $section_id])
                        ->all();

                    foreach ($student_details as $student) {
                        $this->saveStudentAssessment($student->id, $StudentAssessment->id);
                    }
                } elseif (!empty($selected_student_id)) {
                    $selected_student_id_arr = explode(',', $selected_student_id);

                    foreach ($selected_student_id_arr as $student_id) {
                        $this->saveStudentAssessment($student_id, $StudentAssessment->id);
                    }
                }

                $data['status'] = self::API_OK;
                $data['details'] = $StudentAssessment->asJson();
            } else {
                throw new \Exception("Failed to save StudentAssessment.");
            }
        } catch (\Exception $e) {
            \Yii::error("Error in actionWriteAssessment: " . $e->getMessage(), __METHOD__);
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $data;
    }

    // Helper function to save student assessments
    private function saveStudentAssessment($student_id, $student_assessment_id)
    {
        $StudentHasAssessment = StudentHasAssessment::find()
            ->where(['student_id' => $student_id, 'student_assessment_id' => $student_assessment_id])
            ->one();

        if (empty($StudentHasAssessment)) {
            $StudentHasAssessment = new StudentHasAssessment();
        }

        $StudentHasAssessment->student_id = $student_id;
        $StudentHasAssessment->student_assessment_id = $student_assessment_id;
        $StudentHasAssessment->date = date('Y-m-d');
        $StudentHasAssessment->is_read = StudentHasAssessment::is_read_no;
        $StudentHasAssessment->status = StudentHasAssessment::STATUS_PENDING;

        if (!$StudentHasAssessment->save(false)) {
            \Yii::error("Failed to save StudentHasAssessment for student_id: $student_id", __METHOD__);
        }
    }


    public function actionStudentListByClassAndSection()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_class_id  = !empty($post['student_class_id']) ? $post['student_class_id'] : '';
            $section_id   = !empty($post['section_id']) ? $post['section_id'] : '';
            try {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $student_details = StudentDetails::find()->where(['student_class_id' => $student_class_id])->andWhere(['section_id' => $section_id])->andWhere(['status' => StudentDetails::STATUS_ACTIVE])->all();
                    if (!empty($student_details)) {
                        foreach ($student_details as $student_details_data) {
                            $list[] = $student_details_data->asJson();
                        }

                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "student  details not found";
                    }
                } else {

                    $data['status'] = self::API_NOK;
                    $data['error'] = "teacher details not found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionStudentListByClasswise()
    {


        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {

            try {
                $exsm_id =  $post['exam_id'];
                $search =  $post['search'];
                $page = 0;



                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $academic_year_id = $teacher_details->getAcademicId();
                    $student_class_id  = $teacher_details->class_id;
                    $section_id   = $teacher_details->section_id;
                    $student_details_query = StudentDetails::find()->where(['student_class_id' => $student_class_id])
                        ->andWhere(['section_id' => $section_id])->andWhere(['status' => StudentDetails::STATUS_ACTIVE]);

                    if (!empty($search)) {
                        $student_details_query->andFilterWhere([
                            'or',
                            ['like', 'student_details.student_name', $search],
                            ['like', 'student_details.rool_number', $search],
                        ]);
                    }

                    $student_details = new ActiveDataProvider([
                        'query' => $student_details_query,
                        'sort' => [
                            'defaultOrder' => [
                                'id' => SORT_DESC,
                            ],
                        ],
                        'pagination' => [
                            'pageSize' => 1000,
                            'page' => $page,
                        ],
                    ]);



                    if (!empty($student_details)) {
                        foreach ($student_details->models as $student_details_data) {
                            $list[] = $student_details_data->asJsonExamResult($exsm_id);
                        }

                        $total_student_count = StudentDetails::find()->where(['student_class_id' => $student_class_id])
                            ->andWhere(['section_id' => $section_id])->count();
                        $exams_result_upload_deone = ExamsResult::find()
                            ->andWhere(['class_id' => $student_class_id])
                            ->andWhere(['section_id' => $section_id])
                            ->andWhere(['exam_id' => $exsm_id])
                            ->count();
                        $exams_result_pending =  $total_student_count - $exams_result_upload_deone;

                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                        $data['total_student_count'] = $total_student_count;
                        $data['exams_result_upload_deone'] = $exams_result_upload_deone;
                        $data['exams_result_pending'] = $exams_result_pending;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "student  details not found";
                    }
                } else {

                    $data['status'] = self::API_NOK;
                    $data['error'] = "teacher details not found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionClassWiseAttendance()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();



            if (!empty($teacher_details)) {
                $subject_timetable_id = !empty($post['subject_timetable_id']) ? $post['subject_timetable_id'] : '';
                $search = !empty($post['search']) ? $post['search'] : '';
                $status = !empty($post['status']) ? $post['status'] : '';
                $page = !empty($post['page']) ? $post['page'] : '';
                $date  = !empty($post['date']) ? $post['date'] : '';
                $subject_id   = !empty($post['subject_id']) ? $post['subject_id'] : '';
                $class_id  = $teacher_details->class_id;
                $section_id  = $teacher_details->section_id;

                $day_id = date('l', strtotime($date));
                $academic_year_id = $teacher_details->getAcademicId();


                if (!empty($subject_timetable_id)) {
                    $subject_timetable_data = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
                } else {
                    $subject_timetable_data = SubjectTimetable::find()->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['subject_id' => $subject_id])
                        ->andWhere(['academic_year_id' => $academic_year_id])->one();
                }




                $query = StudentClassAttendance::find()->innerJoinWith('student as stu')->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])->andWhere(['student_class_attendance.subject_id' => $subject_id])->andWhere(['stu.student_class_id' => $class_id])->andWhere(['stu.section_id' => $section_id]);
                $query->andWhere(['<>', 'stu.status', 3]); // Exclude students with status 3



                if (!empty($subject_timetable_id)) {

                    $subject_timetable = SubjectTimetable::find()->where(['id' => $subject_timetable_id])->one();
                    $subject_timetable_id = $subject_timetable_id;
                } else {

                    $subject_timetable = SubjectTimetable::find()->where(['day_id' => $day_id])
                        ->andWhere(['class_id' => $class_id])
                        ->andWhere(['section_id' => $section_id])
                        ->andWhere(['subject_id' => $subject_id])
                        ->andWhere(['academic_year_id' => $academic_year_id])->one();

                    $subject_timetable_id = $subject_timetable->id ?? null;
                }
                $query->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id]);




                $query->andWhere(['student_class_attendance.date' => $date]);

                // print_r($query->createCommand()->getRawSql());
                // exit;
                if (!empty($search)) {
                    $query->andFilterWhere([
                        'or',
                        ['like', 'student_details.student_name', $search],
                        ['like', 'student_details.rool_number', $search],
                    ]);
                }

                if (!empty($status)) {
                    $query->andWhere(['student_class_attendance.status' => $status]);
                }




                $student_class_attendance = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ],
                    ],
                    'pagination' => [
                        'pageSize' => 100,
                        'page' => $page,
                    ],
                ]);



                $total = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])->count();

                $total_present = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])
                    ->andWhere(['student_class_attendance.status' => StudentClassAttendance::STATUS_PRESENT])
                    ->count();

                $total_absent = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.teacher_id' => $teacher_details->id])
                    ->andWhere(['student_class_attendance.subject_timetable_id' => $subject_timetable_id])
                    ->andWhere(['student_class_attendance.date' => date('Y-m-d')])
                    ->andWhere(['student_class_attendance.status' => StudentClassAttendance::STATUS_ABSENT])
                    ->count();







                if (!empty($student_class_attendance)) {
                    foreach ($student_class_attendance->models as $student_class_attendance_data) {
                        $list[] = $student_class_attendance_data->asJsonDailyAttendance();
                    }

                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                        $data['total'] = $total;
                        $data['total_present'] = $total_present;
                        $data['total_absent'] = $total_absent;
                        $data['date'] = date('Y-m-d');
                        $data['class'] = $subject_timetable->class->title;
                        $data['section'] = $subject_timetable->section->section_name;
                        $data['time_from'] = date('h:i A', strtotime($subject_timetable_data->time_from));
                        $data['time_to'] = date('h:i A', strtotime($subject_timetable_data->time_to));
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "attendance data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "attendance data not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionWriteNotice()
    {



        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $title  = !empty($post['title']) ? $post['title'] : '';
            $description   = !empty($post['description']) ? $post['description'] : '';
            $expiry_date   = !empty($post['expiry_date']) ? $post['expiry_date'] : '';
            $send_all = !empty($post['send_all']) ? $post['send_all'] : 'false';
            $selected_student_id = !empty($post['selected_student_id']) ? $post['selected_student_id'] : '';

            try {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $section_id = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
                    $section_id  = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
                    $campus_id  = $teacher_details->campus_id;
                    $student_notice_boards = new StudentNoticeBoards();
                    $student_notice_boards->title = $title;
                    $student_notice_boards->campus_id = $campus_id;
                    $student_notice_boards->description = $description;
                    $student_notice_boards->section_id  = $section_id;
                    $student_notice_boards->expiry_date = $expiry_date;
                    $student_notice_boards->teacher_details_id  = $teacher_details->id;
                    $student_notice_boards->status  = StudentNoticeBoards::STATUS_ACTIVE;
                    if ($student_notice_boards->save(false)) {



                        if ($send_all == 'true') {
                            $student_notice_boards->is_global =  StudentNoticeBoards::is_global_no;
                            $student_notice_boards->save(false);

                            $student_details = StudentDetails::find()->andWhere(['section_id' => $section_id])->all();
                            if (!empty($student_details)) {
                                foreach ($student_details as $student_details_id) {
                                    $student_id  = $student_details_id->id;

                                    $StudentHasNoticeCheck  =   StudentHasNotice::find()->where(['student_id' => $student_id])->andWhere(['student_notice_board_id' => $student_notice_boards->id])->one();
                                    if (!empty($StudentHasNoticeCheck)) {
                                        $StudentHasNotice =   StudentHasNotice::find()->where(['id' => $StudentHasNoticeCheck->id])->one();
                                    } else {
                                        $StudentHasNotice =  new StudentHasNotice();
                                    }

                                    $StudentHasNotice->student_id  = $student_id;
                                    $StudentHasNotice->student_notice_board_id   = $student_notice_boards->id;
                                    $StudentHasNotice->status   = StudentHasNotice::STATUS_ACTIVE;
                                    $StudentHasNotice->is_read   = StudentHasNotice::is_read_yes;

                                    $StudentHasNotice->save(false);

                                    $student_name = !empty($StudentHasNotice->student->student_name) ? $StudentHasNotice->student->student_name : 'No Name';

                                    $title = 'Student Notice Info';
                                    $body = "Dear parent Notice for $student_name";
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $StudentHasNotice->student->parent->user_id, $title, $body, $type);
                                }
                            }
                        } else {
                            $student_notice_boards->is_global =  StudentNoticeBoards::is_global_no;
                            $student_notice_boards->save(false);
                            if (!empty($selected_student_id)) {
                                $selected_student_id_arr = explode(',', $selected_student_id);
                                if (!empty($selected_student_id_arr)) {
                                    foreach ($selected_student_id_arr as $student_id) {
                                        $StudentHasNoticeCheck  =   StudentHasNotice::find()->where(['student_id' => $student_id])->andWhere(['student_notice_board_id' => $student_notice_boards->id])->one();
                                        if (!empty($StudentHasNoticeCheck)) {
                                            $StudentHasNotice =   StudentHasNotice::find()->where(['id' => $StudentHasNoticeCheck->id])->one();
                                        } else {
                                            $StudentHasNotice =  new StudentHasNotice();
                                        }

                                        $StudentHasNotice->student_id  = $student_id;
                                        $StudentHasNotice->student_notice_board_id   = $student_notice_boards->id;
                                        $StudentHasNotice->status   = StudentHasNotice::STATUS_ACTIVE;
                                        $StudentHasNotice->is_read   = StudentHasNotice::is_read_yes;
                                        $StudentHasNotice->save(false);

                                        $student_name = !empty($StudentHasNotice->student->student_name) ? $StudentHasNotice->student->student_name : 'No Name';
                                        $title = 'Student Notice Info';
                                        $body = "Dear parent Notice for $student_name";
                                        $type = '';
                                        Yii::$app->notification->UserNotification('', $StudentHasNotice->student->parent->user_id, $title, $body, $type);
                                    }
                                }
                            }
                        }













                        $data['status'] = self::API_OK;
                        $data['details'] = $student_notice_boards->asJson();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "data saved failed";
                    }
                } else {

                    $data['status'] = self::API_NOK;
                    $data['error'] = "teacher details not found";
                }
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionNoticeBoard()
    {
        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (!empty($user_id)) {
                $date = date('Y-m-d');
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

                if (!empty($teacher_details)) {
                    $class_teacher = ClassTeacher::find()->where(['teacher_details_id' => $teacher_details->id])->andWhere(['status' => ClassTeacher::STATUS_ACTIVE])->all();
                    $section_id = !empty($class_teacher) ? array_map(function ($class_teacher_data) {
                        return $class_teacher_data->section_id;
                    }, $class_teacher) : [];

                    $NoticeBoards = NoticeBoards::find()->where(['in', 'section_id', $section_id])
                        ->andWhere(['>', 'expiry_date', $date])
                        ->orderBy(['created_on' => SORT_DESC])  // Sorting in descending order
                        ->all();

                    $particularTeacherNotice = NoticeBoards::find()->where(['teacher_id' => $teacher_details->id])
                        ->andWhere(['>', 'expiry_date', $date])
                        ->orderBy(['created_on' => SORT_DESC])  // Sorting in descending order
                        ->all();

                    $allNotices = array_merge($NoticeBoards, $particularTeacherNotice);

                    // Sort merged notices by created_on in descending order
                    usort($allNotices, function ($a, $b) {
                        return strtotime($b->created_on) - strtotime($a->created_on);
                    });

                    if (!empty($allNotices)) {
                        $list = array_map(function ($notice) {
                            return $notice->asJson();
                        }, $allNotices);

                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Teacher details not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No user found";
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }



    public function actionSpecialDays()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $section_id  = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';

                $campus_id  = $teacher_details->campus_id;
                $startDate = new \DateTime();
                $endDate = clone $startDate;
                $endDate->add(new \DateInterval('P30D'));


                $special_days = SpecialDays::find()->where(['status' => SpecialDays::STATUS_ACTIVE])
                    ->andWhere(['campus_id' => $campus_id])
                    ->andWhere(['between', 'date', $startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->all();
                if (!empty($special_days)) {
                    foreach ($special_days as $special_days_data) {
                        $list[] = $special_days_data->asJson();
                    }
                    if (!empty($list)) {

                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "data not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "data not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionStudentSearch()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            $search_key = !empty($post['search_key']) ? $post['search_key'] : '';
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();

            if (!empty($teacher_details)) {
                $class_id = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
                $section_id = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
                $campus_id = $teacher_details->campus_id;
                $campus = Campus::findOne($campus_id);
                $student_details = StudentDetails::find()
                    ->where(['campus_id' => $campus_id])
                    ->andWhere(['student_class_id' => $class_id])
                    ->andWhere(['section_id' => $section_id])
                    ->andWhere(['academic_year_id' => $campus->academic_year])
                    ->andWhere(['<>', 'status', 3])
                    ->andWhere(
                        [
                            'or',
                            ['like', 'student_name', $search_key],
                            ['like', 'rool_number', $search_key],
                            ['like', 'admission_number', $search_key],
                        ]
                    )
                    ->all();

                if (!empty($student_details)) {
                    $list = [];
                    foreach ($student_details as $student_details_data) {
                        $list[] = $student_details_data->asJson(); // This includes the student_face_registered key
                    }

                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No students found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Data not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }

        return $this->sendJsonResponse($data);
    }




    public function actionGetSubjectsBySectionId()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {

            $search_key = !empty($post['search_key']) ? $post['search_key'] : '';
            $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($teacher_details)) {
                $academic_year_id = $teacher_details->getAcademicId();
                $class_id   = !empty($teacher_details->class_id) ? $teacher_details->class_id : '';
                $section_id   = !empty($teacher_details->section_id) ? $teacher_details->section_id : '';
                $campus_id  = !empty($teacher_details->campus_id) ? $teacher_details->campus_id : '';
                $subject_groups_class_sections = SubjectGroupsClassSections::find()->where(['class_sections_id' => $section_id])->one();

                if (!empty($subject_groups_class_sections)) {
                    $subject_group_id  = $subject_groups_class_sections->subject_group_id;

                    $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_group_id])->andWhere(['academic_year_id' => $academic_year_id])->all();
                    // var_dump($academic_year_id);exit;

                    if (empty($subject_group_subjects)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Subject group not found for the current session.";
                        return $this->sendJsonResponse($data);
                    }

                    if (!empty($subject_group_subjects)) {
                        foreach ($subject_group_subjects as $subject_group_subjects_dada) {
                            $list[] = $subject_group_subjects_dada->asJson();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "subject data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "subject data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Subject group not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "teacher details not found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }
    public function actionMyNotification()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $fcm_notification = FcmNotification::find()->where(['user_id' => $user_id])->andWhere(['status' => \app\modules\admin\models\base\FcmNotification::STATUS_ACTIVE])->orderBy(['id' => SORT_DESC])->all();
            if (!empty($fcm_notification)) {
                foreach ($fcm_notification as $fcm_notification_data) {
                    $list[] = $fcm_notification_data->asJson();
                }
                if (!empty($list)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $list;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "notification not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "notification not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionNotiCount()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $fcm_notification = FcmNotification::find()->where(['user_id' => $user_id])->orderBy(['id' => SORT_DESC])->count();
            if (!empty($fcm_notification)) {

                if (!empty($fcm_notification)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $fcm_notification;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "notification not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "notification not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionClearNotifications()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            // Find all active notifications for the user
            $fcm_notifications = FcmNotification::find()->where(['user_id' => $user_id, 'status' => FcmNotification::STATUS_ACTIVE])->all();

            if (!empty($fcm_notifications)) {
                // Delete each notification
                foreach ($fcm_notifications as $notification) {
                    $notification->delete(); // Delete the record from the database
                }

                $data['status'] = self::API_OK;
                $data['message'] = "All notifications cleared.";
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No active notifications found to clear.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }

        return $this->sendJsonResponse($data);
    }


    function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }


    public function actionMarkAttendance()
    {
        $data = [];
        try {


            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {
                $lat = !empty($post['lat']) ? $post['lat'] : '';
                $lng = !empty($post['lng']) ? $post['lng'] : '';
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $campus_lat = !empty($teacher_details->campus->lat) ? $teacher_details->campus->lat : '';
                    $campus_lng = !empty($teacher_details->campus->lng) ? $teacher_details->campus->lng : '';
                    $radius = !empty($teacher_details->campus->radius) ? $teacher_details->campus->radius : '';
                    $distance = $this->calculateDistance($campus_lat, $campus_lng, $lat, $lng);
                    if ($distance <= $radius) {
                        $currentDate = date('Y-m-d');

                        $existingRecord = TeacherAttenddence::find()
                            ->where(['teacher_details_id' => $teacher_details->id, 'date' => $currentDate])
                            ->one();

                        if (empty($existingRecord)) {
                            $teacher_attenddence = new TeacherAttenddence();
                            $teacher_attenddence->teacher_details_id   = $teacher_details->id;
                            $teacher_attenddence->teacher_present_date_and_time = date('Y-m-d H:i:s');
                            $teacher_attenddence->date = date('Y-m-d');
                            $teacher_attenddence->status = TeacherAttenddence::STATUS_ACTIVE;
                            $teacher_attenddence->lat = $lat;
                            $teacher_attenddence->lng = $lng;
                            $teacher_attenddence->save(false);
                            $data['status'] = self::API_OK;
                            $data['details'] = $teacher_attenddence->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "teacher details Attendance already done";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "You are not inside the premisses so you will not able to clock in";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "teacher details not found";
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


    public function actionCheckOut()
    {
        $data = [];
        try {


            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {
                $checkout_lat = !empty($post['checkout_lat']) ? $post['checkout_lat'] : '';
                $checkout_lng = !empty($post['checkout_lng']) ? $post['checkout_lng'] : '';

                if (!empty($checkout_lat) && !empty($checkout_lng)) {

                    $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                    if (!empty($teacher_details)) {
                        $campus_lat = !empty($teacher_details->campus->lat) ? $teacher_details->campus->lat : '';
                        $campus_lng = !empty($teacher_details->campus->lng) ? $teacher_details->campus->lng : '';
                        $radius = !empty($teacher_details->campus->radius) ? $teacher_details->campus->radius : '';
                        $distance = $this->calculateDistance($campus_lat, $campus_lng, $checkout_lat, $checkout_lng);
                        if ($distance <= $radius) {

                            $currentDate = date('Y-m-d');
                            $existingRecord = TeacherAttenddence::find()
                                ->where(['teacher_details_id' => $teacher_details->id, 'date' => $currentDate])
                                ->one();
                            if (!empty($existingRecord)) {
                                $existingRecord->checkout_lat = $checkout_lat;
                                $existingRecord->checkout_lng = $checkout_lng;
                                $existingRecord->checkout_date_time = date('Y-m-d H:i:s');
                                if ($existingRecord->save(false)) {
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $existingRecord->asJson();
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Failed to logout ";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "You cannot log out at this moment ";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "You are not inside the premisses so you will not able to clock out";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "teacher details not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Checkout Lat and Checkout Lang Can not be empty.";
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
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {

                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {

                    $campus_id = $teacher_details->campus_id;
                    $exams = Exams::find()->where(['campus_id' => $campus_id])->andWhere(['status' => Exams::STATUS_ACTIVE])->all();
                    if (!empty($exams)) {
                        foreach ($exams as $exams_data) {
                            $list[] = $exams_data->asJson();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "exam data not  found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "exam data not  found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
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

    public function actionUploadMarkSheet()
    {

        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $campus_id  = $teacher_details->campus_id;
                    $academic_year_id  = $teacher_details->campus->academic_year;
                    $exam_id  = !empty($post['exam_id']) ? $post['exam_id'] : '';
                    $student_id  = !empty($post['student_id']) ? $post['student_id'] : '';
                    $percentage_or_gpa  = !empty($post['percentage_or_gpa']) ? $post['percentage_or_gpa'] : '';
                    $marks_sheet = !empty($post['marks_sheet']) ? $post['marks_sheet'] : '';
                    $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                    $class_id = $student_details->student_class_id;
                    $section_id  = $student_details->section_id;
                    $exams = Exams::find()->where(['id' => $exam_id])->one();
                    if (!empty($exams)) {

                        if ($exams->total_percentage_or_gpa >= $percentage_or_gpa) {
                            $exams_result_check  =  ExamsResult::find()->where(['exam_id' => $exam_id])
                                ->andWhere(['student_id' => $student_id])
                                ->andWhere(['academic_year_id' => $academic_year_id])
                                ->andWhere(['class_id' => $class_id])
                                ->andWhere(['section_id' => $section_id])
                                ->one();
                            if (!empty($exams_result_check)) {
                                $exams_result =  ExamsResult::find()->where(['exams_result_id' => $exams_result_check->exams_result_id])->one();
                            } else {
                                $exams_result = new ExamsResult();
                            }
                            $exams_result->campus_id  = $campus_id;
                            $exams_result->exam_id   = $exam_id;
                            $exams_result->academic_year_id   = $academic_year_id;
                            $exams_result->student_id   = $student_id;
                            $exams_result->class_id    = $class_id;
                            $exams_result->section_id    = $section_id;
                            $exams_result->marks_type    = $exams->marks_type;
                            $exams_result->marks_sheet    = $marks_sheet;
                            $exams_result->percentage_or_gpa    = $percentage_or_gpa;
                            $exams_result->status    = StudentDetails::STATUS_ACTIVE;
                            if ($exams_result->save(false)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $exams_result->asJson();
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Data added failed.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "max enter value  $exams->total_percentage_or_gpa";
                        }
                    } else {

                        $data['status'] = self::API_NOK;
                        $data['error'] = "Exam Data Not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
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
    public function actionViewExamResult()
    {
        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
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

    public function actionExamResultByStudentId()
    {

        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            $student_id = $post['student_id'];
            if (!empty($user_id)) {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $class_sections_exam_result = ClassSections::find()->innerJoinWith('examsResults as er')->where(['er.student_id' => $student_id])->all();
                    if (!empty($class_sections_exam_result)) {
                        foreach ($class_sections_exam_result as $class_sections_exam_result_data) {
                            $list[] = $class_sections_exam_result_data->asJsonExamResult($student_id);
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Exam result data not found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Exam result data not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
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


    // teacher payrolls

    public function actionTeacherPayrollDetails()
    {

        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            $student_id = $post['student_id'];
            if (!empty($user_id)) {
                $teacher_details = TeacherDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($teacher_details)) {
                    $staff = StaffDetails::find()->where(['user_id' => $teacher_details->user_id])->one();
                    if (empty($staff)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No data found for the staff";
                        return $this->sendJsonResponse($data);
                    } else {
                        $staffSalary = StaffSalary::find()->where(['staff_id' => $staff->id])->one();
                        if (!empty($staffSalary->ctc)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $staffSalary->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Payroll is not updated";
                        }
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
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


    public function actionTestSms()
    {
        $contact_no = "7986932720";
        $sms = "Sir/Madam,your ward Rohit did not attended SUBJECT - Maths class today Regards, Estudent";
        $sms_url = urlencode($sms);
        $template_id = '1007540324428275979';
        $sender = 'ESTDNT';
        $route = 7;
        $SendOtpData = new SendOtp();
        $send_otp = $SendOtpData->sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
        var_dump($send_otp);
        exit;
    }

    public function actionNoticeDetails()
    {
        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            if (!empty($user_id)) {

                $notice_id = $post['id'];
                $noticeBoard = BaseNoticeBoards::find()->where(['id' => $notice_id])->one();
                if (empty($noticeBoard)) {

                    $data['status'] = self::API_NOK;
                    $data['error'] = "Invalid Id";
                }
                $data['status'] = self::API_OK;
                $data['message'] = $noticeBoard->asJson();
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
    public function actionStudentDetailsById()
    {
        $data = [];
        try {
            $post = Yii::$app->request->post();
            $headers = isset(\Yii::$app->request->headers['auth_code'])
                ? \Yii::$app->request->headers['auth_code']
                : Yii::$app->request->getQueryParam('auth_code');

            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (!empty($user_id)) {
                $student_id = isset($post['student_id']) ? $post['student_id'] : null;

                if (empty($student_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student ID is required.";
                    return $this->sendJsonResponse($data);
                }

                $studentDetails = StudentDetails::find()->where(['id' => $student_id])->one();

                if (empty($studentDetails)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Invalid Student ID.";
                } else {
                    $data['status'] = self::API_OK;
                    $data['message'] = $studentDetails->asJson();
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
}
