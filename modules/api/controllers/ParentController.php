<?php

namespace app\modules\api\controllers;

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
use app\modules\admin\models\Campus;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\BusRoute;
use app\modules\admin\models\Category;
use app\modules\admin\models\BusDetails;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\LeaveRequests;
use app\modules\admin\models\LeaveTypes;
use app\modules\admin\models\ParentDetails;
use app\modules\api\controllers\BKController;
use app\modules\admin\models\StudentHasParent;
use app\modules\admin\models\StudentAttendanceBus;
use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\RazorpayLinkedAccount;
use app\modules\admin\models\StudentHasAssessment;
use app\modules\admin\models\StudentHasDairy;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\StudentNoticeBoards;
use app\modules\admin\models\NoticeBoards;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\modules\hostelmanagement\models\base\Hostels;
use DateTime;
use kartik\mpdf\Pdf;
use Exception;
use app\components\SendOtp;
use app\modules\admin\models\base\NoticeBoards as BaseNoticeBoards;
use app\modules\admin\models\base\StudentFaces;
use app\modules\admin\models\SpecialDays;
use app\modules\admin\models\StudentAssessment;
use app\modules\admin\models\UserOtp;
use DateInterval;
use yii\db\Expression;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\Exams;
use app\modules\exammanagement\models\FinalMarksheet;
use app\modules\hostelmanagement\models\HostellersAttandance;
use DatePeriod;

