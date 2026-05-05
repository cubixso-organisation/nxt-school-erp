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
use app\modules\admin\models\UserOtp;
use app\modules\hostelmanagement\models\base\Floor;
use app\modules\hostelmanagement\models\base\WardenToHostel;
use app\modules\hostelmanagement\models\base\HostellersAttandance;
use app\modules\hostelmanagement\models\base\HostlerAttendanceSettings;
use app\modules\hostelmanagement\models\HostellersAttandance as ModelsHostellersAttandance;
use app\modules\hostelmanagement\models\Rooms as ModelsRooms;
use Exception;

class HostelManagementController extends BKController
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
                            'send-otp-chief-warden',
                            'resend-otp-chief-warden',
                            'verify-otp-chief-warden',
                            'get-warden-assigned-floors',
                            'get-floor-rooms',
                            'get-room-wise-students',
                            'get-student-for-warden',
                            'mark-attendance',
                            'get-attendence-history',
                            'no-of-attendance'
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
                            'send-otp-chief-warden',
                            'resend-otp-chief-warden',
                            'verify-otp-chief-warden',
                            'get-warden-assigned-floors',
                            'get-floor-rooms',
                            'get-room-wise-students',
                            'get-student-for-warden',
                            'mark-attendance',
                            'get-attendence-history'
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

    public function actionSendOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_WARDEN])->one();

            if (!empty($user_check)) {

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

            $otp = rand(1111, 9999);
            $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
            $sms = 'Dear Driver, ' . $otp . ' is the OTP for login into Driver App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
            $sms_url = urlencode($sms);
            $template_id = '1707168312584449739';
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
            $otp_code = $post['otp_code'];

            $setting = new WebSetting();
            $numbers = $setting->getSettingByKey('warden');
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

            if ($otp_match  === true) {
                //check user details exist
                $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_WARDEN])->one();

                if (!empty($user_check)) {
                    $providerId = User::ROLE_WARDEN;
                    $number =  $contact_no;
                    $auth_id = $number;
                    $auth = Auth::find()->where(['source' => $providerId, 'source_id' => $auth_id])->one();

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
                            $data['error'] = $auth->getErrors();
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
        return $this->sendJsonResponse($data);
    }


    public function actionCheck()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);


        if ($user_id) {
            try {
                $auth_session = AuthSession::find()->where([
                    'auth_code' => $headers,
                ])->one();
                if ($auth_session) {
                    $user = $auth_session->createUser;
                    $data['status'] = self::API_OK;
                    $data['detail'] = $user->asJson($user_id);
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
            } catch (Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['error'] = Yii::t("app", 'Auth code not found');
            $data['auth'] = isset($auth_code) ? $auth_code : '';
        }

        return $this->sendJsonResponse($data);
    }


    public function actionCreateOrUpdateRoom($room_id = '')
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $post = Yii::$app->request->post();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            try {
                $name_of_the_room = $post['name_of_the_room'];
                $hostel_id = $post['hostel_id'];
                $no_of_beds = $post['no_of_beds'];
                $type = $post['type'];
                $status = Hostels::STATUS_ACTIVE;
                $check_hostel = Hostels::find()->where(['id' => $hostel_id])->one();
                if (!empty($check_hostel)) {
                    if (!empty($room_id)) {
                        $rooms =  Rooms::find()->where(['id' => $room_id])->one();
                    } else {
                        $rooms = new Rooms();
                    }

                    $rooms->hostel_id = $check_hostel->id;
                    $rooms->name_of_the_room = $name_of_the_room;
                    $rooms->no_of_beds = $no_of_beds;
                    $rooms->type = $type;
                    $rooms->status = $status;
                    if ($rooms->validate() & $rooms->save(false)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $rooms->asJson();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Data Saved Failed");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Hostel Data Not Found");
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


    public function actionGetHostelStudents()
    {

        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $roome_id = isset($post['room_id']) ? $post['room_id'] : "";
        if (!empty($user_id)) {
            try {
                $hostel = Hostels::find()->where(['warden_id' => $user_id])->one();
                if (empty($hostel)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Hostels Assigned";
                    return $this->sendJsonResponse($data);
                }
                if (empty($roome_id)) {
                    $student_details = Hostellers::find()->where(['hostel_id' => $hostel->id])->all();
                } else {
                    $student_details = Hostellers::find()->where(['hostel_id' => $hostel->id])->andWhere(['room_id' => (int)$roome_id])->all();
                }

                if (!empty($student_details)) {
                    $list = [];
                    foreach ($student_details as $results) {
                        $list[] = $results->asJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Students Found";
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


    public function actionGetHostelRooms()
    {

        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $post = Yii::$app->request->post();
        $user_id = $auth->getAuthSession($headers);
        $filter = isset($post['filter']) ? $post['filter'] : '';
        $page = isset($post['page']) ? $post['page'] : 0;
        if (!empty($user_id)) {
            try {

                $check_hostel = Hostels::find()->where(['warden_id' => $user_id])->one();
                // var_dump($check_hostel);exit;
                if (!empty($check_hostel)) {
                    $query = Rooms::find()
                        ->where(['hostel_id' => $check_hostel->id])
                        ->andWhere(['status' => Rooms::STATUS_ACTIVE]);
                    if (!empty($filter)) {
                        $query->andWhere(['no_of_beds' => $filter]);
                    }
                    $rooms = new ActiveDataProvider([
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
                    if (!empty($rooms)) {
                        foreach ($rooms->models as $rooms_data) {
                            $list[] = $rooms_data->asJson();
                        }
                        if (!empty($list)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $list;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = Yii::t("app", "Rooms Data Not Found");
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = Yii::t("app", "Rooms Data Not Found");
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "Data Not Found");
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

    public function actionGetHostelDetails()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');

        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $post = Yii::$app->request->post();
        $student_id = $post['student_id'];
        if (!empty($user_id)) {

            try {
                if (empty($student_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Required Student Id";
                } else {
                    $hostelDetails = Hostellers::find()->where(['student_id' => $student_id])->one();

                    if (!empty($hostelDetails)) {

                        $data['status'] = self::API_OK;
                        $data['details'] = $hostelDetails->asJson();
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Hostel Details Found";
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'api');
                $data['status'] = self::API_NOK;
                $data['error'] = "An error occurred while processing the request.";
            }
        } else {
            // var_dump("Hi");
            // exit;
            $data['status'] = self::API_NOK;
            $data['error'] = "No user found";
        }

        return $this->sendJsonResponse($data);
    }
    public function actionSendOtpChiefWarden()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])->one();

            if (!empty($user_check)) {

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
                $data['error'] = Yii::t("app", "User details Not found");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No data posted");
        }
        return $this->sendJsonResponse($data);
    }
    public function actionResendOtpChiefWarden()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])->one();

            if (!empty($user_check)) {

                $otp = rand(1111, 9999);
                $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
                $sms = 'Dear Chief Warden, ' . $otp . ' is the OTP for login into Hostel Management  App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
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

    public function actionVerifyOtpChiefWarden()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $otp_code = $post['otp_code'];

            if ($contact_no == '8812345677' || $contact_no == '9963363621' || $contact_no == '9490132035' || $contact_no == '8186842100' || $contact_no == '9059526838' || $contact_no == '7674896838' || $contact_no == '9502664076' || $contact_no == '9059526831') {
                $otp_match = true;
            } else {

                $user_otp = UserOtp::find()->where(['contact_number' => $contact_no])->where(['otp' => $otp_code])->one();
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

            if ($otp_match  === true) {
                //check user details exist
                $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])->one();
                if (!empty($user_check)) {
                    $providerId = User::ROLE_CHEF_WARDEN;
                    $number =  $contact_no;
                    $auth_id = $number;
                    $auth = Auth::find()->where(['source' => $providerId, 'source_id' => $auth_id])->one();

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
                            $data['error'] = $auth->getErrors();
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
        return $this->sendJsonResponse($data);
    }

    public function actionGetWardenAssignedFloors()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        } else {
            try {

                $warden_floors = WardenToHostel::find()->where(['warden_id' => $user_id])->andWhere(['status' => WardenToHostel::STATUS_ACTIVE])->all();
                if (empty($warden_floors)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Floors Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $list = [];
                    foreach ($warden_floors as $results) {
                        $list[] = $results->asJsonForFloor();
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
        }

        return $this->sendJsonResponse($data);
    }

    public function actionGetFloorRooms()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $floor_id = $post['floor_id'];

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        }

        if (empty($floor_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "floor data not posted";
            return $this->sendJsonResponse($data);
        }

        try {
            $getWardenFloors = (new Floor)->getWardenFloors($user_id);
            // var_dump($getWardenFloors);
            // exit;
            if (!in_array($floor_id, $getWardenFloors)) {
                // $floor_id does not exist in $getWardenFloors
                $data['status'] = self::API_NOK;
                $data['error'] = "Invalid floor id for the user";
                return $this->sendJsonResponse($data);
            }

            // Now $floor_id exists in $getWardenFloors

            $floor_rooms = Rooms::find()->where(['floor_id' => $floor_id])->all();

            if (empty($floor_rooms)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Floors Found";
                return $this->sendJsonResponse($data);
            } else {
                $list = [];
                foreach ($floor_rooms as $results) {
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

        return $this->sendJsonResponse($data);
    }

    public function actionGetRoomWiseStudents()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $room_id = $post['room_id'];
        $attendanceNo = isset($post['attendance_no']) ? $post['attendance_no'] : 1;

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        }
        if (empty($room_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Room data not posted";
            return $this->sendJsonResponse($data);
        }

        // try {
        // Fetch attendance settings

        // Fetch students in the specified room under warden's authority
        $getWardenFloors = (new Floor)->getWardenFloors($user_id);
        $warden = User::find()->where(['id' => $user_id])->one();

        $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $warden->campus_id])->one();



        $student_rooms = Hostellers::find()->joinWith(['room as r'])->where(['hostellers.room_id' => $room_id])->andWhere(['IN', 'r.floor_id', $getWardenFloors])->andWhere(['hostellers.status' => Hostellers::STATUS_ACTIVE])->all();

        if (empty($student_rooms)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No Students Found";
            return $this->sendJsonResponse($data);
        }

        // Process student data and create response
        $list = [];
        foreach ($student_rooms as $student) {
            $dailyAttendanceCount = $attendanceSetting->daily_attendance_count;
            for ($i = 1; $i <= $dailyAttendanceCount; $i++) {
                $hostellerAttendace = HostellersAttandance::find()
                    ->where(['attendance_count_perday' => $i])
                    ->andWhere(['Date(date)' => date('Y-m-d')])
                    ->andWhere(['student_id' => $student->student_id])
                    ->one();
                if (empty($hostellerAttendace)) {
                    $attendanceRecord = new HostellersAttandance();
                    $attendanceRecord->campus_id = $student->campus_id;
                    $attendanceRecord->hostel_id = $student->hostel_id;
                    $attendanceRecord->student_id = $student->student_id;
                    $attendanceRecord->room_id = $student->room_id;
                    $attendanceRecord->attandance  = HostellersAttandance::NOT_MARKED;
                    $attendanceRecord->attendance_count_perday = $i;
                    $attendanceRecord->date = date('Y-m-d');
                    $attendanceRecord->attandance_by = $user_id;
                    $attendanceRecord->status = HostellersAttandance::STATUS_ACTIVE;
                    $attendanceRecord->save(false);
                }
            }
            $list[] = $student->studentListJson($attendanceNo);
        }

        $data['status'] = self::API_OK;
        $data['details'] = $list;
        // } catch (Exception $e) {
        //     Yii::error($e->getMessage(), 'api');
        //     $data['status'] = self::API_NOK;
        //     $data['error'] = "An error occurred while processing the request.";
        // }

        return $this->sendJsonResponse($data);
    }

    public function actionNoOfAttendance()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();
        $room_id = isset($post['room_id']) ? $post['room_id'] : "";

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        }
        // if (empty($room_id)) {
        //     $data['status'] = self::API_NOK;
        //     $data['error'] = "Room data not posted";
        //     return $this->sendJsonResponse($data);
        // }

        // try {
        // Fetch attendance settings

        // Fetch students in the specified room under warden's authority
        $getWardenFloors = (new Floor)->getWardenFloors($user_id);
        $warden = User::find()->where(['id' => $user_id])->one();

        $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $warden->campus_id])->one();

        if (!empty($room_id)) {
            $student_rooms = Hostellers::find()->joinWith(['room as r'])->where(['hostellers.room_id' => $room_id])->andWhere(['IN', 'r.floor_id', $getWardenFloors])->andWhere(['hostellers.status' => Hostellers::STATUS_ACTIVE])->all();
        } else {
            $student_rooms = Hostellers::find()->joinWith(['room as r'])->Where(['IN', 'r.floor_id', $getWardenFloors])->andWhere(['hostellers.status' => Hostellers::STATUS_ACTIVE])->all();
        }

        if (empty($student_rooms)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No Students Found";
            return $this->sendJsonResponse($data);
        }

        // Process student data and create response
        $list = [];
        foreach ($student_rooms as $student) {

            $hostellerAttendace = HostellersAttandance::find()
                ->select(['attendance_count_perday'])
                ->where(['student_id' => $student->student_id])
                ->andWhere(['campus_id' => $student->campus_id])
                ->andWhere(['Date(date)' => date('Y-m-d')])
                ->groupBy('attendance_count_perday')
                ->all();


            foreach ($hostellerAttendace as $noAttendance) {
                $list[] = $noAttendance->attendance_count_perday;
            }
        }


        $attendaceSettings = HostlerAttendanceSettings::find()->where(['campus_id' => $warden->campus_id])->one();
        if (!empty($attendaceSettings)) {

            if (!empty($list)) {
                $data['status'] = self::API_OK;
                $data['details'] = $data['details'] = array_values(array_unique($list));
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Students";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "Please Update Student Attendance Settings";
        }
        // } catch (Exception $e) {
        //     Yii::error($e->getMessage(), 'api');
        //     $data['status'] = self::API_NOK;
        //     $data['error'] = "An error occurred while processing the request.";
        // }

        return $this->sendJsonResponse($data);
    }






    public function actionGetStudentForWarden()
    {
        $data = [];
        $headers = \Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

        $search_name = $post['student_name'] ?? "";
        $floor_id = $post['floor_id'] ?? "";
        $attendance_no = $post['attendance_no'] ?? 1;
        $page = $post['page'] ?? 0;

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        }
        $warden = User::find()->where(['id' => $user_id])->one();
        $getWardenFloors = (new Floor)->getWardenFloors($user_id);
        // try {
        $query = Hostellers::find()
            ->joinWith(['student s', 'room r'])
            ->Where(['IN', 'r.floor_id', $getWardenFloors]);

        if (!empty($floor_id)) {
            // Use the specific floor_id if provided
            $query->andWhere(['r.floor_id' => $floor_id]);
        } else {
            $query;
        }
        if (!empty($search_name)) {
            $query->andFilterWhere(['like', 's.student_name', $search_name]);
        }

        $student_details = $query->andWhere(['hostellers.status' => Hostellers::STATUS_ACTIVE])->all();

        if (empty($student_details)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No Students Found";
            return $this->sendJsonResponse($data);
        }

        $list = [];
        $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $warden->campus_id])->one();
        foreach ($student_details as $results) {
            if (empty($attendanceSetting)) {
                $dailyAttendanceCount = 1;
            } else {
                $dailyAttendanceCount = $attendanceSetting->daily_attendance_count;
            }



            for ($i = 1; $i <= $dailyAttendanceCount; $i++) {
                $hostellerAttendace = HostellersAttandance::find()
                    ->where(['attendance_count_perday' => $i])
                    ->andWhere(['Date(date)' => date('Y-m-d')])
                    ->andWhere(['student_id' => $results->student_id])
                    ->one();
                if (empty($hostellerAttendace)) {
                    $attendanceRecord = new HostellersAttandance();
                    $attendanceRecord->campus_id = $results->campus_id;
                    $attendanceRecord->hostel_id = $results->hostel_id;
                    $attendanceRecord->student_id = $results->student_id;
                    $attendanceRecord->room_id = $results->room_id;
                    $attendanceRecord->attandance  = HostellersAttandance::NOT_MARKED;
                    $attendanceRecord->attendance_count_perday = $i;
                    $attendanceRecord->date = date('Y-m-d');
                    $attendanceRecord->attandance_by = $user_id;
                    $attendanceRecord->save(false);
                }
            }
            $list[] = $results->asJsonForRoomStudents($attendance_no);
        }

        if (!empty($list)) {
            $data['status'] = self::API_OK;
            $data['details'] = $list;
        }
        // } catch (Exception $e) {
        //     Yii::error($e->getMessage(), 'api');
        //     $data['status'] = self::API_NOK;
        //     $data['error'] = "An error occurred while processing the request.";
        // }

        return $this->sendJsonResponse($data);
    }

    public function actionMarkAttendance()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {
                $student_id = isset($post['student_id']) ? $post['student_id'] : 0;
                $attendance = isset($post['attendance']) ? $post['attendance'] : 3;
                $attendance_no = isset($post['attendance_no']) ? $post['attendance_no'] : 1;

                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getUserCampusId($user_id);
                    // var_dump($wardenCampusId);
                    // exit;

                    $getWardenFloors = (new Floor)->getWardenFloors($user_id);

                    $hostellars = Hostellers::find()
                        ->joinWith(['room as r'])
                        ->where(['student_id' => (int)$student_id])
                        ->andWhere(['campus_id' => (int)$wardenCampusId])
                        // ->andWhere(['warden_id' => $user_id])
                        ->andWhere(['IN', 'r.floor_id', $getWardenFloors])
                        ->one();
                    // var_dump($hostellars);
                    // var_dump($wardenCampusId);
                    // var_dump($user_id);
                    // var_dump($getWardenFloors);
                    // exit;
                    if (empty($hostellars)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Invalid Student Id";
                    } else {
                        $today = date('Y-m-d');
                        $existingAttendance = HostellersAttandance::find()
                            ->where(['student_id' => $hostellars->student_id])
                            ->andWhere(['attendance_count_perday' => $attendance_no])
                            ->andWhere(['DATE(date)' => $today]) // Compare only the date part
                            ->one();

                        if ($existingAttendance) {
                            // Attendance already exists for today, update it
                            $existingAttendance->attandance = $attendance;
                            $existingAttendance->date = date('Y-m-d H:i:s');
                            $existingAttendance->attandance_by = $user_id;


                            if ($existingAttendance->save(false)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = "Attendance Updated Successfully";
                            }
                        } else {
                            // Attendance does not exist for today, add new entry
                            $hostellersAttendance = new HostellersAttandance();
                            $hostellersAttendance->campus_id = $hostellars->campus_id;
                            $hostellersAttendance->hostel_id = $hostellars->hostel_id;
                            $hostellersAttendance->student_id = $hostellars->student_id;
                            $hostellersAttendance->room_id = $hostellars->room_id;
                            $hostellersAttendance->attandance = $attendance;
                            $hostellersAttendance->date = date('Y-m-d H:i:s');
                            $hostellersAttendance->attandance_by = $user_id;
                            $hostellersAttendance->status = HostellersAttandance::STATUS_ACTIVE;

                            if ($hostellersAttendance->save(false)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = "Attendance Added Successfully";
                            }
                        }
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

    public function actionGetAttendenceHistory()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Warden Not Found";
            return $this->sendJsonResponse($data);
        }

        $post = Yii::$app->request->post();
        $floor_id = $post['floor_id'] ?? "";
        $from_date = $post['from_date'] ?? "";
        $to_date = $post['to_date'] ?? "";
        $attendance_mark = $post['attendance_mark'] ?? "";
        $singleAttendance = $post['attendance_no'] ?? 1;
        // var_dump($floor_id);exit;

        try {
            $query = HostellersAttandance::find()
                ->joinWith(['room as r'])
                ->where(['attandance_by' => $user_id])->andWhere(['hostellers_attandance.attendance_count_perday' => $singleAttendance]);

            if (!empty($floor_id)) {
                $query->andWhere(['floor_id' => $floor_id]);
            } else {
                $getWardenFloors = (new Floor)->getWardenFloors($user_id);
                $query->andWhere(['IN', 'r.floor_id', $getWardenFloors]);
            }

            if (!empty($from_date) && !empty($to_date)) {
                $query->andWhere(['between', 'Date(hostellers_attandance.date)', $from_date, $to_date]);
            } else {
                $currentDate = date('Y-m-d');
                $query->andWhere(['=', 'Date(hostellers_attandance.date)', $currentDate]);
            }

            if (!empty($attendance_mark)) {
                $query->andWhere(['hostellers_attandance.attandance' => $attendance_mark]);
            }
            if (!empty($singleAttendance)) {
                $query;
            }
            // var_dump($query->createCommand()->getRawSql());exit;
            $student_details = $query->all();

            if (empty($student_details)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Students Found";
                return $this->sendJsonResponse($data);
            }

            $list = array_map(function ($result) {
                return $result->asJsonForAttendenceHistory();
            }, $student_details);

            $data['status'] = self::API_OK;
            $data['details'] = $list;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'api');
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
        }

        return $this->sendJsonResponse($data);
    }
}
