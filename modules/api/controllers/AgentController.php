<?php

namespace app\modules\api\controllers;

use app\modules\api\controllers\BKController;
use yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
use app\components\AuthSettings;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\User;
use app\modules\admin\models\AgentStudentJoin;
use app\modules\admin\models\Auth;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\Category;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\SpecialCourses;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\StudentSpecialCourses;
use app\modules\admin\models\StudentDetailsAgentLead;
use app\components\RazorPay;
use app\modules\admin\models\UserOtp;
use app\components\SendOtp;
use Exception;

class AgentController extends BKController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:50674'],
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
                            'verify-otp-parent',
                            'send-otp-parent',
                            'student-registration',
                            'student-class',
                            'special-courses',
                            'verify-student-phone',
                            'my-admits',
                            'pay-now',
                            'update-lat-lng',
                            'view-profile',
                            'manual-pay-now',
                            'agent-student-payment',
                            'logout',
                            'filter-students-by-status-and-agent',
                            'students-details-on-board',
                            'on-board-student-detail'

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
                            'verify-otp-parent',
                            'student-registration',
                            'student-class',
                            'special-courses',
                            'verify-student-phone',
                            'my-admits',
                            'pay-now',
                            'update-lat-lng',
                            'view-profile',
                            'manual-pay-now',
                            'agent-student-payment',
                            'logout',
                            'filter-students-by-status-and-agent',
                            'students-details-on-board',
                            'on-board-student-detail'



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
        $data['status'] = self::API_OK;
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
                $user = User::find()->where(['id' => $auth_session->create_user_id])->one();
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
        // $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        //$userID = Yii::$app->request->post();
        $auth = new AuthSettings();
        // $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        // var_dump($post);exit;

        $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
        $agent_details = user::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_AGENT])->one();
        if (!empty($agent_details)) {
            // var_dump($agent_details);
            // exit;
            if (!empty($agent_details)) {
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
                $SendOtpData =   new SendOtp();
                $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);

                if (strlen($send_otp) > 4) {
                    $date = date('Y-m-d H:i:s');
                    $user_otp  = new UserOtp();
                    $user_otp->contact_number = $contact_no;
                    $user_otp->otp = $otp;
                    $user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +10 minutes"));
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
                $data['error'] = Yii::t("app", "Agent details not found");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No User found ");
        }
        return $this->sendJsonResponse($data);
    }
    public function actionSendOtpParent()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $userID = Yii::$app->request->post();

        // var_dump($post);exit;
        if (!empty($post)) {


            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';


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
            $data['error'] = Yii::t("app", "No data posted");
        }
        return $this->sendJsonResponse($data);
    }

    public function actionResendOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();
        // var_dump($post);exit;
        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $agent_details = user::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_AGENT])->one();

            if (!empty($agent_details)) {

                $otp = rand(1111, 9999);
                $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
                $sms = 'Dear Parent, ' . $otp . ' is the OTP for login into Parent App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
                $sms_url = urlencode($sms);
                $template_id = '1707168312593733446';
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
                $data['error'] = Yii::t("app", "Agent details not found");
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
            // var_dump($otp_code);exit;

            $setting = new WebSetting();
            $numbers = $setting->getSettingByKey('agent-numbers');
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
            if ($contact_no == '9644435690' || $contact_no == '9494472630' || $contact_no == '8985228717' || $contact_no == '9963363621' || $contact_no == '9059526838') {
                $send_otp['Status'] = 'Success';
            }


            if ($otp_match  === true) {



                // $agent_details = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_AGENT])->one();
                $agent_details = User::find()->where(['contact_no' => $contact_no, 'user_role' => User::ROLE_AGENT])->one();

                // var_dump(User::ROLE_AGENT);exit;

                if (!empty($agent_details)) {
                    $providerId = User::ROLE_AGENT;
                    $number =  $contact_no;
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
                        $auth->user_id = $agent_details->id;
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


    public function actionVerifyOtpParent()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $otp_code = !empty($post['otp_code']) ? $post['otp_code'] : '';

            $allowedContactNumbers = ['8812345678', '9963363621', '7008180055', '9848012345'];

            if (in_array($contact_no, $allowedContactNumbers)) {
                $otp_match = true;
            } else {
                $userOtp = UserOtp::find()->where(['contact_number' => $contact_no, 'otp' => $otp_code])->one();

                if (!empty($userOtp) && strtotime($userOtp->expire_date_and_time) > strtotime(date('Y-m-d H:i:s'))) {
                    $otp_match = true;
                    $userOtp->status = UserOtp::STATUS_VERIFIED;
                    $userOtp->save(false);
                } else {
                    $otp_match = false;
                    $msg = (!empty($userOtp) ? 'otp expired' : 'otp verification failed');
                }
            }

            if ($otp_match) {
                // Handle OTP verification success
                // You can put your logic here, e.g., login the user, update the device token, etc.
                $data['status'] = self::API_OK;
                $data['details'] = "otp verified";
                // Add additional data to $data if needed
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", $msg);
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No Data Posted");
        }

        return $this->sendJsonResponse($data);
    }



    public function actionVerifyStudentPhone()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $session_code = $post['session_code'];
                $otp_code = $post['otp_code'];

                $send_otp = Yii::$app->notification->verifyOtp($session_code, $otp_code);

                $send_otp = json_decode($send_otp, true);

                if ($send_otp['Status'] == 'Success') {
                    $phone['verify'] = true;
                    $data['status'] = self::API_OK;
                    $data['details'] = $phone;
                } else {
                    $phone['verify'] = false;
                    $data['status'] = self::API_NOK;
                    $data['error'] = $phone;
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "You Don't Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionStudentRegistration()
    {

        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {

            if (!empty($user_id)) {
                if (!empty($post)) {
                    $userHasCampus =  (new User())->userHasCampus($user_id);
                    if (!empty($userHasCampus)) {
                        $campus_id = $userHasCampus->campus_id;
                        $student_name = !empty($post['student_name']) ? $post['student_name'] : "";
                        $gender = !empty($post['gender']) ? $post['gender'] : "";
                        $date_of_birth = !empty($post['date_of_birth']) ? $post['date_of_birth'] : "";
                        $name_of_the_parent = !empty($post['name_of_the_parent']) ? $post['name_of_the_parent'] : "";
                        $previous_school_name = !empty($post['previous_school_name']) ? $post['previous_school_name'] : "";
                        $previous_school_address = !empty($post['previous_school_address']) ? $post['previous_school_address'] : "";
                        $previous_student_class = !empty($post['previous_student_class']) ? $post['previous_student_class'] : "";
                        $student_class_id  = !empty($post['student_class_id']) ? $post['student_class_id'] : "";
                        $special_courses_id   = !empty($post['special_courses_id']) ? $post['special_courses_id'] : '';
                        $hostal_is_required  = isset($post['hostal_is_required']) ? $post['hostal_is_required'] : "";
                        $bus_transport_required  = isset($post['bus_transport_required']) ? $post['bus_transport_required'] : "";
                        $phone_number  = !empty($post['phone_number']) ? $post['phone_number'] : "";
                        $verified_phone  = !empty($post['verified_phone']) ? $post['verified_phone'] : "";


                        $student_details = new StudentDetailsAgentLead();
                        $student_details->campus_id  = $campus_id;
                        $student_details->student_name     = $student_name;
                        $student_details->agent_id      = $user_id;
                        $student_details->gender = $gender;
                        $student_details->date_of_birth     = $date_of_birth;
                        $student_details->name_of_the_parent = $name_of_the_parent;
                        $student_details->phone_number    = $phone_number;
                        $student_details->verified_phone    = (bool)$verified_phone;
                        $student_details->previous_school_name = $previous_school_name;
                        $student_details->previous_school_address = $previous_school_address;
                        $student_details->previous_student_class = $previous_student_class;
                        $student_details->student_class_id  = $student_class_id;
                        $student_details->hostal_is_required = $hostal_is_required;
                        $student_details->bus_transport_required = $bus_transport_required;
                        $student_details->special_courses_id = $special_courses_id;
                        $student_details->status = StudentDetailsAgentLead::STATUS_ACTIVE;
                        if ($student_details->save(false)) {
                            // $agent_student_join = AgentStudentJoin::find()->where(['student_id' => ])
                            $agent_student_join = new AgentStudentJoin();
                            $agent_student_join->campus_id  = $campus_id;
                            $agent_student_join->agent_id = $user_id;
                            $agent_student_join->student_id = $student_details->id;
                            $agent_student_join->amount = 0;
                            $agent_student_join->status = AgentStudentJoin::STATUS_PENDING;

                            if ($agent_student_join->save(false)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $student_details->asJson();
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Agent of student data update failed";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "User Not Created.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['message'] = Yii::t("app", "No Data Post");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No User found.";
                }
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }




    public function actionStudentClass()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $campus_id = $userHasCampus->campus_id;
                $student_class = StudentClass::find()->where(['campus_id' => $campus_id])
                    // ->andWhere(['is_agent'=>1])
                    ->andWhere(['status' => StudentClass::STATUS_ACTIVE])->all();
                if (!empty($student_class)) {
                    foreach ($student_class as $student_class_data) {
                        $class_data[] = $student_class_data->asJson();
                    }
                    $data['status'] = self::API_OK;
                    $data['detail'] = $class_data;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data NotFound.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "You Don\'t Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionSpecialCourses()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $campus_id = $userHasCampus->campus_id;
                $specialCourses = SpecialCourses::find()->where(['campus_id' => $campus_id])
                    // ->andWhere(['is_agent'=>1])
                    ->andWhere(['status' => StudentClass::STATUS_ACTIVE])->all();
                if (!empty($specialCourses)) {
                    foreach ($specialCourses as $specialCoursesData) {
                        $special_courses_data[] = $specialCoursesData->asJson();
                    }
                    $data['status'] = self::API_OK;
                    $data['detail'] = $special_courses_data;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data NotFound.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "You Don\'t Have to access perform this action.";
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
            $userHasCampus =  (new User())->userHasCampus($user_id);

            if (!empty($userHasCampus)) {
                $student_id = !empty($post['student_id']) ? $post['student_id'] : $err[] = 'Student Id Is Required';
                $razorpay_payment_id = !empty($post['razorpay_payment_id']) ? $post['razorpay_payment_id'] : $err[] = 'razorpay_payment_id  Is Required';
                $razorpay_order_id = !empty($post['razorpay_order_id']) ? $post['razorpay_order_id'] : $err[] = 'razorpay_order_id  Is Required';

                if (empty($err)) {
                    $student = AgentStudentJoin::find()->where(['student_id' => $student_id])->one();

                    if (!empty($student)) {
                        $raorPay = new RazorPay();
                        $createOrder = $raorPay->checkPaymentByPayId($razorpay_payment_id);
                        $paymentDecode = json_decode($createOrder);

                        if (!empty($paymentDecode)) {
                            $amount = $paymentDecode->amount / 100;
                            $status = $paymentDecode->status;
                            $student->amount = $amount;
                            $student->razorpay_payment_id = $razorpay_payment_id;
                            $student->status = $status == 'failed' ? AgentStudentJoin::STATUS_FAILED : AgentStudentJoin::STATUS_PAID;
                            $student->payment_mode = EmployeeDetails::agent_type_payment_gate_way;
                            $student->save(false);


                            if (!empty($paymentDecode->status) && $paymentDecode->status == 'captured') {
                                $student_details_agent_lead = StudentDetailsAgentLead::find()->where(['id' => $student->student_id])->one();

                                //Enrolment App sms
                                $arr_var_data = [];
                                $arr_var_data['VAR1'] = $amount . '/-';
                                $arr_var_data['VAR2'] = $student_details_agent_lead->student_name;
                                $arr_var_data['VAR3'] = $student_details_agent_lead->studentClass->title;
                                $arr_var_data['VAR4'] = '';
                                $sms =  Yii::$app->notification->sendSMSDynamicTemplateV2($student_details_agent_lead->phone_number, 'Enrolment App', $arr_var_data);

                                //Agent app admission fee pay sms
                                $arr_var_data['VAR1'] = $amount . '/-';
                                $arr_var_data['VAR2'] = $student_details_agent_lead->student_name;
                                $sms =  Yii::$app->notification->sendSMSDynamicTemplateV2($student_details_agent_lead->phone_number, 'Agent app admission fee pay', $arr_var_data);

                                $data['status'] = self::API_OK;
                                $data['details'] = $student->asJson();
                            } else {

                                $student_details_agent_lead = StudentDetailsAgentLead::find()->where(['id' => $student->student_id])->one();
                                $arr_var_data = [];


                                $data['status'] = self::API_NOK;
                                $data['error'] = "Payment Failed";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "res Not Found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Data Not Found.";
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
                $data['error'] = "You Don\'t Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionManualPayNow()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);


        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);

            if (!empty($userHasCampus)) {
                $student_id = !empty($post['student_id']) ? $post['student_id'] : $err[] = 'Student Id Is Required';
                $utr_number = !empty($post['utr_number']) ? $post['utr_number'] : '';
                $payment_receipt = !empty($post['payment_receipt']) ? $post['payment_receipt'] : $err[] = 'Payment Receipt Is Required';
                $amount = !empty($post['amount']) ? $post['amount'] : $err[] = 'Amount Is Required';


                if (empty($err)) {
                    $student = AgentStudentJoin::find()->where(['student_id' => $student_id])->one();

                    if (!empty($student)) {
                        if (!empty($utr_number)) {
                            $student->utr_number = $utr_number;
                        }
                        $student->payment_receipt = $payment_receipt;
                        $student->amount = $amount;
                        $student->payment_mode = EmployeeDetails::agent_type_manual_payment;
                        $student->status = AgentStudentJoin::STATUS_PENDING;


                        if ($student->save(false)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $student->asJson();
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Payment Details Updated Failed.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Data Not Found.";
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
                $data['error'] = "You Don\'t Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionMyAdmits()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $page = isset($post['page']) ? $post['page'] : '0';
                $search = isset($post['search']) ? $post['search'] : '';
                $start_date = !empty($post['start_date']) ? $post['start_date'] : '';
                $end_date = !empty($post['end_date']) ? $post['end_date'] : '';
                $status = isset($post['status']) ? $post['status'] : '';

                $query = StudentDetailsAgentLead::find()->Where(['student_details_agent_lead.agent_id' => $user_id]);
                $query->joinWith('agentStudentJoins');



                if (!empty($start_date) && !empty($end_date)) {
                    $query->andFilterWhere(['between', 'student_details_agent_lead.created_on', $start_date, $end_date]);
                }

                if (!empty($search)) {
                    $query->andFilterWhere(['like', 'student_details_agent_lead.student_name', $search]);
                }

                if (isset($post['status'])) {
                    $query->andWhere(['agent_student_join.status' => $status]);
                }




                $student_details = new ActiveDataProvider([
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
                if (!empty($student_details)) {
                    foreach ($student_details->models as $student_details_data) {
                        $list[] = $student_details_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Data Found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Data Found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "You Don\'t Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionUpdateLatLng()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $lat = !empty($post['lat']) ? $post['lat'] : '';
            $lng = !empty($post['lng']) ? $post['lng'] : '';
            if (!empty($lat) && !empty($lng)) {
                $update_lat_lng = User::find()->where(['id' => $user_id])->one();
                if (!empty($update_lat_lng)) {
                    $update_lat_lng->lat = $lat;
                    $update_lat_lng->lng = $lng;
                    if ($update_lat_lng->save(false)) {
                        $coordinates['lat'] = $update_lat_lng->lat;
                        $coordinates['lng'] = $update_lat_lng->lng;

                        $data['status'] = self::API_OK;
                        $data['details'] = $coordinates;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Update Failed";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not Found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "lat and lng are required";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionViewProfile()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $campus_id = $userHasCampus->campus_id;
                $profile = EmployeeDetails::find()->where(['user_id' => $user_id])->one();
                if (!empty($profile)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $profile->asJson();
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "You Don\'t Have to access perform this action.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }

    public function actionAgentStudentPayment()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = Yii::$app->request->headers->get('auth_code', Yii::$app->request->getQueryParam('auth_code'));
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (empty($user_id)) {
            $data = [
                'status' => self::API_NOK,
                'error' => 'No User found.',
            ];
            return $this->sendJsonResponse($data);
        }

        if (empty($post)) {
            $data = [
                'status' => self::API_NOK,
                'message' => Yii::t("app", "No Data Post"),
            ];
            return $this->sendJsonResponse($data);
        }

        $userHasCampus = (new User())->userHasCampus($user_id);

        if (empty($userHasCampus)) {
            $data = [
                'status' => self::API_NOK,
                'message' => 'User has no associated campus.',
            ];
            return $this->sendJsonResponse($data);
        }
        $campus_id = $userHasCampus->campus_id;
        $student_id = $post['student_id'] ?? null;
        $payment_mode = $post['payment_mode'] ?? null;
        $transaction_no = $post['transaction_no'] ?? '';
        $amount = $post['amount'] ?? null;

        $validationErrors = [];
        if (empty($student_id)) {
            $validationErrors[] = 'Student is required.';
        }

        if (empty($payment_mode)) {
            $validationErrors[] = 'Payment mode is required.';
        }

        if (empty($amount)) {
            $validationErrors[] = 'Amount is required.';
        }

        if (!empty($validationErrors)) {
            $data = [
                'status' => self::API_NOK,
                'error' => implode(', ', $validationErrors),
            ];
            return $this->sendJsonResponse($data);
        }

        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $student_id])->one();

        if (empty($agent_student_join)) {
            $data = [
                'status' => self::API_NOK,
                'error' => "Student Not Found",
            ];
            return $this->sendJsonResponse($data);
        }

        $agent_student_join->campus_id = $campus_id;
        $agent_student_join->agent_id = $user_id;
        $agent_student_join->student_id = $student_id;
        $agent_student_join->payment_mode = $payment_mode;
        $agent_student_join->transaction_no = $transaction_no;
        $agent_student_join->amount = $amount;
        $agent_student_join->status = AgentStudentJoin::STATUS_PAID;

        if ($agent_student_join->save(false)) {
            $data = [
                'status' => self::API_OK,
                'details' => $agent_student_join->asJson(),
            ];
        } else {
            $data = [
                'status' => self::API_NOK,
                'error' => 'Payment not created.',
            ];
        }

        return $this->sendJsonResponse($data);
    }

    public function actionFilterStudentsByStatusAndAgent()
    {
        $data = [];

        // Retrieve data from both form-data and raw JSON
        $post = Yii::$app->request->getBodyParams();

        // Retrieve auth_code from headers or query parameter
        $authCode = Yii::$app->request->headers->get('auth_code', Yii::$app->request->getQueryParam('auth_code'));

        // Check authentication
        if (empty($authCode)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Authentication token not provided.";
            return $this->sendJsonResponse($data);
        }

        // Validate user authentication
        $auth = new AuthSettings();
        $userId = $auth->getAuthSession($authCode);
        if (empty($userId)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid or expired authentication token.";
            return $this->sendJsonResponse($data);
        }

        // Check if the user has access to perform this action
        $userHasCampus =  (new User())->userHasCampus($userId);
        if (empty($userHasCampus)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No campus assigned or you don't have access to perform this action.";
            return $this->sendJsonResponse($data);
        }

        // Retrieve status and agent_id from the request
        $status = isset($post['status']) ? $post['status'] : '';
        $agentId = isset($post['agent_id']) ? $post['agent_id'] : $userId; // If agent_id is not provided, default to user_id

        // Validate status value if provided
        $validStatusValues = [
            StudentDetailsAgentLead::STATUS_ACTIVE,
            StudentDetailsAgentLead::STATUS_DELETE,
            StudentDetailsAgentLead::status_admission_ok,
            StudentDetailsAgentLead::status_admission_not_ok
        ];

        if (!empty($status) && !in_array($status, $validStatusValues)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid status value provided.";
            return $this->sendJsonResponse($data);
        }

        // Query students based on agent_id and status
        $query = StudentDetailsAgentLead::find()->where(['agent_id' => $agentId]);

        // Validate and apply status filter
        if (!empty($status)) {
            // Handle status 0 separately
            if ($status == 0) {
                $query->andWhere(['status' => 0]);
            } else {
                $query->andWhere(['status' => (int)$status]);
            }
        }

        $students = $query->all();


        // Check if students were found
        if (!empty($students)) {
            $data['status'] = self::API_OK;
            $data['students'] = [];

            foreach ($students as $student) {
                $data['students'][] = $student->toArray();
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No students found with the given criteria or status.";
        }

        return $this->sendJsonResponse($data);
    }

    public function actionOnBoardStudentDetail()
    {
        $data = [];

        // Retrieve data from both form-data and raw JSON
        $post = Yii::$app->request->getBodyParams();

        // Retrieve auth_code from headers or query parameter
        $authCode = Yii::$app->request->headers->get('auth_code', Yii::$app->request->getQueryParam('auth_code'));

        // Check authentication
        if (empty($authCode)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Authentication token not provided.";
            return $this->sendJsonResponse($data);
        }

        // Validate user authentication
        $auth = new AuthSettings();
        $userId = $auth->getAuthSession($authCode);
        if (empty($userId)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid or expired authentication token.";
            return $this->sendJsonResponse($data);
        }

        // Check if the user has access to perform this action
        $userHasCampus =  (new User())->userHasCampus($userId);
        if (empty($userHasCampus)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No campus assigned or you don't have access to perform this action.";
            return $this->sendJsonResponse($data);
        }

        // Retrieve student_id from request
        $studentId = isset($post['student_id']) ? trim($post['student_id']) : null;

        // Check if student_id is provided and not empty
        if (empty($studentId)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Student ID is required.";
            return $this->sendJsonResponse($data);
        }

        // Fetch student details based on student_id and agent_id
        try {
            $studentDetails = StudentDetailsAgentLead::find()
                ->leftJoin('agent_student_join', 'agent_student_join.student_id = student_details_agent_lead.id')
                ->where(['agent_student_join.agent_id' => $userId, 'agent_student_join.student_id' => $studentId])
                ->one();

            // Check if student with provided ID exists
            if (!empty($studentDetails)) {
                $data['status'] = self::API_OK;
                $data['student_details'] = $studentDetails->asJson();
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Student not found with the provided ID.";
            }
        } catch (\Exception $e) {
            // Handle database errors
            $data['status'] = self::API_NOK;
            $data['error'] = "Error fetching student details: " . $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }
}