class ParentController extends BKController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:51276', 'http://localhost:58382/', 'http://localhost:58382', 'http://localhost:60452', 'http://localhost:56477', 'https://web.estudent.tech'],
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
                            'student-details',
                            'student-profile',
                            'verify-otp',
                            'bus-route',
                            'parent-profile',
                            'bus-details-by-id',
                            'bus-student-attendance',
                            'my-notifications',
                            'total-and-balance-fee',
                            'fee-type',
                            'pay-fee',
                            'pay-now',
                            'create-payment',
                            'fee-structure',
                            'fee-payment-history',
                            'download-fee-recept',
                            'student-class-time-table',
                            'dairy-list',
                            'view-dairy',
                            'upcoming-dairy-list',
                            'mark-as-complete',
                            'mark-as-complete-assessment',
                            'leave-types',
                            'leave-request',
                            'get-class-teacher',
                            'leave-reports',
                            'student-class-attendance-list',
                            'notice-board',
                            'upcoming-assessment-list',
                            'assessment-list',
                            'test-otp',
                            'special-days',
                            'view-assessment',
                            'get-razorpay-keys',
                            'check-payment-status',
                            'exam-result-by-student-id',
                            'exams',
                            'get-parent-students',
                            'get-parent-student-details',
                            'logout',
                            'get-student-attendence',
                            'date-attandance',
                            'exam-marksheet',
                            'mess-menu',
                            'get-notice',
                            'notice-details',
                            'leave-notice-details',
                            'delete-notifications'


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
                            'student-details',
                            'verify-otp',
                            'bus-route',
                            'parent-profile',
                            'bus-details-by-id',
                            'bus-student-attendance',
                            'my-notifications',
                            'total-and-balance-fee',
                            'fee-type',
                            'pay-fee',
                            'payment-details',
                            'create-payment',
                            'pay-now',
                            'fee-structure',
                            'fee-payment-history',
                            'download-fee-recept',
                            'student-class-time-table',
                            'dairy-list',
                            'view-dairy',
                            'mark-as-complete',
                            'leave-types',
                            'leave-request',
                            'get-class-teacher',
                            'leave-reports',
                            'student-class-attendance-list',
                            'notice-board',
                            'upcoming-dairy-list',
                            'upcoming-assessment-list',
                            'assessment-list',
                            'test-otp',
                            'special-days',
                            'view-assessment',
                            'mark-as-complete-assessment',
                            'get-razorpay-keys',
                            'check-payment-status',
                            'exam-result-by-student-id',
                            'exams',
                            'get-parent-students',
                            'get-parent-student-details',
                            'logout',
                            'get-student-attendence',
                            'date-attandance',
                            'exam-marksheet',
                            'mess-menu',
                            'get-notice',
                            'notice-details',
                            'leave-notice-details',
                            'delete-notifications'





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
        $data['details'] = ['Hello'];
        return $this->sendJsonResponse($data);
    }


    public function actionTestOtp()
    {
        $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
        $contact_no = 6300565084;
        $sms = 'Dear Bus Coordinator, 123456 is the OTP for login into Bus Coordinator App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone -DEV2CI';
        $sms_url = urlencode($sms);
        $template_id = '1707168312601935398';
        $sender = 'DEVCIT';
        $route = 7;
        $SendOtp = new SendOtp();
        $send_otp = $SendOtp->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);
        return $send_otp;
    }


    public function actionLogout()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
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

    public function actionGetRazorpayKeys($student_id = '')
    {

        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        if (!empty($user_id)) {
            if (!empty($student_id)) {
                $setting = new WebSetting();
                $razorpay_key_id = $setting->getSettingBykey('razorpay_key_id');
                $razorpay_key_secret = $setting->getSettingBykey('razorpay_key_secret');
                $razorpay['razorpay_key_id'] = $razorpay_key_id;
                $razorpay['razorpay_key_secret'] = $razorpay_key_secret;
                $data['status'] = self::API_OK;
                $data['details'] = $razorpay;
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "student_id Not Found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "User Not Found";
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
                $data['key_skill_tyro'] = false;
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
            $parent_details = ParentDetails::find()->where(['contact_number' => $contact_no])->one();

            if (!empty($parent_details)) {

                $otp = rand(1111, 9999);
                $webSetting = new WebSetting();

                $templateId = $webSetting->getSettingBykey('sms_template_id');
                $apiKey = $webSetting->getSettingBykey('sms_api_key');
                $senderId = $webSetting->getSettingBykey('sender_id');
                $otp = rand(1111, 9999);
                $key = $apiKey;
                // $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
                $sms = 'Dear Customer, Your OTP for Estudent is ' . $otp . '. Please do not share OTP with anyone. Regards, EStudent';
                $sms_url = urlencode($sms);
                $template_id = $templateId;
                // $template_id = '1707168312544700319';
                $sender = $senderId;
                $route = 7;
                $SendOtpData = new SendOtp();
                $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);

                if (strlen($send_otp) > 4) {
                    $date = date('Y-m-d H:i:s');
                    $user_otp = new UserOtp();
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
                $data['error'] = Yii::t("app", "Parent details not found");
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
            $contact_no = $post['contact_no'];
            $send_otp = Yii::$app->notification->resendOtp($contact_no);
            $send_otp = json_decode($send_otp, true);

            if ($send_otp['type'] == 'success') {
                $data['status'] = self::API_OK;
                $data['details'] = $send_otp;
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "OTP failed");
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

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $otp_code = !empty($post['otp_code']) ? $post['otp_code'] : '';

            if (empty($contact_no)) {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "Contact Details Not Found");
                return $this->sendJsonResponse($data);
            }

            $setting = new WebSetting();
            $numbers = $setting->getSettingByKey('parent_number');
            $explodeNumber = explode(',', $numbers);




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

            if ($otp_match === true) {
                $parent_details = User::find()->where(['contact_no' => $post['contact_no']])->andWhere(['user_role' => User::ROLE_PARENT])->one();
                // var_dump($post['User::ROLE_PARENT']);
                //     exit;
                if (!empty($parent_details)) {
                    $providerId = User::ROLE_PARENT;
                    $number = $contact_no;
                    $auth_id = $number;
                    $auth = Auth::find()->where(['source' => $providerId, 'source_id' => $post['contact_no']])->one();

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
                        $auth->user_id = $parent_details->id;
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
                            $data['error'] = $auth->getErrors();
                        }
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Details not found contact to admin");
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", $msg);
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No  Data Posted");
        }
        return $this->sendJsonResponse($data);
    }




    public function actionStudentDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();

            if (!empty($parent_details)) {
                $student_details = StudentDetails::find()->where(['parent_id' => $parent_details->id])->all();

                if (!empty($student_details)) {
                    $student_details_data_all = [];

                    foreach ($student_details as $student_details_data) {

                        $studentDataJson = $student_details_data->asJson();
                        //add below line for check skill tyro 
                        // $studentDataJson['key_skill_tyro'] = ($student_details_data->campus_id == 120) ? true : false;
                        $studentDataJson['key_skill_tyro'] = false;

                        $student_details_data_all[] = $studentDataJson;
                    }

                    if (!empty($student_details_data_all)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $student_details_data_all;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Data Not Exist.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Data Not Exist.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Parent details not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }

        return $this->sendJsonResponse($data);
    }



    public function actionStudentProfile()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';

            if (!empty($student_id)) {
                // Find student details
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    // Check if student_id exists in student_faces
                    $student_face_registered = StudentFaces::find()->where(['student_id' => $student_id])->exists();

                    $data['status'] = self::API_OK;
                    $data['details'] = $student_details->asJson();
                    $data['student_face_registered'] = $student_face_registered; // true if found, false otherwise
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Details Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student Id Required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }

        return $this->sendJsonResponse($data);
    }






    public function actionParentProfile()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $user = User::find()->where(['id' => $user_id])->one();
            if (!empty($user)) {
                $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($parent_details)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $parent_details->asJson();
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "parent details not   found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Profile found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionBusRoute($bus_id = '', $startDate = '', $student_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                if (!empty($startDate)) {
                    $start = date($startDate . ' 00:00:00');
                    $end = date($startDate . ' H:i:s');
                } else {
                    $start = date('y-m-d 00:00:00');
                    $end = date('y-m-d H:i:s');
                }

                if (!empty($student_id)) {
                    $bus_details_data = BusDetails::find()->where(['id' => $bus_id])->one();
                    if ($bus_details_data->status_direction == BusDetails::status_direction_school) {

                        $bus_route = BusRoute::find()
                            ->joinWith(['busStatuses'])
                            ->where(['bus_id' => $bus_id])
                            ->orderBy(['short_order' => SORT_ASC])
                            ->all();
                    } else {
                        $bus_route = BusRoute::find()
                            ->joinWith(['busStatuses'])
                            ->where(['bus_id' => $bus_id])
                            ->orderBy(['short_order' => SORT_DESC])
                            ->all();
                    }



                    $busDetails = BusDetails::find()->joinWith('studentHasBuses')
                        ->where(['student_has_bus.student_id' => $student_id])
                        ->andWhere(['bus_details.status' => BusDetails::current_status_active])
                        ->one();

                    if (!empty($busDetails)) {
                        if ($busDetails->status == BusDetails::STATUS_DRIVE_MODE) {
                            if (!empty($bus_route)) {
                                foreach ($bus_route as $bus_route_data) {
                                    $bus_route_data_arr[] = $bus_route_data->asJsonByDateParentLive($student_id);
                                }
                                if (!empty($bus_route_data_arr)) {
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $bus_route_data_arr;
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Bus Route Details Not found.";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Bus Route Details Not found.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Bus Is Parking Mode.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Is Inactive.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Id Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Bus Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionBusStudentAttendance($bus_id = '', $startDate = '', $student_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                if (!empty($startDate)) {
                    $start = date($startDate . ' 00:00:00');
                    $end = date($startDate . ' H:i:s');
                } else {
                    $start = date('y-m-d 00:00:00');
                    $end = date('y-m-d H:i:s');
                }


                if (!empty($student_id)) {
                    $student_has_bus = StudentHasBus::find()->where(['bus_id' => $bus_id])->andWhere(['student_id' => $student_id])->one();
                    if (!empty($student_has_bus)) {
                        $student_has_bus_id = $student_has_bus->id;
                        $student_attendance_bus = StudentAttendanceBus::find()
                            ->where(['student_has_bus_id' => $student_has_bus_id])
                            ->andWhere(['between', 'created_on', $start, $end])->one();

                        if (!empty($student_attendance_bus)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $student_attendance_bus->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Data Not Found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Id With bus Id Not Matched.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Id Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Bus Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }










    public function actionBusDetailsById($bus_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                $bus_details = BusDetails::find()->where(['id' => $bus_id])->one();
                if (!empty($bus_details)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $bus_details->asJson();
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Data not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "bus_id Required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionMyNotifications()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $page = isset($post['page']) ? $post['page'] : 0;
            $query = FcmNotification::find()->where(['user_id' => $user_id]);
            $notifications = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
                'pagination' => [
                    'pageSize' => 20,
                    'page' => $page,
                ],
            ]);

            foreach ($notifications->models as $notifications_data) {
                $notifications_data_arr[] = $notifications_data->asJson();
            }
            if (!empty($notifications_data_arr)) {
                $data['status'] = self::API_OK;
                $data['details'] = $notifications_data_arr;
                $data['details'] = $notifications_data_arr;
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Notifications.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionDeleteNotifications()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            // Get the notification IDs to delete (this can be passed in the POST request)


            if (!empty($user_id)) {
                // Delete notifications belonging to the user
                $deletedCount = FcmNotification::deleteAll(['user_id' => $user_id,]);

                if ($deletedCount > 0) {
                    $data['status'] = self::API_OK;
                    $data['message'] = "{$deletedCount} notifications deleted.";
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No notifications were deleted.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No notification IDs provided.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }

        return $this->sendJsonResponse($data);
    }

    public function actionTotalAndBalanceFee()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    $pay_fees = PayFees::find()
                        ->joinWith(['feeStructures'])
                        ->where(['student_id' => $student_id])->all();

                    $payFee['total_fee'] = 0;
                    $payFee['balance'] = 0;
                    $payFee['paid_fee'] = 0;

                    if (!empty($pay_fees)) {
                        foreach ($pay_fees as $pay_fees_data) {
                            $fee_structures = FeeStructures::find()->where(['id' => $pay_fees_data->fee_structures_id])->one();
                            $fee[] = $fee_structures->fee;
                            $maximum_detuction[] = $fee_structures->maximum_detuction;
                            $fees_cut_student[] = $pay_fees_data->fees_cut;
                        }
                        $fee_array_sum = array_sum($fee);
                        $fees_cut_student_array_sum = array_sum($fees_cut_student);
                        $total_fee = $fee_array_sum - $fees_cut_student_array_sum;
                        //check student paid amount
                        $student_class_id = $student_details->student_class_id;
                        $section_id = $student_details->section_id;
                        $payment_details = PaymentDetails::find()->where(['student_id' => $student_id])
                            ->andWhere(['section_id' => $section_id])
                            ->andWhere(['class_id' => $student_class_id])
                            ->andWhere(['status' => PaymentDetails::status_success])
                            ->all();
                        if (!empty($payment_details)) {
                            foreach ($payment_details as $payment_details_data) {
                                $paid_amount_arr[] = $payment_details_data->paid_amount;
                            }

                            if (!empty($paid_amount_arr)) {
                                $balance_sum = array_sum($paid_amount_arr);
                                $payFee['balance'] = $total_fee - $balance_sum;
                            } else {
                                $payFee['balance'] = $total_fee;
                            }
                        } else {
                            $payFee['balance'] = $total_fee;
                        }
                        $payFee['total_fee'] = $total_fee;
                        $payFee['paid_fee'] = $total_fee - $payFee['balance'];


                        $data['status'] = self::API_OK;
                        $data['details'] = $payFee;
                        $checkForRazorPayAccount = RazorpayLinkedAccount::find()->where(['campus_id' => $student_details->campus_id])->andWhere(['account_status' => 'created'])->one();
                        if (!empty($checkForRazorPayAccount)) {
                            $data['details']['online_payment_enabled'] = true;
                        } else {
                            $data['details']['online_payment_enabled'] = false;
                        }
                    } else {
                        $data['status'] = self::API_OK;
                        $data['details'] = $payFee;
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Details Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student Id Required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionFeeType()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->andWhere(['status' => StudentDetails::STATUS_ACTIVE])->one();
                if (!empty($student_details)) {
                    $student_class_id = $student_details->student_class_id;
                    $section_id = $student_details->section_id;
                    $fee_structures = FeeStructures::find()->where(['student_class_id' => $student_class_id])->andWhere(['class_section_id' => $section_id])->all();
                    if (!empty($fee_structures)) {
                        foreach ($fee_structures as $fee_structures_data) {
                            $list[] = $fee_structures_data->asJson();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Fee data Not Found Found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Fee data Not Found Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Details Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student Id Required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionPayFee()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            $fee_structures_id = isset($post['fee_structures_id']) ? $post['fee_structures_id'] : '';

            // if (!empty($student_id)  && !empty($fee_structures_id)) {
            // } else {
            //     $data['status'] = self::API_NOK;
            //     $data['error'] = "Student Id and fee structures id Required.";
            // }

            $student_details = StudentDetails::find()->where(['id' => $student_id])->andWhere(['status' => StudentDetails::STATUS_ACTIVE])->one();
            if (!empty($student_details)) {
                $pay_fees = PayFees::find()->where(['student_id' => $student_id])->andWhere(['fee_structures_id' => $fee_structures_id])->one();
                if (!empty($pay_fees)) {
                    //fee structures
                    $fee_structures = FeeStructures::find()->where(['id' => $pay_fees->fee_structures_id])->one();
                    if (!empty($fee_structures)) {
                        $student_class_id = $student_details->student_class_id;
                        $section_id = $student_details->section_id;

                        //check  paid amount
                        $paid = (new PaymentDetails())->getPaidAmount($student_id, $student_class_id, $section_id, $pay_fees->id);
                        $pay_fee = $fee_structures->fee - $pay_fees->fees_cut;
                        $pay_able_amount = $pay_fee - $paid;

                        $pay['total_amount'] = $pay_fee;
                        $pay['pay_fee'] = $pay_able_amount;
                        $pay['student_id'] = $student_id;
                        $pay['pay_fees_id'] = $pay_fees->id;
                        $data['status'] = self::API_OK;
                        $data['details'] = $pay;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Pay Fee Structure Data Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Pay Fee Data Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student Details Not Found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionPayNow()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {






            $razorpay_order_id = !empty($post['razorpay_order_id']) ? $post['razorpay_order_id'] : '';
            $razorpay_payment_id = !empty($post['razorpay_payment_id']) ? $post['razorpay_payment_id'] : '';

            if (!empty($razorpay_order_id) && !empty($razorpay_payment_id)) {
                $payment_details_update = PaymentDetails::find()->where(['razorpay_order_id' => $razorpay_order_id])->one();

                if (!empty($payment_details_update)) {
                    $RazorPay = new RazorPay();
                    $checkPaymentByPayId = $RazorPay->checkPaymentByPayId($razorpay_payment_id);
                    $paymentStatus = json_decode($checkPaymentByPayId);
                    if (!empty($paymentStatus->status) && ($paymentStatus->status == 'authorized' || $paymentStatus->status == 'captured')) {


                        if ($paymentStatus->status == 'authorized') {
                            $CapturePayment = json_decode($RazorPay->CapturePayment($paymentStatus->amount, $razorpay_payment_id));
                        }
                        $checkPaymentByPayIdCheck = json_decode($RazorPay->checkPaymentByPayId($razorpay_payment_id));

                        if (!empty($checkPaymentByPayIdCheck->status) && $checkPaymentByPayIdCheck->status == 'captured') {
                            $payment_details_update->razorpay_payment_id = $razorpay_payment_id;
                            $payment_details_update->status = PaymentDetails::status_success;
                            if ($payment_details_update->save(false)) {
                                $pay_fees_id = $payment_details_update->pay_fees_id;
                                if ($payment_details_update->status == PaymentDetails::status_success) {
                                    $payment_details_update->status = PaymentDetails::status_success;
                                    $payment_details_update->save(false);
                                    //update balance fee
                                    $pay_fees_update = PayFees::find()->where(['id' => $pay_fees_id])->one();
                                    $balance = ParentDetails::getBalanceAmount($pay_fees_update->student_id, $pay_fees_update->student->student_class_id, $pay_fees_update->student->section_id, $pay_fees_id);
                                    $pay_fees_update->balance_fee = $balance;
                                    $pay_fees_update->save(false);
                                    $payment_details_update->balance_amount = $balance;
                                    $payment_details_update->save(false);

                                    $fee_type = !empty($payment_details_update->payFees->feeStructures->title) ? $payment_details_update->payFees->feeStructures->title : 'Fee Type Not Set';
                                    $contact_no = $pay_fees_update->student->parent->contact_number;
                                    $sms = "Dear Sir/Madam, Received a Payment of Rs. $payment_details_update->paid_amount/- and the Balance left is Rs. $balance/-($fee_type). FOR TESTING PURPOSE -DEV2CI";
                                    $sms_url = urlencode($sms);
                                    $template_id = '1707169175227222849';
                                    $sender = 'DEVCIT';
                                    $route = 7;
                                    $SendOtpData = new SendOtp();
                                    $send_otp = $SendOtpData->sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
                                    if (strlen($send_otp) > 4) {
                                    }
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $payment_details_update->asJson();
                                }
                            } else {

                                $data['status'] = self::API_NOK;
                                $data['error'] = "Payment Update failed.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Payment captured failed.";
                        }
                    } else {
                        $title = 'Payment authorized Failed';
                        $body = 'Your Payment Failed  ' . $payment_details_update->paid_amount;
                        $type = '';
                        Yii::$app->notification->UserNotification('', $user_id, $title, $body, $type);
                        $payment_details_update->razorpay_payment_id = $razorpay_payment_id;
                        $payment_details_update->status = PaymentDetails::status_failed;
                        $payment_details_update->save(false);
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Payment Failed.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "razorpay  order id and  Not In  Our Data Base.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "razorpay  order id and razorpay payment id  Required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionCreatePayment()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $post = Yii::$app->request->post();
            $orderAmount = isset($post['amount']) ? $post['amount'] : $err[] = 'amount is required';
            $pay_fees_id = isset($post['pay_fees_id']) ? $post['pay_fees_id'] : $err[] = 'pay fees id required';
            $student_id = isset($post['student_id']) ? $post['student_id'] : $err[] = 'student id required';
            $remarks = isset($post['remarks']) ? $post['remarks'] : '';
            $fee_receipt = isset($post['fee_receipt']) ? $post['fee_receipt'] : '';
            $payment_mode = isset($post['payment_mode']) ? $post['payment_mode'] : $err[] = 'payment mode required';
            if (empty($err)) {
                //get student Details

                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                $student_class_id = $student_details->student_class_id;
                $section_id = $student_details->section_id;
                $pay_fees = PayFees::find()->where(['id' => $pay_fees_id])->one();
                if (!empty($pay_fees)) {
                    $fee_structures_id = $pay_fees->fee_structures_id;
                    $fee_structures = FeeStructures::find()->where(['id' => $fee_structures_id])->one();
                    $fee = $fee_structures->fee;
                    $fees_cut = $pay_fees->fees_cut;
                    $firstPayAmount = $fee - $fees_cut;
                    //check amount paid or not
                    $payment_details = PaymentDetails::find()
                        ->select("payment_details.paid_amount, payment_details.balance_amount,payment_details.pay_fees_id")
                        ->joinWith(['payFees'])
                        ->where(['payment_details.student_id' => $student_id])
                        ->andWhere(['payment_details.class_id' => $student_class_id])
                        ->andWhere(['payment_details.section_id' => $section_id])
                        ->andWhere(['payment_details.pay_fees_id' => $pay_fees_id])
                        ->andWhere(['payment_details.status' => PaymentDetails::status_success])
                        ->sum('payment_details.paid_amount');
                    $pay_amount = $firstPayAmount - $payment_details;


                    if (!empty($orderAmount) && !empty($payment_mode)) {
                        if ($orderAmount <= $pay_amount && $pay_amount > 0) {
                            if ($payment_mode == PaymentDetails::payment_mode_online) {
                                $raorPay = new RazorPay();
                                $createOrder = $raorPay->CreateOrder($orderAmount);
                                if (!empty($createOrder)) {
                                    $createOrd = json_decode($createOrder);
                                    if (!empty($createOrd) && empty($createOrd->error)) {
                                        $getCampusByStudentId = (new Campus())->getCampusByStudentId($student_id);
                                        $payment_details = new PaymentDetails();
                                        $payment_details->campus_id = $getCampusByStudentId;
                                        $payment_details->student_id = $student_id;
                                        $payment_details->class_id = $student_class_id;
                                        $payment_details->section_id = $section_id;
                                        $payment_details->pay_fees_id = $pay_fees_id;
                                        $payment_details->paid_reference_number = rand(11111111, 99999999);
                                        $payment_details->status = PaymentDetails::status_pending;
                                        $payment_details->paid_amount = $orderAmount;
                                        $payment_details->razorpay_order_id = $createOrd->id;
                                        $payment_details->remarks = $remarks;
                                        $payment_details->payment_mode = $payment_mode;
                                        $payment_details->save(false);
                                        $data['status'] = self::API_OK;
                                        $data['details'] = $createOrd;
                                    } else {
                                        $data['status'] = self::API_NOK;
                                        $data['error'] = !empty($createOrd->error->description) ? $createOrd->error->description : 'Create payment failed';
                                    }
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Orders not created";
                                }
                            } elseif ($payment_mode == PaymentDetails::payment_mode_offline || $payment_mode == PaymentDetails::payment_mode_net_banking) {
                                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                                $student_class_id = $student_details->student_class_id;
                                $section_id = $student_details->section_id;
                                $getCampusByStudentId = (new Campus())->getCampusByStudentId($student_id);
                                $payment_details = new PaymentDetails();
                                $payment_details->campus_id = $getCampusByStudentId;
                                $payment_details->student_id = $student_id;
                                $payment_details->class_id = $student_class_id;
                                $payment_details->section_id = $section_id;
                                $payment_details->pay_fees_id = $pay_fees_id;
                                $payment_details->fee_receipt = $fee_receipt;
                                $payment_details->paid_reference_number = rand(11111111, 99999999);
                                $payment_details->status = PaymentDetails::status_pending;
                                $payment_details->paid_amount = $orderAmount;
                                $payment_details->razorpay_order_id = '';
                                $payment_details->payment_mode = $payment_mode;
                                $payment_details->remarks = $remarks;
                                if ($payment_details->save(false)) {
                                    $title = 'Payment Pending';
                                    $body = 'Your Payment Pending ' . $payment_details->paid_amount . '/-';
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $user_id, $title, $body, $type);
                                    //send conformation sms to phone
                                    $parent_number = StudentDetails::getPatentNumberByStudentId($student_id);
                                    $arr_var_data = [];
                                    $arr_var_data['VAR1'] = $payment_details->paid_amount;
                                    $arr_var_data['VAR2'] = $student_details->student_name;
                                    $sms = Yii::$app->notification->sendSMSDynamicTemplateV2($parent_number, 'Counter Pay Payment', $arr_var_data);
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $payment_details->asJson();
                                }
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Pay Amount is " . $pay_amount;
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "student id and and payment mode and amount required";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Pay Fee Details Not Found";
                }
            } else {
                $errors = '';
                foreach ($err as $err_data) {
                    $errors .= $err_data . ',';
                }
                $data['status'] = self::API_NOK;
                $data['error'] = $errors;
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "User Not Found";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionFeeStructure()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';

            if (!empty($student_id)) {
                $pay_fees = FeeStructures::find()->innerJoinWith('payFees')->where(['pay_fees.student_id' => $student_id])->all();
                if (!empty($pay_fees)) {
                    foreach ($pay_fees as $pay_fees_data) {
                        $list[] = $pay_fees_data->asJson($student_id);
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Data Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student id required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionFeePaymentHistory()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {

            try {



                $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
                if (!empty($student_id)) {
                    $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                    if (!empty($student_details)) {
                        $student_class_id = $student_details->student_class_id;
                        $section_id = $student_details->section_id;
                        $payment_details = PaymentDetails::find()
                            ->where(['student_id' => $student_id])
                            ->andWhere(['class_id' => $student_class_id])
                            ->andWhere(['section_id' => $section_id])
                            ->all();
                        if (!empty($payment_details)) {
                            foreach ($payment_details as $payment_details_data) {
                                $list[] = $payment_details_data->asJson();
                            }
                            if (!empty($list)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $list;
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Details Not Found.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Payment Details Not Found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student  Details Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student id required.";
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

    public function actionDownloadFeeRecept($id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($id)) {
                $payment_details = PaymentDetails::find()
                    ->where(['id' => $id])
                    ->one();
                $model = new PaymentDetails();
                if (!empty($payment_details)) {
                    $content = $this->renderPartial('_reportView', [
                        'payment_details' => $payment_details,
                    ]);

                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE,
                        'format' => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_DOWNLOAD,
                        'content' => $content,
                        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.css',
                        'cssInline' => '.kv-heading-1{font-size:18px}',
                        'options' => ['title' => 'Fee Balance'],
                        'methods' => [
                            'SetHeader' => ['Fee Balance'],
                            'SetFooter' => ['{PAGENO}'],
                        ]
                    ]);

                    $file = $pdf->render();
                    $filename = 'fee_recept.pdf';
                    return Yii::$app->response->sendContentAsFile($file, $filename)->send();
                    $data['status'] = self::API_OK;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Payment Details Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Payment Details Not Found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionStudentClassTimeTable()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            $date = !empty($post['date']) ? $post['date'] : '';
            if (!empty($date)) {
                $day_id = date('l', strtotime($date));
                if (!empty($student_id)) {
                    $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                    if (!empty($student_details)) {
                        $student_class_id = $student_details->student_class_id;
                        $section_id = $student_details->section_id;
                        $academic_year_id = $student_details->academic_year_id;
                        $subject_timetable = SubjectTimetable::find()->where(['class_id' => $student_class_id])->andWhere(['section_id' => $section_id])
                            ->andWhere(['academic_year_id' => $academic_year_id])
                            ->andWhere(['day_id' => $day_id])
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
                                $data['error'] = "Student Time table not found.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Time table not found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student  Details Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student id required.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Date is required.";
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
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            $date = !empty($post['date']) ? $post['date'] : '';
            $page = !empty($post['page']) ? $post['page'] : 0;

            if (!empty($date)) {
                $query = StudentHasDairy::find()->where(['student_id' => $student_id])->andWhere(['date' => $date]);

                $StudentHasDairy = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ],
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                        'page' => $page,
                    ],
                ]);

                if (!empty($StudentHasDairy)) {
                    foreach ($StudentHasDairy->models as $StudentHasDairyData) {
                        $list[] = $StudentHasDairyData->asJsonInParent();
                    }
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
                $data['error'] = "Date is required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionUpcomingDairyList()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            $date = new DateTime('+1 day');
            $date_one_day = $date->format('Y-m-d');
            $date_thirty_days = new DateTime('+30 day');
            $date_thirty_day = $date_thirty_days->format('Y-m-d');



            if (!empty($date)) {
                $StudentHasDairy = StudentHasDairy::find()->innerJoinWith('studentDairy')
                    ->where(['student_id' => $student_id])
                    ->andWhere(['between', 'student_dairy.submission_date', $date_one_day, $date_thirty_day])->all();


                if (!empty($StudentHasDairy)) {
                    foreach ($StudentHasDairy as $StudentHasDairyData) {
                        $list[] = $StudentHasDairyData->asJsonInParent();
                    }
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
                $data['error'] = "Date is required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionUpcomingAssessmentList()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = isset($post['student_id']) ? $post['student_id'] : '';
            $date = new DateTime('+1 day');
            $date_one_day = $date->format('Y-m-d');
            $date_thirty_days = new DateTime('+30 day');
            $date_thirty_day = $date_thirty_days->format('Y-m-d');
            if (!empty($date)) {
                $StudentHasDairy = StudentHasAssessment::find()->innerJoinWith('studentAssessment')
                    ->where(['student_has_assessment.student_id' => $student_id])
                    ->andWhere(['between', 'student_assessment.submission_date', $date_one_day, $date_thirty_day])->all();


                if (!empty($StudentHasDairy)) {
                    foreach ($StudentHasDairy as $StudentHasDairyData) {
                        $list[] = $StudentHasDairyData->asJson();
                    }
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
                $data['error'] = "Date is required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionAssessmentList()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {

            try {


                $student_id = isset($post['student_id']) ? $post['student_id'] : '';
                $page = !empty($post['page']) ? $post['page'] : 0;
                $date = !empty($post['date']) ? $post['date'] : 0;

                $query = StudentHasAssessment::find()->innerJoinWith('studentAssessment')
                    ->where(['student_has_assessment.student_id' => $student_id]);
                if (!empty($date)) {
                    $query->andWhere(['student_has_assessment.date' => $date]);
                }
                $StudentHasDairy = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ],
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                        'page' => $page,
                    ],
                ]);
                if (!empty($StudentHasDairy)) {
                    foreach ($StudentHasDairy->models as $StudentHasDairyData) {
                        $list[] = $StudentHasDairyData->asJson();
                    }
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









    public function actionViewDairy()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            $student_has_dairy_id = !empty($post['student_has_dairy_id']) ? $post['student_has_dairy_id'] : '';
            $student_has_dairy = StudentHasDairy::find()->where(['id' => $student_has_dairy_id])->one();
            if (!empty($student_has_dairy)) {
                $student_has_dairy->is_read = StudentHasDairy::is_read_yes;
                $student_has_dairy->save(false);
                $data['status'] = self::API_OK;
                $data['details'] = $student_has_dairy->asJsonInParent();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Dairy data not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionViewAssessment()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            $assignment_details_id = !empty($post['assignment_details_id']) ? $post['assignment_details_id'] : '';
            $student_has_assessment = StudentHasAssessment::find()->where(['id' => $assignment_details_id])->one();
            if (!empty($student_has_assessment)) {
                $student_has_assessment->is_read = StudentHasAssessment::is_read_yes;
                $student_has_assessment->save(false);
                $data['status'] = self::API_OK;
                $data['details'] = $student_has_assessment->asJson();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Assignment data not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }










    public function actionMarkAsComplete()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            $student_has_dairy_id = !empty($post['student_has_dairy_id']) ? $post['student_has_dairy_id'] : '';
            $student_has_dairy = StudentHasDairy::find()->where(['id' => $student_has_dairy_id])->one();
            if (!empty($student_has_dairy)) {
                $student_has_dairy->status = StudentHasDairy::STATUS_COMPLETED;
                $student_has_dairy->save(false);
                $data['status'] = self::API_OK;
                $data['details'] = $student_has_dairy->asJsonInParent();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Dairy data not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionMarkAsCompleteAssessment()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            $student_has_assessment_id = !empty($post['student_has_assessment_id']) ? $post['student_has_assessment_id'] : '';
            $StudentHasAssessment = StudentHasAssessment::find()->where(['id' => $student_has_assessment_id])->one();
            if (!empty($StudentHasAssessment)) {
                $StudentHasAssessment->status = StudentHasAssessment::STATUS_COMPLETED;
                $StudentHasAssessment->save(false);
                $data['status'] = self::API_OK;
                $data['details'] = $StudentHasAssessment->asJson();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Assessment data not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionLeaveTypes()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
        if (!empty($user_id)) {
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    $campus_id = $student_details->campus_id;
                    $leave_types = LeaveTypes::find()->where(['status' => LeaveTypes::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->all();
                    if (!empty($leave_types)) {
                        foreach ($leave_types as $leave_types_data) {
                            $list[] = $leave_types_data->asJson();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "leave type data not found";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "leave type data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student details not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student id required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionGetClassTeacher()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
        if (!empty($user_id)) {
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();

                if (!empty($student_details)) {

                    $student_class_id = $student_details->student_class_id;
                    $section_id = $student_details->section_id;

                    $teacher_details = TeacherDetails::find()->where(["class_id" => $student_class_id])->andWHere(['section_id' => $section_id])->one();

                    if (!empty($teacher_details)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = array('name' => $teacher_details->name);
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No class teacher found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student details not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student id required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionLeaveRequest()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $student_id = !empty($post['student_id']) ? $post['student_id'] : '';

        if (!empty($user_id)) {

            try {


                if (!empty($student_id)) {
                    $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                    if (!empty($student_details)) {

                        $student_class_id = $student_details->student_class_id;
                        $section_id = $student_details->section_id;
                        $academic_year_id = $student_details->academic_year_id;

                        $teacher_details = TeacherDetails::find()->where(["class_id" => $student_class_id])->andWHere(['section_id' => $section_id])->one();
                        // return $teacher_details;
                        // exit;



                        $student_id = $student_details->id;
                        $leave_type_id = !empty($post['leave_type_id']) ? $post['leave_type_id'] : '';
                        $from_date = !empty($post['from_date']) ? $post['from_date'] : '';
                        $to_date = !empty($post['to_date']) ? $post['to_date'] : '';
                        $leave_reason = !empty($post['leave_reason']) ? $post['leave_reason'] : '';
                        $class_teacher_id = $teacher_details->id;
                        $document = !empty($post['document']) ? $post['document'] : '';

                        $leave_requests = new LeaveRequests();
                        $leave_requests->student_id = $student_id;
                        $leave_requests->leave_type_id = $leave_type_id;
                        $leave_requests->academic_year_id = $academic_year_id;
                        $leave_requests->from_date = $from_date;
                        $leave_requests->to_date = $to_date;
                        $leave_requests->leave_reason = $leave_reason;
                        $leave_requests->class_teacher_id = $class_teacher_id;
                        $leave_requests->document = $document;
                        $leave_requests->status = LeaveRequests::STATUS_PENDING;
                        if ($leave_requests->save(false)) {

                            $title = 'New Leave Request';
                            $body = "You received a new leave request";
                            $type = '';
                            Yii::$app->notification->UserNotification('', $teacher_details->user_id, $title, $body, $type, 'student_leave', $leave_requests->id);

                            $data['status'] = self::API_OK;
                            $data['details'] = $leave_requests->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Leave update failed.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student details not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student id required.";
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


    public function actionLeaveReports()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
        if (!empty($user_id)) {
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();

                if (!empty($student_details)) {
                    $academic_year_id = $student_details->academic_year_id;

                    $leave_requests = LeaveRequests::find()->where(['student_id' => $student_id])->andWhere(['academic_year_id' => $academic_year_id])->all();

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
                    $data['error'] = "Student details not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student id required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionStudentClassAttendanceList()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            $date = !empty($post['date']) ? $post['date'] : '';


            if (!empty($student_id)) {

                $student_class_attendance = StudentClassAttendance::find()
                    ->innerJoinWith('student')
                    ->andWhere(['student_class_attendance.student_id' => $student_id])
                    ->andWhere(['student_class_attendance.date' => $date])->all();



                if (!empty($student_class_attendance)) {
                    foreach ($student_class_attendance as $student_class_attendance_data) {
                        $list[] = $student_class_attendance_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
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
                $data['error'] = "student_id required";
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
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $student_notice_boards_global = [];
            $student_notice_boards_global_no = [];
            $mergedNotices = [];

            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
            if (!empty($student_id)) {
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    $section_id = $student_details->section_id;
                    $date = date('Y-m-d');

                    $student_notice_boards_global = StudentNoticeBoards::find()->where(['section_id' => $section_id])
                        ->andWhere(['>', 'expiry_date', $date])
                        ->andWhere(['status' => StudentNoticeBoards::STATUS_ACTIVE])
                        ->andWhere(['is_global' => StudentNoticeBoards::is_global_yes])
                        ->all();

                    $student_notice_boards_global_no = StudentNoticeBoards::find()->innerJoinWith('studentHasNotices as shn')->where(['section_id' => $section_id])
                        ->andWhere(['shn.student_id' => $student_id])
                        ->andWhere(['student_notice_boards.status' => StudentNoticeBoards::STATUS_ACTIVE])
                        ->andWhere(['>', 'expiry_date', $date])
                        ->andWhere(['is_global' => StudentNoticeBoards::is_global_no])
                        ->all();

                    $indevidualNotice = NoticeBoards::find()->where(['>', 'expiry_date', $date])
                        ->andWhere(['student_id' => $student_id])
                        ->andWhere(['status' => StudentNoticeBoards::STATUS_ACTIVE])
                        ->all();

                    foreach ($student_notice_boards_global as $student_notice_boards_data) {
                        $mergedNotices[] = $student_notice_boards_data->asJson();
                    }

                    foreach ($student_notice_boards_global_no as $student_notice_boards_data) {
                        $mergedNotices[] = $student_notice_boards_data->asJson();
                    }

                    foreach ($indevidualNotice as $notice) {
                        $mergedNotices[] = $notice->asnJsonNew();
                    }

                    // Sort merged notices by 'created_on' in descending order
                    usort($mergedNotices, function ($a, $b) {
                        return strtotime($b['created_on']) <=> strtotime($a['created_on']);
                    });

                    if (!empty($mergedNotices)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $mergedNotices;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Data not found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student details not found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "student_id required";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
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
            $student_id = !empty($post['student_id']) ? $post['student_id'] : '';

            if (!empty($student_id)) {

                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                if (!empty($student_details)) {
                    $startDate = new \DateTime();
                    $endDate = clone $startDate;
                    $endDate->add(new \DateInterval('P30D'));


                    $campus_id = $student_details->campus_id;
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
                    $data['error'] = "student data  not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "student_id required.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionCheckPaymentStatus()
    {
        $twelveHoursAgo = new Expression('NOW() - INTERVAL 12 HOUR');

        $payment_details = PaymentDetails::find()
            ->where(['status' => PaymentDetails::status_pending])
            ->andWhere(['<', 'created_on', $twelveHoursAgo])
            ->all();


        if (!empty($payment_details)) {
            foreach ($payment_details as $payment_details_data) {
                $payment_details_data->status = PaymentDetails::status_failed;
                $payment_details_data->save(false);
            }
        }
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
                $student_id = $post['student_id'];
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                $campus_id = $student_details->campus_id;
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
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }

    public function actionGetParentStudents()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Auth Not Found";
            return $this->sendJsonResponse($data);
        }

        if (!empty($user_id)) {
            try {
                $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();

                $parent_students = StudentDetails::find()->where(['parent_id' => $parent_details->id])->all();

                if (empty($parent_students)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Students Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $list = [];
                    foreach ($parent_students as $results) {
                        $list[] = $results->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'api');
                $data['status'] = self::API_NOK;
                $data['error'] = "An error occurred while processing the request.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid user or data";
            return $this->sendJsonResponse($data);
        }
        return $this->sendJsonResponse($data);
    }

    public function actionGetParentStudentDetails()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $student_id = $post['student_id'];
        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Auth Not Found";
            return $this->sendJsonResponse($data);
        }
        if (empty($student_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid Student Id";
            return $this->sendJsonResponse($data);
        }

        if (!empty($user_id) && !empty($student_id)) {
            try {
                $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();
                if (empty($parent_details)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Parent not found";
                    return $this->sendJsonResponse($data);
                }
                $parent_students = StudentDetails::find()->where(['parent_id' => $parent_details->id])->andWhere(['user_id' => $student_id])->all();

                if (empty($parent_students)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Students Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $list = [];
                    foreach ($parent_students as $results) {
                        $list = $results->studentDetailParent();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'api');
                $data['status'] = self::API_NOK;
                $data['error'] = "An error occurred while processing the request.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid user or data";
            return $this->sendJsonResponse($data);
        }

        return $this->sendJsonResponse($data);
    }

    public function actionGetStudentAttendence()
    {
        $data = [];
        $array = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $post = Yii::$app->request->post();
        $student_id = $post['student_id'];
        $to_date = !empty($post['to_date']) ? $post['to_date'] : '';
        $from_date = !empty($post['from_date']) ? $post['from_date'] : '';
        $page = isset($post['page']) ? $post['page'] : 0;
        if (empty($user_id) || empty($student_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid user or data";
            return $this->sendJsonResponse($data);
        }

        $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();
        if (empty($parent_details)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Parent not found";
            return $this->sendJsonResponse($data);
        }

        // try {
        $studentDetail = StudentDetails::find()->where(['parent_id' => $parent_details->id])->one();
        if (empty($studentDetail)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid Student or parent id";
            return $this->sendJsonResponse($data);
        } else {

            $hostellerAttandance = HostellersAttandance::find()->where(['student_id' => $studentDetail->user_id]);
            if (!empty($from_date) && !empty($to_date)) {
                $hostellerAttandance = $hostellerAttandance->andWhere(['between', 'Date(date)', $from_date, $to_date]);
            } else {
                $hostellerAttandance;
            }

            $attandance = new ActiveDataProvider([
                'query' => $hostellerAttandance,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
                'pagination' => [
                    'pageSize' => 20,
                    'page' => $page,
                ],
            ]);

            foreach ($attandance->models as $attand) {
                $array[] = $attand->asJsonForAttendenceHistory();
            }
            if (!empty($array)) {
                $data['status'] = self::API_OK;
                $data['details'] = $array;
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Attendence data for the student.";
            }
        }
        // } catch (Exception $e) {
        //     Yii::error($e->getMessage(), 'api');
        //     $data['status'] = self::API_NOK;
        //     $data['error'] = "An error occurred while processing the request.";
        // }

        return $this->sendJsonResponse($data);
    }

    public function actionDateAttandance()
    {
        $data = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        try {
            $student_id = $post['student_id'];
            $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();

            if (empty($parent_details)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "Parent not found";
                return $this->sendJsonResponse($data);
            }

            $year = isset($post['year']) ? $post['year'] : date('Y'); // Use the provided year or the current year if not specified

            $studentDetail = StudentDetails::find()->where(['parent_id' => $parent_details->id])->andWhere(['user_id' => $student_id])->one();

            if (empty($studentDetail)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "Invalid Student or parent id";
                return $this->sendJsonResponse($data);
            }

            $attendanceData = HostellersAttandance::find()
                ->select(['DATE(date) AS date', 'attandance'])
                ->where(['student_id' => $student_id])
                ->andWhere(['>=', 'DATE(date)', $year . '-01-01'])
                ->andWhere(['<=', 'DATE(date)', $year . '-12-31'])
                ->indexBy('date')
                ->asArray()
                ->all();

            $result = [];
            $start_date = new DateTime($year . '-01-01');
            $end_date = new DateTime($year . '-12-31');
            $interval = new DateInterval('P1D');
            $date_range = new DatePeriod($start_date, $interval, $end_date);

            foreach ($date_range as $date) {
                $formattedDate = $date->format('Y-m-d');
                $status = isset($attendanceData[$formattedDate]) ? (int) $attendanceData[$formattedDate]['attandance'] : 3;

                $result[] = [
                    'date' => $formattedDate,
                    'attendance' => $status,
                ];
            }

            $data['status'] = self::API_OK;
            $data['details'] = $result;

            return $this->sendJsonResponse($data);
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }



    // Exam Results in Parent
    public function actionExamMarksheet()
    {
        $data = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        try {
            $student_id = $post['student_id'];
            $parent_details = ParentDetails::find()->where(['user_id' => $user_id])->one();

            $finalMarksheets = FinalMarksheet::find()->where(['student_id' => $student_id])->all();

            if (!empty($finalMarksheets)) {
                $data['status'] = self::API_OK;
                $data['details'] = []; // Initialize details array

                foreach ($finalMarksheets as $finalMarksheet) {
                    // Create mark sheet data directly within the details array
                    $markSheetData = $finalMarksheet->asJson();
                    // Add mark sheet data to details array
                    $data['details'][] = $markSheetData;
                }

                if (empty($data['details'])) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Marksheet Not Generated";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Data found";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }


    public function actionMessMenu()
    {
        $data = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        try {
            $studentId = $post['student_id'];

            $hostellers = Hostellers::find()->where(['student_id' => $studentId])->one();
            if (!empty($hostellers)) {
                $hostel = Hostels::find()->where(['id' => $hostellers->hostel_id])->one();
                if (!empty($hostel->mess_menu)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $hostel->mess_menu;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = " No Menu Added";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Hostels Found";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }

    public function actionSendMail()
    {
        $data = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        try {
            $studentId = $post['student_id'];

            $hostellers = Hostellers::find()->where(['student_id' => $studentId])->one();
            if (!empty($hostellers)) {
                $hostel = Hostels::find()->where(['id' => $hostellers->hostel_id])->one();
                if (!empty($hostel->mess_menu)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $hostel->mess_menu;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = " No Menu Added";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Hostels Found";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }
    public function actionLeaveNoticeDetails()
    { {
            $data = [];
            try {
                $post = Yii::$app->request->post();
                $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
                $auth = new AuthSettings();
                $user_id = $auth->getAuthSession($headers);
                if (!empty($user_id)) {

                    $notice_id = $post['id'];
                    $noticeBoard = LeaveRequests::find()->where(['id' => $notice_id])->one();
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
}
