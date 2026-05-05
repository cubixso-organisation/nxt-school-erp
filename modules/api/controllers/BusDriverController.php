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
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\StudentHasParent;
use app\components\SendOtp;
use app\modules\admin\models\UserOtp;

class BusDriverController extends BKController
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
                            'bus-detail',
                            'update-lat-lng',
                            'view-profile',
                            'driver-bus-details',
                            'student-details',
                            'start-or-end-drive',
                            'bus-route',
                            'student-details-of-route',
                            'bus-status',
                            'bus-live-route',
                            'student-attendance-bus',
                            'bus-details-by-id',
                            'end-drive',
                            'student-absent-present-list',
                            'student-details-of-route-absent',
                            'my-notifications',
                            'check-notifications',
                            'logout',


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
                            'bus-detail',
                            'view-profile',
                            'driver-bus-details',
                            'student-details',
                            'start-or-end-drive',
                            'bus-route',
                            'student-details-of-route',
                            'logout',
                            'bus-status',
                            'update-lat-lng',
                            'student-attendance-bus',
                            'bus-live-route',
                            'end-drive',
                            'bus-details-by-id',
                            'student-absent-present-list',
                            'student-details-of-route-absent',
                            'check-notifications',
                            'my-notifications',





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


    public function actionCheckNotifications()
    {
        $title = 'hello';
        $body = 'Hi';
        $type = '';
        return  json_decode(Yii::$app->notification->UserNotification('', 1556, $title, $body, $type));
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
                $data['detail'] = $user;
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
        $webSetting = new WebSetting();
        $templateId = $webSetting->getSettingBykey('sms_template_id');
        $apiKey = $webSetting->getSettingBykey('sms_api_key');
        $senderId = $webSetting->getSettingBykey('sender_id');
        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
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
            // $session_code = $post['session_code'];
            $otp_code = $post['otp_code'];


            $setting = new WebSetting();
            $numbers = $setting->getSettingByKey('bus_driver');
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


            if ($otp_match  == true) {
                $providerId = "BusDriver";
                $auth_id = $contact_no;

                $auth = Auth::find()->where([
                    'source' => $providerId,
                    'source_id' => $auth_id,
                ])->one();


                if (!empty($auth)) {
                    $user = $auth->user;
                    $user->device_token = !empty($post['device_token']) ? $post['device_token'] : '';
                    $user->device_type = !empty($post['device_type']) ? $post['device_type'] : '';
                    Yii::$app->user->login($user);
                    $data['status'] = self::API_OK;
                    $data['details'] = $user;
                    $data['auth_code'] = AuthSession::newSession($user)->auth_code;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = Yii::t("app", "you don't have permission to perform this action contact your  admin");
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

    // Bus Details




    public function actionBusDetail()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $busDetail = BusDetails::find()->joinWith(['driverHasBuses as dhb'])->where(['dhb.user_id' => $user_id])->one();
            if (!empty($busDetail)) {
                $data['status'] = self::API_OK;
                $data['detail'] = $busDetail->asJson();
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
            $user = User::find()->where(['id' => $user_id])->one();
            if (!empty($user)) {
                $user->lat = $post['lat'];
                $user->lng = $post['lng'];
                if ($user->save(false)) {
                    $data['status'] = self::API_OK;
                    $data['detail'] = "Lat And Lng Updated Successfully";
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Data Not Saved";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "User Not Found";
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




    public function actionDriverBusDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id  = $driver_has_bus->bus_id;
                $bus_details = BusDetails::find()->where(['id' => $bus_id])->one();
                if (!empty($bus_details)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $bus_details->asJson();
                    $data['start_point_lat'] = $bus_details->start_point_lat;
                    $data['start_point_lng'] = $bus_details->start_point_lng;
                    $data['end_point_lat'] = $bus_details->end_point_lat;
                    $data['end_point_lng'] = $bus_details->end_point;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus  Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionStudentDetails($status = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $status = isset($status) ? $status : 0;
                $bus_id  = $driver_has_bus->bus_id;
                $busDetails = BusDetails::find()->where(['id' => $bus_id])->one();
                $session_key = $busDetails->session_key;
                if ($status == '') {
                    $student_details = StudentDetails::find()
                        ->innerJoinWith('studentAttendanceBuses')
                        ->where(['student_attendance_bus.session_key' => $session_key])
                        ->all()
                    ;
                    // var_dump(
                    //     $student_details->createCommand()->getRawSql()
                    // );
                    // exit;
                } else {
                    $student_details = StudentDetails::find()
                        ->innerJoinWith('studentAttendanceBuses')
                        ->where(['student_attendance_bus.session_key' => $session_key])
                        ->andWhere(['student_attendance_bus.status' => $status])
                        ->all();
                }


                if (!empty($student_details)) {
                    foreach ($student_details as $student_details_data) {
                        $student_data[] = $student_details_data->DriverAsJson($session_key);
                    }
                    $data['status'] = self::API_OK;
                    $data['details'] = $student_data;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionStartOrEndDrive()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $start_or_end = !empty($post['start_drive']) ? $post['start_drive'] : '';
            $status_direction = !empty($post['status_direction']) ? $post['status_direction'] : '';
            //get driver details
            $employee_details = EmployeeDetails::find()->where(['user_id' => $user_id])->one();
            if (!empty($employee_details)) {
                $academic_year_id = !empty($employee_details->campus->academic_year) ? $employee_details->campus->academic_year : '';
                if (!empty($academic_year_id)) {
                    $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
                    if (!empty($driver_has_bus)) {
                        if ($driver_has_bus->status == DriverHasBus::STATUS_ACTIVE) {
                            if (!empty($start_or_end) && !empty($status_direction)) {
                                $bus_id =  $driver_has_bus['bus_id'];
                                //check bus current status
                                $checkBus = BusDetails::find()->where(['id' => $bus_id])->andWhere(['current_status' => BusDetails::current_status_active])->one();

                                if (!empty($checkBus)) {
                                    if ($checkBus->status == BusDetails::STATUS_DRIVE_MODE || $checkBus->status == BusDetails::STATUS_PARKING) {
                                        if ($checkBus->status == BusDetails::STATUS_DRIVE_MODE) {
                                            $busStatus['busStatus'] = 'Bus Is Drive Mode';
                                            $busStatus['CurrentStatus'] =  strip_tags($checkBus->getStateOptionsBadges());
                                            $busStatus['bus_id'] =  $bus_id;
                                            $data['status'] = self::API_OK;
                                            $data['details'] =  $busStatus;
                                        } else {
                                            $bus = BusDetails::find()->where(['id' => $bus_id])
                                                ->andWhere(['status' => BusDetails::STATUS_PARKING])->one();
                                            $bus->status = $start_or_end;
                                            $bus->session_key =  md5(uniqid(rand(), true));
                                            $bus->status_direction = $status_direction;
                                            $bus->endDrive = BusDetails::end_drive_no;
                                            if ($bus->save(false)) {
                                                $bus_route = BusRoute::find()
                                                    ->where(['bus_id' => $bus_id])
                                                    ->all();

                                                if (!empty($bus_route)) {
                                                    // $busRp
                                                    if ($bus->status_direction == BusDetails::status_direction_school) {
                                                        $first_next_route = BusRoute::find()->limit(1)
                                                            ->where(['status' => 0])
                                                            ->andWhere(['bus_id' => $bus_id])
                                                            ->orderBy(['short_order' => SORT_ASC])
                                                            ->one();
                                                    } else {
                                                        $first_next_route = BusRoute::find()->limit(1)
                                                            ->where(['status' => 0])
                                                            ->andWhere(['bus_id' => $bus_id])
                                                            ->orderBy(['short_order' => SORT_DESC])
                                                            ->one();
                                                    }
                                                    $updateBusStop = BusDetails::find()->where(['id' => $bus_id])->one();
                                                    $updateBusStop->next_stop = $first_next_route->id;
                                                    $updateBusStop->current_stop = $first_next_route->id;


                                                    if ($updateBusStop->save(false)) {
                                                        if ($start_or_end == BusDetails::STATUS_DRIVE_MODE) {
                                                            foreach ($bus_route as $bus_route_status) {
                                                                $bus_route_status->status = BusRoute::STATUS_INACTIVE;
                                                                $bus_route_status->unique_key = md5(uniqid(rand(), true));
                                                                $bus_route_status->session_key = $bus->session_key;
                                                                $bus_route_status->save(false);
                                                                if ($bus_route_status->id == $updateBusStop->next_stop) {
                                                                    $bus_route_status->status = BusRoute::STATUS_INACTIVE;
                                                                    $bus_route_status->save(false);
                                                                } else {
                                                                    $bus_route_status->status = BusRoute::STATUS_NEXT_STOP;
                                                                    $bus_route_status->save(false);
                                                                }

                                                                $bus_status = new  BusStatus();
                                                                $bus_status->bus_route_id  = $bus_route_status->id;
                                                                $bus_status->status_direction  = $status_direction;
                                                                $bus_status->session_key  = $bus->session_key;
                                                                $bus_status->unique_key  =  $bus_route_status->unique_key;
                                                                $bus_status->status  = BusRoute::STATUS_INACTIVE;
                                                                $bus_status->save(false);
                                                                //get last inserted id
                                                                $bus_status_last_id = $bus_status->id;
                                                                $bus_state = BusStatus::find()->where(['id' => $bus_status_last_id])->one();
                                                                if ($bus_state->bus_route_id == $bus->next_stop) {
                                                                } else {
                                                                    $bus_state->status =  BusRoute::STATUS_NEXT_STOP;
                                                                    $bus_state->save(false);
                                                                }



                                                                //create student student_attendance_bus
                                                                $student_has_bus = StudentHasBus::find()->where(['bus_route_id' => $bus_status->bus_route_id])->all();
                                                                foreach ($student_has_bus as $student_has_bus_student_id_data) {
                                                                    $student_id  = $student_has_bus_student_id_data->student_id;
                                                                    $bus_route_id  = $student_has_bus_student_id_data->bus_route_id;
                                                                    $student_has_bus_id = $student_has_bus_student_id_data->id;
                                                                    $student_attendance_bus = new  StudentAttendanceBus();
                                                                    $student_attendance_bus->student_id = $student_id;
                                                                    $student_attendance_bus->academic_year_id  = $academic_year_id;
                                                                    $student_attendance_bus->bus_route_id  = $bus_route_id;
                                                                    $student_attendance_bus->student_has_bus_id   = $student_has_bus_id;
                                                                    $student_attendance_bus->unique_key = $bus_status->unique_key;
                                                                    $student_attendance_bus->actual_pickup_point  = $student_has_bus_student_id_data->bus_route_id;
                                                                    $student_attendance_bus->session_key = $bus->session_key;
                                                                    $student_attendance_bus->save(false);
                                                                }
                                                            }


                                                            $student_attendance_bus = StudentAttendanceBus::find()->where(['session_key' => $bus->session_key])->all();
                                                            foreach ($student_attendance_bus  as $student_attendance_bus_save) {
                                                                $student_attendance_bus_update = StudentAttendanceBus::find()->where(['id' => $student_attendance_bus_save->id])->one();

                                                                if ($bus->status_direction == BusDetails::status_direction_from_school) {
                                                                    $student_attendance_bus_update->student_status = StudentAttendanceBus::student_status_reached;
                                                                    $student_attendance_bus_update->school_left_time = date('Y-m-d H:i:s');
                                                                }



                                                                $student_attendance_bus_update->save(false);
                                                            }

                                                            $busStatus['busStatus'] = 'Status Changed Successfully';
                                                            $busStatus['CurrentStatus'] =  strip_tags($bus->getStateOptionsBadges());
                                                            $busStatus['bus_id'] =  $bus_id;
                                                        } elseif ($start_or_end == BusDetails::STATUS_PARKING) {
                                                            $busStatus['busStatus'] = 'Status Changed Successfully';
                                                            $busStatus['CurrentStatus'] =  strip_tags($bus->getStateOptionsBadges());
                                                        }
                                                        $data['status'] = self::API_OK;
                                                        $data['details'] =  $busStatus;
                                                    } else {
                                                        $data['status'] = self::API_NOK;
                                                        $data['error'] = "Next Stop Updated Unsuccessful";
                                                    }
                                                } else {
                                                    $data['status'] = self::API_NOK;
                                                    $data['error'] = "Bus Routes Not Found With this bus";
                                                }
                                            } else {
                                                $data['status'] = self::API_NOK;
                                                $data['error'] = "Details Not Saved Retry";
                                            }
                                        }
                                    } else {
                                        $data['status'] = self::API_NOK;
                                        $data['error'] = "Bus Status Inactive Contact to admin";
                                    }
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Bus Details Not Found";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Status And Direction Can Not Empty";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Driver Status Is InActive.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Driver Details Not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "academic year not found contact to school admin.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionEndDrive()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    $bus = BusDetails::find()->where(['id' => $bus_id])->one();

                    if (!empty($bus)) {
                        $bus->status = BusDetails::STATUS_PARKING;
                        $bus->current_stop = 0;
                        $bus->next_stop = 0;
                        $session_key = $bus->session_key;
                        if ($bus->save(false)) {

                            $student_attendance_bus = StudentAttendanceBus::find()->where(['session_key' => $bus->session_key])->andWhere(['status' => StudentAttendanceBus::STATUS_PRESENT])->all();
                            foreach ($student_attendance_bus  as $student_attendance_bus_save) {
                                $student_attendance_bus_update = StudentAttendanceBus::find()->where(['id' => $student_attendance_bus_save->id])->one();

                                if ($bus->status_direction == BusDetails::status_direction_school) {
                                    $student_attendance_bus_update->student_status = StudentAttendanceBus::student_status_reached;
                                    $student_attendance_bus_update->school_reached_time = date('Y-m-d H:i:s');
                                }



                                $student_attendance_bus_update->save(false);
                            }


                            $absentStudentData = StudentDetails::find()
                                ->innerJoinWith('studentHasBuses')
                                ->innerJoinWith('studentAttendanceBuses')
                                ->where(['student_attendance_bus.session_key' => $bus->session_key])
                                ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_ABSENT])
                                ->all();

                            if (!empty($absentStudentData)) {
                                foreach ($absentStudentData as $absentStudentDataDetails) {
                                    $sid = $absentStudentDataDetails->id;
                                    $student_name = $absentStudentDataDetails->student_name;
                                    $StudentHasParent = StudentHasParent::find()->where(['student_id' => $sid])->one();
                                    if (!empty($StudentHasParent)) {
                                        $title = 'Your Children Absent';
                                        $body = 'Your Children Absent ' . $student_name;
                                        $type = '';



                                        Yii::$app->notification->UserNotification('', $absentStudentDataDetails->student->parent->user_id, $title, $body, $type);
                                    }
                                }
                            }
                            $session_key = $bus->session_key;
                            $student_attendance_bus = StudentAttendanceBus::find()->where(['session_key' => $session_key])->andWhere(['status' => StudentAttendanceBus::STATUS_PRESENT])->all();


                            if ($bus->status_direction == BusDetails::status_direction_school) {
                                if (!empty($student_attendance_bus)) {
                                    foreach ($student_attendance_bus as $student_attendance_bus_data) {
                                        $student_id  = $student_attendance_bus_data->student_id;
                                        $getParentIdByStudentId = (new StudentDetails())->getParentIdByStudentId($student_id);
                                        if (!empty($getParentIdByStudentId)) {
                                            $title = 'Your Children Reached At School';
                                            $body = 'Your Children Reached At School ' . $student_name;
                                            $type = '';
                                            Yii::$app->notification->UserNotification('', $getParentIdByStudentId, $title, $body, $type);
                                        }
                                    }
                                }
                            } else {

                                if (!empty($student_attendance_bus)) {
                                    foreach ($student_attendance_bus as $student_attendance_bus_data) {
                                        $student_id  = $student_attendance_bus_data->student_id;
                                        $getParentIdByStudentId = (new StudentDetails())->getParentIdByStudentId($student_id);
                                        if (!empty($getParentIdByStudentId)) {
                                            $title = 'Your Children Reached At Home';
                                            $body = 'Your Children Reached At Home ' . $student_name;
                                            $type = '';
                                            Yii::$app->notification->UserNotification('', $getParentIdByStudentId, $title, $body, $type);
                                        }
                                    }
                                }
                            }







                            $bus_route = BusRoute::find()->where(['session_key' => $session_key])->all();
                            if (!empty($bus_route)) {
                                foreach ($bus_route as $bus_route_data) {
                                    $bus_route_data->status = BusRoute::STATUS_INACTIVE;
                                    $bus_route_data->save(false);
                                }
                                $data['status'] = self::API_OK;
                                $data['details'] = "Bus route Status Updated Successfully";
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "NO bus_routes found.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Failed to update bus status.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Details Not found With Driver.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionBusRoute()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    $bus_details = BusDetails::find()->where(['id' => $bus_id])->one();
                    if ($bus_details->status_direction == BusDetails::status_direction_school) {
                        $bus_route = BusRoute::find()
                            ->joinWith(['busStatuses'])
                            ->where(['bus_id' => $bus_id])->orderBy(['short_order' => SORT_ASC])->all();
                    } else {

                        $bus_route = BusRoute::find()
                            ->joinWith(['busStatuses'])
                            ->where(['bus_id' => $bus_id])->orderBy(['short_order' => SORT_DESC])->all();
                    }


                    if (!empty($bus_route)) {
                        foreach ($bus_route as $bus_route_data) {
                            $bus_route_data_arr[] = $bus_route_data->asJson();
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
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionBusStatus($status = '', $bus_status_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $current_location_lat = isset($post['current_location_lat']) ? $post['current_location_lat'] : '';
        $current_location_lng = isset($post['current_location_lng']) ? $post['current_location_lng'] : '';

        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    $bus = BusDetails::find()->where(['id' => $bus_id])->one();
                    if (!empty($status) && !empty($bus_status_id)) {
                        $bus_status_update = BusStatus::find()->where(['id' => $bus_status_id])->one();
                        if (!empty($bus_status_update)) {
                            $unique_key    = $bus_status_update->unique_key;
                            if ($status == BusStatus::bus_reached) {
                                $bus_status_update->bus_reached_time = Date('Y-m-d H:i:s');
                                //update current stop in bus details
                                $update_current_stop = BusRoute::find()->where(['unique_key' => $unique_key])->one();
                                $bus_root_id_current = $update_current_stop->id;
                                $bus->current_stop = $bus_root_id_current;
                                $bus->current_location_lat = $current_location_lat;
                                $bus->current_location_lng = $current_location_lng;
                                $bus->current_location_coordinates = $current_location_lat . ',' . $current_location_lng;
                                $bus->save(false);

                                // $student_has_bus = StudentHasBus::find()->where(['bus_route_id'=>$bus_root_id_current])->all();
                                $parent_details = StudentHasParent::find()
                                    ->joinWith('student')
                                    ->joinWith('student.studentHasBuses')
                                    ->where(['student_has_bus.bus_id' => $bus_id])
                                    ->andWhere(['student_has_bus.bus_route_id' => $bus_root_id_current])
                                    ->all();
                                foreach ($parent_details as $parent_details_data) {
                                    $parent_id_data = $parent_details_data->parent_id;
                                    $title = 'Bus Arrived';
                                    $body = 'Bus Arrived ' . $update_current_stop->point_name;
                                    $type = '';
                                    Yii::$app->notification->UserNotification('', $parent_id_data, $title, $body, $type);
                                }

                                $student_has_bus = StudentHasBus::find()->where(['bus_id' => $bus_id])->andWhere(['status' => StudentHasBus::STATUS_ACTIVE])->all();
                                if (!empty($student_has_bus)) {
                                    foreach ($student_has_bus as $student_has_bus_data) {
                                        $getParentIdByStudentId = (new StudentDetails())->getParentIdByStudentId($student_has_bus_data->student_id);
                                        if (!empty($getParentIdByStudentId)) {
                                            $title = 'Bus Arrived';
                                            $body = 'Bus Arrived ' . $update_current_stop->point_name;
                                            $type = 'refresh';
                                            Yii::$app->notification->UserNotification('', $getParentIdByStudentId, $title, $body, $type);
                                        }
                                    }
                                }
                            } elseif ($status == BusStatus::bus_left) {
                                $bus_status_update->bus_left_time = Date('Y-m-d H:i:s');

                                if ($bus_status_update->save(false)) {


                                    $bus_route_id =  $bus_status_update->bus_route_id;






                                    $busUpdatePage = BusDetails::find()->where(['id' => $bus_id])->one();
                                    if ($busUpdatePage->save(false)) {
                                        $bus_route_update_last = BusRoute::find()
                                            ->where(['session_key' => $busUpdatePage->session_key])
                                            ->andWhere(['in', 'status', [BusRoute::STATUS_LEFT, BusRoute::STATUS_SKIP]])
                                            ->andWhere(['not in', 'unique_key', [$unique_key]])
                                            ->all();


                                        //get next stop
                                        $next_stop_of_bus_route =  BusRoute::find()
                                            ->where(['session_key' => $busUpdatePage->session_key])
                                            ->andWhere(['unique_key' => $unique_key])
                                            ->one();
                                        $bus_details_current_stop = BusDetails::find()->where(['id' => $bus_id])->one();

                                        if (!empty($next_stop_of_bus_route->short_order)) {
                                            $sortOrder = $next_stop_of_bus_route->short_order;
                                            //get bus direction
                                            if ($bus->status_direction == BusDetails::status_direction_from_school) {
                                                if ($sortOrder == '') {
                                                } else {
                                                    //get completed stop values
                                                    $get_completed_arr = BusRoute::find()->where(['bus_id' => $bus_id])
                                                        ->andWhere(['in', 'status', [BusRoute::STATUS_REACHED, BusRoute::STATUS_COMPLETED, BusRoute::STATUS_LEFT, BusRoute::STATUS_SKIP]])
                                                        ->orderBy(['short_order' => SORT_DESC])->all();

                                                    //get gus route order desc
                                                    $bus_route_arr = BusRoute::find()->where(['bus_id' => $bus_id])->orderBy(['short_order' => SORT_DESC])->all();
                                                    foreach ($bus_route_arr as $bus_route_arr_data) {
                                                        $next_stop_order_arr[] = $bus_route_arr_data->short_order;
                                                    }
                                                    //get current stop
                                                    $current_stop = $bus_details_current_stop->current_stop;

                                                    // $next_stop_order = $sortOrder-1;
                                                    $next_stop_order = $sortOrder;
                                                }
                                            } elseif ($bus->status_direction == BusDetails::status_direction_school) {
                                                //get completed stop values
                                                $get_completed_arr = BusRoute::find()->where(['bus_id' => $bus_id])
                                                    ->andWhere(['in', 'status', [BusRoute::STATUS_REACHED, BusRoute::STATUS_COMPLETED, BusRoute::STATUS_LEFT, BusRoute::STATUS_SKIP]])
                                                    ->orderBy(['short_order' => SORT_ASC])->all();

                                                //get gus route order asc
                                                $bus_route_arr = BusRoute::find()->where(['bus_id' => $bus_id])->orderBy(['short_order' => SORT_ASC])->all();
                                                foreach ($bus_route_arr as $bus_route_arr_data) {
                                                    $next_stop_order_arr[] = $bus_route_arr_data->short_order;
                                                }
                                                //get current stop
                                                $current_stop = $bus_details_current_stop->current_stop;

                                                //    $next_stop_order = $sortOrder+1;
                                                $next_stop_order = $sortOrder;
                                            }
                                        }
                                        if (!empty($get_completed_arr)) {
                                            foreach ($get_completed_arr as $get_completed_arr_data) {
                                                $current_stop_arr_d[] = $get_completed_arr_data->short_order;
                                            }

                                            if (!empty($current_stop_arr_d)) {
                                                $next_stop_arr = array_diff($next_stop_order_arr, $current_stop_arr_d);
                                                if (!empty($next_stop_arr)) {
                                                    $get_next_stop = current($next_stop_arr);
                                                    $next_stop_order = $get_next_stop;
                                                } else {
                                                    $next_stop_order = '';
                                                }
                                            } else {
                                                $next_stop_order = '';
                                            }
                                        } else {
                                            $next_stop_order = '';
                                        }



                                        if (!empty($next_stop_order)) {
                                            //get next stop id
                                            $bus_route_next = BusRoute::find()->where(['session_key' => $busUpdatePage->session_key])->andWhere(['short_order' => $next_stop_order])->one();
                                            if (!empty($bus_route_next)) {
                                                $bus_route_next->status = BusRoute::STATUS_INACTIVE;
                                                if ($bus_route_next->save(false)) {
                                                    //send notification to parent
                                                    $next_stop_unique_key = $bus_route_next->unique_key;
                                                    $student_has_bus = StudentAttendanceBus::find()
                                                        ->joinWith('studentHasBus')
                                                        ->joinWith('studentHasBus')
                                                        ->where(['student_attendance_bus.unique_key' => $next_stop_unique_key])
                                                        ->all();
                                                    if (!empty($student_has_bus)) {
                                                        foreach ($student_has_bus as $student_has_bus_data) {
                                                            $student_id[]  = $student_has_bus_data->student_id;
                                                        }
                                                        if (!empty($student_id)) {
                                                            $student_has_parent = StudentHasParent::find()
                                                                ->where(['in', 'student_id', $student_id])
                                                                ->distinct()
                                                                ->all();
                                                            if (!empty($student_has_parent)) {
                                                                foreach ($student_has_parent as $student_has_parent_id_data) {
                                                                    $title = 'Bus Bus Reached Next Stop';
                                                                    $body = 'Bus Bus Reached Next Stop ' . $bus_route_next->point_name;
                                                                    $type = '';
                                                                    Yii::$app->notification->UserNotification('', $student_has_parent_id_data->parent_id, $title, $body, $type);
                                                                }
                                                            }
                                                        }
                                                    }





                                                    // $title='hello Driver';
                                                    // $body='Your ride is start';
                                                    // $type='';
                                                    // Yii::$app->notification->UserNotification('',$user_id,$title,$body,$type);






                                                    $next_stop_unique_key = $bus_route_next->unique_key;
                                                    //update bus status
                                                    $bus_status_next = BusStatus::find()->where(['unique_key' => $next_stop_unique_key])->one();
                                                    $bus_status_next->status = BusRoute::STATUS_INACTIVE;
                                                    $bus_status_next->save(false);
                                                }
                                            } else {
                                                $bus->endDrive = BusDetails::end_drive_yes;
                                                $bus->save(false);
                                            }
                                        } else {
                                            $bus->endDrive = BusDetails::end_drive_yes;
                                            $bus->save(false);
                                        }



                                        if (!empty($bus_route_update_last)) {
                                            foreach ($bus_route_update_last as $bus_route_update_last_data) {
                                                $bus_route_update_last_data->status = BusRoute::STATUS_COMPLETED;
                                                $bus_route_update_last_data->save(false);
                                            }
                                        } else {
                                            $data['status'] = self::API_NOK;
                                            $data['error'] = "bus route update last failed.";
                                        }
                                    } else {
                                        $data['status'] = self::API_NOK;
                                        $data['error'] = "bus Status update last failed.";
                                    }
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "bus Details Status update  failed.";
                                }
                            }
                            $bus_status_update->status = $status;
                            if ($bus_status_update->save(false)) {
                                $bus_route_id = $bus_status_update->bus_route_id;
                                $session_key = $bus->session_key;
                                $bus->current_stop = $bus_route_id;
                                //get next stop
                                $bus_status_next_stop = BusStatus::find()->where(['session_key' => $session_key])->andWhere(['status' => 0])->limit(1)->one();

                                if (!empty($bus_status_next_stop)) {
                                    $bus->next_stop = $bus_status_next_stop->bus_route_id;
                                    $bus->save(false);
                                }


                                $bus_route_id  = $bus_status_update->bus_route_id;
                                $bus_route = BusRoute::find()->where(['id' => $bus_route_id])->one();
                                $bus_route->status = $status;
                                if ($bus_route->save(false)) {
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $bus_route->asJson();
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Bus Status And Bus Route  Updated Failed.";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Bus Status And Bus Route Not Updated.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "No Route Found With This Id.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Status And Bus Route Id Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }



    public function actionStudentDetailsOfRoute($bus_route_id = '', $unique_key = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    if (!empty($bus_route_id)) {
                        $student_has_bus = StudentHasBus::find()->where(['bus_route_id' => $bus_route_id])->all();
                        if (!empty($student_has_bus)) {
                            $bus_route = BusRoute::find()->where(['id' => $bus_route_id])->one();

                            foreach ($student_has_bus as $student_has_bus_data) {
                                $student_id_data[] = $student_has_bus_data->student_id;
                            }

                            if (!empty($student_id_data)) {
                                if (!empty($unique_key)) {
                                    $student_details = StudentDetails::find()
                                        ->innerJoinWith('studentAttendanceBuses')
                                        ->where(['student_attendance_bus.unique_key' => $unique_key])
                                        ->all();
                                } else {
                                    $student_details = StudentDetails::find()
                                        ->innerJoinWith('studentAttendanceBuses')
                                        ->where(['in', 'student_details.id', $student_id_data])->all();
                                }

                                foreach ($student_details as $student_details_data) {
                                    if (!empty($unique_key)) {
                                        $student_details_data_arr[] = $student_details_data->asJson($unique_key);
                                    } else {
                                        $student_details_data_arr[] = $student_details_data->asJson();
                                    }
                                }

                                if (!empty($student_details_data_arr)) {
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $student_details_data_arr;
                                    $data['unique_key'] = $bus_route->unique_key;
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Student Data Not Found At This Route.";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Student Data Not Found At This Route.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Data Not Found At This Route";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Route Id Can Not Blank.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionStudentDetailsOfRouteAbsent($session_key = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    $bus_route = BusRoute::find()->where(['session_key' => $session_key])
                        ->andWhere(['IN', 'status', [BusRoute::STATUS_LEFT, BusRoute::STATUS_COMPLETED]])
                        ->all();

                    if (!empty($bus_route)) {
                        foreach ($bus_route as $bus_route_data) {
                            $bus_route_id_arr[] = $bus_route_data->id;
                        }


                        if (!empty($student_id_data)) {
                            if (!empty($session_key)) {
                                $student_details = StudentDetails::find()
                                    ->joinWith('studentAttendanceBuses')
                                    ->where(['student_attendance_bus.session_key' => $session_key])
                                    ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_ABSENT])
                                    ->andWhere(['in', 'student_attendance_bus.bus_route_id', $bus_route_id_arr])
                                    ->all();
                            } else {
                                $student_details = StudentDetails::find()->where(['in', 'id', $student_id_data])->all();
                            }
                            foreach ($student_details as $student_details_data) {
                                if (!empty($unique_key)) {
                                    $student_details_data_arr[] = $student_details_data->DriverAsJson($session_key);
                                } else {
                                    $student_details_data_arr[] = $student_details_data->DriverAsJson();
                                }
                            }

                            if (!empty($student_details_data_arr)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = $student_details_data_arr;
                                // $data['unique_key'] = $bus_route->unique_key;
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Student Data Not Found At This Route.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Data Not Found At This Route.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Route Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }







    public function actionStudentAbsentPresentList($status = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                //get bus details
                $bus = BusDetails::find()->where(['id' => $bus_id])->one();
                $session_key = $bus->session_key;

                if (!empty($bus_id)) {
                    $student_details = StudentDetails::find()
                        ->joinWith('studentAttendanceBuses')
                        ->where(['student_attendance_bus.session_key' => $session_key])
                        ->andWhere(['student_attendance_bus.status' => $status])
                        ->all();
                    if (!empty($student_details)) {
                        $countPresent = StudentDetails::find()
                            ->joinWith('studentAttendanceBuses')
                            ->where(['student_attendance_bus.session_key' => $session_key])
                            ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_PRESENT])
                            ->count();

                        $CountAbsent = StudentDetails::find()
                            ->joinWith('studentAttendanceBuses')
                            ->where(['student_attendance_bus.session_key' => $session_key])
                            ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_ABSENT])
                            ->count();


                        foreach ($student_details as $student_details_data) {
                            $stuArr[] = $student_details_data->DriverAsJson($session_key);
                        }
                        if (!empty($stuArr)) {
                            $data['status'] = self::API_OK;
                            $data['details'] =  $stuArr;
                            $data['countPresent'] =  $countPresent;
                            $data['CountAbsent'] =  $CountAbsent;
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Details Not found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Details Not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }




    public function actionBusLiveRoute()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                if (!empty($bus_id)) {
                    $bus_details = BusDetails::find()->where(['id' => $bus_id])->one();

                    $query = BusRoute::find()->where(['bus_id' => $bus_id]);
                    $query->andWhere(['in', 'status', [
                        BusRoute::STATUS_INACTIVE,
                        BusRoute::STATUS_REACHED,
                        BusRoute::STATUS_LEFT,
                        BusRoute::STATUS_SKIP,
                        BusRoute::STATUS_NEXT_STOP
                    ]]);


                    if ($bus_details->status_direction == BusDetails::status_direction_school) {
                        $bus_detailsD = new ActiveDataProvider([
                            'query' => $query,
                            'sort' => [
                                'defaultOrder' => [
                                    'short_order' => SORT_ASC,
                                ],
                            ],
                            'pagination' => [
                                'pageSize' => 3,
                                'page' => 0,
                            ],
                        ]);
                    } else {
                        $bus_detailsD = new ActiveDataProvider([
                            'query' => $query,
                            'sort' => [
                                'defaultOrder' => [
                                    'short_order' => SORT_DESC,
                                ],
                            ],
                            'pagination' => [
                                'pageSize' => 3,
                                'page' => 0,
                            ],
                        ]);
                    }
                    foreach ($bus_detailsD->models as $bus_route_data) {
                        $bus_route_data_arr[] = $bus_route_data->asJson();
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
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionStudentAttendanceBus()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $driver_has_bus = DriverHasBus::find()->where(['driver_id' => $user_id])->one();
            if (!empty($driver_has_bus)) {
                $bus_id = $driver_has_bus->bus_id;
                $campus_id = $driver_has_bus->campus_id;
                $driver_id  = $driver_has_bus->driver_id;

                if (!empty($bus_id)) {
                    $bus = BusDetails::find()->where(['id' => $bus_id])->one();
                    if (!empty($post)) {
                        $student_id = !empty($post['student_id']) ? $post['student_id'] : '';
                        $unique_key = !empty($post['unique_key']) ? $post['unique_key'] : '';
                        $route_id = !empty($post['route_id']) ? $post['route_id'] : '';

                        $status = isset($post['status']) ? $post['status'] : 0;
                        if (!empty($student_id)) {
                            $update_student_attendance_bus = StudentAttendanceBus::find()->where(['student_id' => $student_id])->andWhere(['unique_key' => $unique_key])->one();
                            if (!empty($update_student_attendance_bus)) {
                                $update_student_attendance_bus->status = $status;

                                if ($bus->status_direction == BusDetails::status_direction_school) {

                                    if ($status == StudentAttendanceBus::STATUS_PRESENT) {
                                        $update_student_attendance_bus->student_picked_up_point  = $route_id;
                                        $update_student_attendance_bus->pickup_point_time  = date('Y-m-d H:i:s');
                                        $update_student_attendance_bus->student_status  = StudentAttendanceBus::student_status_picked;
                                    } elseif ($status == StudentAttendanceBus::STATUS_ABSENT) {
                                        $update_student_attendance_bus->student_picked_up_point  = '';
                                        $update_student_attendance_bus->student_status  = '';
                                    }
                                } else {

                                    if ($status == StudentAttendanceBus::STATUS_PRESENT) {
                                        $update_student_attendance_bus->home_reached_time  = date('Y-m-d H:i:s');
                                        $update_student_attendance_bus->student_status  = StudentAttendanceBus::student_status_reached;
                                    } elseif ($status == StudentAttendanceBus::STATUS_ABSENT) {
                                        $update_student_attendance_bus->student_picked_up_point  = '';
                                        $update_student_attendance_bus->student_status  = '';
                                    }
                                }



                                if ($update_student_attendance_bus->save(false)) {
                                    $data['status'] = self::API_OK;
                                    $data['details'] = $update_student_attendance_bus->asJson();
                                } else {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Student Attendance Not Updated.";
                                }
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = "Student Details Not Found.";
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Student Attendance id not Found.";
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Id Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Details Not found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Driver Details Not found.";
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
                $data['error'] = "Bus Id Required.";
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
}
