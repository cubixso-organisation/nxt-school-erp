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
use app\modules\admin\models\Auth;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\CampusHasUsers;
use app\modules\admin\models\BusDetails;
use app\modules\admin\models\BusRoute;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Category;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\StudentAttendanceBus;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\UserOtp;
use app\components\SendOtp;


class BusCoOrdinatorController extends BKController
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
                            'view-profile',
                            'bus-details',
                            'my-campus',
                            'all-bus-details',
                            'count-of-bus-students',
                            'bus-details-by-id',
                            'student-details-of-bus',
                            'bus-route',
                            'student-bus-absent-present',
                            'my-notifications',
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
                            'view-profile',
                            'bus-details',
                            'my-campus',
                            'all-bus-details',
                            'count-of-bus-students',
                            'bus-details-by-id',
                            'bus-route',
                            'student-details-of-bus',
                            'student-bus-absent-present',
                            'my-notifications',
                            'logout',


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
                $data['detail'] = $user->asJsonBusCoordinator();
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

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';

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
            $numbers = $setting->getSettingByKey('bus_coordinator');
            $explodeNumber  = explode(',', $numbers);




            if (in_array($post['contact_no'], $explodeNumber)) {
                $otp_match = true;
            }else {
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
                $providerId = "BusCoOrdinator";
                $checkUserExist = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_BUS_COORDINATOR])->one();
                if (!empty($checkUserExist)) {
                    if ($checkUserExist->status == User::STATUS_ACTIVE) {
                        $auth_id = $contact_no;
                        $auth = Auth::find()->where([
                            'user_id' => $checkUserExist->id,
                        ])->one();

                        if ($auth) {
                            //update auth
                            $auth->source = $providerId;
                            $auth->source_id = $auth_id;
                            $auth->save(false);
                            $userHasCampusAccess = (new User())->userHasCampusAccess($auth->user_id);
                            if ($userHasCampusAccess == true) {
                                $user = $auth->user;
                                $user->device_token = $post['device_token'];
                                $user->device_type = $post['device_type'];
                                Yii::$app->user->login($user);
                                $data['status'] = self::API_OK;
                                $data['details'] = $user;
                                $data['auth_code'] = AuthSession::newSession($user)->auth_code;
                            } else {
                                $data['status'] = self::API_NOK;
                                $data['error'] = 'you don\'t have access contact to admin';
                            }
                        } else {
                            $data['status'] = self::API_NOK;
                            $data['error'] = 'you don\'t have access contact to admin';
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = 'User is in active contact to admin';
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = 'User data not found';
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = $msg;
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No  Data Posted");
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
                $profile = User::find()->where(['id' => $user_id])->one();
                if (!empty($profile)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $profile->asJsonBusCoordinator();
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



    public function actionBusDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $page = isset($post['page']) ? $post['page'] : '0';

            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
                $campus_id = $userHasCampus->campus_id;
                $query = BusDetails::find()->where(['campus_id' => $campus_id]);
                $bus_details = new ActiveDataProvider([
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


                if (!empty($bus_details)) {
                    foreach ($bus_details->models as $bus_details_data) {
                        $list[] = $bus_details_data->asJson();
                    }
                    if (!empty($list)) {
                        $data['details'] = $list;
                        $data['status'] = self::API_OK;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Bus Not Data Found";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Not Data Found";
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



    public function actionMyCampus()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $campus_has_users = CampusHasUsers::find()->where(['user_id' => $user_id])->all();
            foreach ($campus_has_users as  $campus_has_users_data) {
                $campus_id_data[] = $campus_has_users_data->campus_id;
            }

            if (!empty($campus_id_data)) {
                $campus = Campus::find()->where(['in', 'id', $campus_id_data])->all();
                if (!empty($campus)) {
                    foreach ($campus as $campus_data) {
                        $campus_data_arr[] = $campus_data->asJson();
                    }
                    $data['status'] = self::API_OK;
                    $data['details'] = $campus_data_arr;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Campus Data Not Found.";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Campus Data Not Found.";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionAllBusDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $page = isset($post['page']) ? $post['page'] : '0';
            $campus_id = isset($post['campus_id']) ? $post['campus_id'] : '0';
            if (!empty($campus_id)) {
                $query = BusDetails::find()->where(['campus_id' => $campus_id]);
            } else {
                $query = BusDetails::find()->where(['campus_id' => $campus_id]);
                $campus_has_users = CampusHasUsers::find()->where(['user_id' => $user_id])->all();



                if (!empty($campus_has_users)) {
                    foreach ($campus_has_users as  $campus_has_users_data) {
                        $campus_id_data[] = $campus_has_users_data->campus_id;
                    }


                    if (!empty($campus_id_data)) {
                        $query = BusDetails::find()->where(['in', 'campus_id', $campus_id_data]);
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Campus Data Not Found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Details found.";
                }
            }



            $bus_details = new ActiveDataProvider([
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


            if (!empty($bus_details)) {
                foreach ($bus_details->models as $bus_details_data) {
                    $list[] = $bus_details_data->asJson();
                }

                if (!empty($list)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $list;
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Bus Not Data Found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Bus Not Data Found";
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }


    public function actionCountOfBusStudents($campus_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($campus_id)) {
                $count['busCount'] = BusDetails::find()->where(['campus_id' => $campus_id])->Count();
                $count['studentCount'] = StudentHasBus::find()->where(['campus_id' => $campus_id])->Count();
                $data['status'] = self::API_OK;
                $data['details'] = $count;
            } else {
                $count['busCount'] = 0;
                $count['studentCount'] = 0;
                $data['status'] = self::API_OK;
                $data['details'] = $count;
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
                $bus_details = BusDetails::find()
                    ->where(['id' => $bus_id])->one();
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



    public function actionBusRoute($bus_id = '', $startDate = '', $endDate = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                // if(!empty($startDate) && !empty($endDate)){
                //     $start = $startDate;
                //     $end = $endDate;

                // }else{
                //     $start = date('y-m-d 00:00:00');
                //     $end = date('y-m-d H:i:s');
                // }

                $busDetails = BusDetails::find()->where(['id' => $bus_id])->one();

                if ($busDetails->status_direction == BusDetails::status_direction_from_school) {
                    $bus_route = BusRoute::find()
                        ->joinWith(['busStatuses'])
                        ->where(['bus_id' => $bus_id])
                        ->orderBy(['short_order' => SORT_DESC])->all();
                } elseif ($busDetails->status_direction == BusDetails::status_direction_school) {
                    $bus_route = BusRoute::find()
                        ->joinWith(['busStatuses'])
                        ->where(['bus_id' => $bus_id])
                        ->orderBy(['short_order' => SORT_ASC])->all();
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
            $data['error'] = "No User found.";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionStudentDetailsOfBus($bus_id = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                $student_details = StudentDetails::find()
                    ->innerJoinWith('studentHasBuses as shb')
                    ->innerJoinWith('studentAttendanceBuses')
                    ->where(['shb.bus_id' => $bus_id])->all();
                if (!empty($student_details)) {
                    foreach ($student_details as $student_details_data) {
                        $list[] = $student_details_data->StudentDetailsOfBudAsJson();
                    }
                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Data Not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Data Not found.";
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




    public function actionStudentBusAbsentPresent($bus_id = '', $start = '', $end = '', $status = '')
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            if (!empty($bus_id)) {
                $student_details = StudentDetails::find()->joinWith('studentHasBuses as shb')->where(['shb.bus_id' => $bus_id])->all();
                if (!empty($student_details)) {
                    foreach ($student_details as $student_details_data) {
                        $list[] = $student_details_data->StudentDetailsOfBudAsJson($start, $end, $status);
                    }

                    $countPresent = StudentDetails::find()
                        ->joinWith('studentAttendanceBuses')
                        ->joinWith('studentHasBuses')
                        ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_PRESENT])
                        ->andWhere(['student_has_bus.bus_id' => $bus_id])
                        ->andFilterWhere(['between', 'student_attendance_bus.created_on', $start, $end])
                        ->count();

                    $CountAbsent = StudentDetails::find()
                        ->joinWith('studentAttendanceBuses')
                        ->joinWith('studentHasBuses')
                        ->andWhere(['student_attendance_bus.status' => StudentAttendanceBus::STATUS_ABSENT])
                        ->andWhere(['student_has_bus.bus_id' => $bus_id])
                        ->andFilterWhere(['between', 'student_attendance_bus.created_on', $start, $end])
                        ->count();


                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                        $data['countPresent'] = $countPresent;
                        $data['CountAbsent'] = $CountAbsent;
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Student Data Not found.";
                    }
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Student Data Not found.";
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
