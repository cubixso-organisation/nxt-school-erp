<?php

namespace app\modules\api\controllers;

use app\modules\api\controllers\BKController;
use app\modules\hostelmanagement\models\base\WardenAttandance;
use app\modules\hostelmanagement\models\Floor;
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
use app\modules\hostelmanagement\models\base\HostellersAttandance;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\components\SendOtp;
use app\modules\admin\models\UserOtp;
use app\modules\hostelmanagement\models\base\HostlerAttendanceSettings;
use app\modules\hostelmanagement\models\Rooms as ModelsRooms;
use app\modules\hostelmanagement\models\WardenToHostel;
use Exception;

class ChiefWardenController extends BKController
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
                            'send-otp-chief-warden',
                            'resend-otp-chief-warden',
                            'verify-otp-chief-warden',
                            'hostels',
                            'floor',
                            'rooms',
                            'student-by-rooms',
                            'all-students',
                            'mark-attendance',
                            'get-all-students',
                            'assign-rooms',
                            'warden',
                            'mark-warden-attendance',
                            'student-attandance-history',
                            'student-detail',
                            'logout',
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
                            'send-otp-chief-warden',
                            'resend-otp-chief-warden',
                            'verify-otp-chief-warden',
                            'hostels',
                            'floor',
                            'rooms',
                            'student-by-rooms',
                            'all-students',
                            'mark-attendance',
                            'get-all-students',
                            'assign-rooms',
                            'warden',
                            'mark-warden-attendance', 'student-attandance-history', 'student-detail', 'logout',
                            'no-of-attendance'






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
    public function actionVerifyOtpChiefWarden()
    {
        $data = [];
        $post = Yii::$app->request->post();
        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $otp_code = $post['otp_code'];
            $setting = new WebSetting();
            $numbers = $setting->getSettingByKey('chief_warden');
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
    public function actionSendOtpChiefWarden()
    {
        $data = [];
        $post = Yii::$app->request->post();
        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])->one();
            if (!empty($user_check)) {
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
                $data['error'] = Yii::t("app", "Chief Warden Is Not Registred");
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
                $sms = 'Dear Parent, ' . $otp . ' is the OTP for login into Hostel Management  App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
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
                $data['error'] = Yii::t("app", "Chef Warden is not registred");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No data posted");
        }
        return $this->sendJsonResponse($data);
    }
    public function actionHostels()
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            // var_dump((new User())->getChiefWardenCampus($user_id));
            //     exit;
            if (!empty($user_id)) {


                $hostels  =  Hostels::find()->where(['campus_id' => (new User())->getChiefWardenCampus($user_id)])->andWhere(['status' => Hostels::STATUS_ACTIVE])->all();
                if (empty($hostels)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Hostels Fond.";
                    return $this->sendJsonResponse($data);
                } else {
                    foreach ($hostels as $hostel) {
                        $dd[] =   $hostel->asHostelList();
                    }
                    $data['status'] = self::API_OK;
                    $data['details'] = $dd;
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
    // Floor By Hostels
    public function actionFloor($hostel_id)
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {
                if (empty($hostel_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Required Hostel Id";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                    $hostelId = Hostels::find()->where(['id' => $hostel_id])->andWhere(['campus_id' => (int)$wardenCampusId])->one();
                    if (empty($hostelId)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "This hostel is not belongs to this campus";
                        return $this->sendJsonResponse($data);
                    }
                    $floors  =  Floor::find()->where(['hostel_id' => ($hostel_id)])->andWhere(['status' => Floor::STATUS_ACTIVE])->all();
                    if (empty($floors)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Floors Fond.";
                        return $this->sendJsonResponse($data);
                    } else {
                        foreach ($floors as $floor) {
                            $dd[] =   $floor->floorListJson();
                        }
                        $data['status'] = self::API_OK;
                        $data['details'] = $dd;
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
    // Rooms 
    public function actionRooms($floor_id)
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {
                if (empty($floor_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Required Floor Id";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                    $roooms  =  Rooms::find()->where(['floor_id' => (int)$floor_id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->all();
                    if (empty($roooms)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Rooms Fond.";
                        return $this->sendJsonResponse($data);
                    } else {
                        foreach ($roooms as $room) {
                            $dd[] =   $room->roomListJson();
                        }
                        $data['status'] = self::API_OK;
                        $data['details'] = $dd;
                        $totalRooms  =  Rooms::find()->where(['floor_id' => (int)$floor_id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->count();

                        $data['total_rooms'] = $totalRooms;
                        $warden = WardenToHostel::find()->where(['floor_id' => $floor_id])->one();
                        if (!empty($warden)) {
                            $warden = User::find()->where(['id' => $warden->warden_id])->one();
                            if (!empty($warden)) {
                                $data['warden_name'] = $warden->first_name;
                            } else {
                                $data['warden_name'] = "";
                            }
                        } else {
                            $data['warden_name'] = "";
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
    // Student by Rooms
    public function actionStudentByRooms($room_id, $attendance_no = '')
    {
        $data = [];
        $dd = [];

        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        try {
            if (empty($user_id)) {
                throw new Exception("Warden Not Found");
            }

            if (empty($room_id)) {
                throw new Exception("Required Room ID");
            }

            $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
            $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $wardenCampusId])->one();

            if (empty($attendanceSetting)) {
                throw new Exception("Attendance Settings not found");
            }

            $hostellars = Hostellers::find()->where(['room_id' => (int)$room_id])->andWhere(['status' => Hostellers::STATUS_ACTIVE])->all();

            if (empty($hostellars)) {
                $data['status'] = self::API_NOK;
                $data['message'] = "No Students Found";
            } else {
                foreach ($hostellars as $hostellar) {
                    $dd[] = $hostellar->studentListJson($attendance_no);

                    // Mark attendance for each student
                    for ($i = 1; $i <= $attendanceSetting->daily_attendance_count; $i++) {
                        $hostellerAttendance = HostellersAttandance::find()
                            ->where(['attendance_count_perday' => $i])
                            ->andWhere(['Date(date)' => date('Y-m-d')])
                            ->andWhere(['student_id' => $hostellar->student_id])
                            ->one();

                        if (empty($hostellerAttendance)) {
                            $attendanceRecord = new HostellersAttandance();
                            $attendanceRecord->campus_id = $hostellar->campus_id;
                            $attendanceRecord->hostel_id = $hostellar->hostel_id;
                            $attendanceRecord->student_id = $hostellar->student_id;
                            $attendanceRecord->room_id = $hostellar->room_id;
                            $attendanceRecord->attandance = HostellersAttandance::NOT_MARKED;
                            $attendanceRecord->attendance_count_perday = $i;
                            $attendanceRecord->date = date('Y-m-d');
                            $attendanceRecord->attandance_by = $user_id;
                            $attendanceRecord->save(false);
                        }
                    }
                }

                $data['status'] = self::API_OK;
                $data['details'] = $dd;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'api');
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }

    public function actionNoOfAttendance()
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        $post = Yii::$app->request->post();

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
        $wardenCampusId = (new User())->getChiefWardenCampus($user_id);

        $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $wardenCampusId])->one();


        $student_rooms = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->andWhere(['campus_id' => $wardenCampusId])->all();

        if (empty($student_rooms)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "No Students Found";
            return $this->sendJsonResponse($data);
        }

        // Process student data and create response
        $list = [];
        foreach ($student_rooms as $student) {
            // var_dump($student);exit;

            $hostellerAttendace = HostellersAttandance::find()
    ->select(['attendance_count_perday']) // Select only the grouped column
    ->where(['student_id' => $student->student_id])
    ->andWhere(['campus_id' => $student->campus_id])
    ->andWhere(['Date(date)' => date('Y-m-d')])
    ->groupBy(['attendance_count_perday']) // Group by attendance_count_perday
    ->all();

            // var_dump($hostellerAttendace);exit;
            foreach ($hostellerAttendace as $noAttendance) {
                $list[] = $noAttendance->attendance_count_perday;
            }
        }


        if (!empty($attendanceSetting)) {

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
    // All Students
    public function actionAllStudents()
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {
                $page = isset($post['page']) ? $post['page'] : 0;
                $search_key = isset($post['search_key']) ? $post['search_key'] : "";
                $hostel_id = isset($post['hostel_id']) ? $post['hostel_id'] : "";
                $attendance_no = isset($post['attendance_no']) ? $post['attendance_no'] : "";

                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                    $attendanceSetting = HostlerAttendanceSettings::find()->where(['campus_id' => $wardenCampusId])->one();

                    $hostellarsQuery = Hostellers::find()
                        ->joinWith(['student as stu'])
                        ->where(['hostellers.campus_id' => (int)$wardenCampusId])
                        ->andWhere(['IS NOT', 'hostellers.room_id', null])
                        ->andWhere(['hostellers.status' => Hostellers::STATUS_ACTIVE]);
                        // $query = $hostellarsQuery->createCommand()->rawSql;
                        // var_dump($query);exit;

                    // Filter by search key if it is provided
                    if (!empty($search_key)) {
                        $hostellarsQuery->andWhere(['like', 'stu.student_name', $search_key]);
                    }

                    // Filter by hostel_id if it is provided
                    if (!empty($hostel_id)) {
                        $hostellarsQuery->andWhere(['hostellers.hostel_id' => $hostel_id]);
                    }
                    if (empty($hostellarsQuery)) {
                        $count = 0;
                    } else {
                        $count = $hostellarsQuery->count();
                    }
                    $hostelarr = new ActiveDataProvider([
                        'query' => $hostellarsQuery,
                        'sort' => [
                            'defaultOrder' => [
                                'id' => SORT_DESC,
                            ],
                        ],
                        'pagination' => [
                            'pageSize' => 10000,
                            'page' => $page,
                            'totalCount' => $count, // Ensure this is set correctly
                            'page' => $page,
                        ],
                    ]);
                    // var_dump($hostelarr->pagination);exit;
                    $totalPages = $hostelarr->pagination->getPageCount();
                    if (empty($hostelarr->models)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Students Found.";
                    } else {
                        foreach ($hostelarr->models as $hostellar) {
                            $dd[] = $hostellar->studentListJson($attendance_no);
                            for ($i = 1; $i <= $attendanceSetting->daily_attendance_count; $i++) {
                                $hostellerAttendance = HostellersAttandance::find()
                                    ->where(['attendance_count_perday' => $i])
                                    ->andWhere(['Date(date)' => date('Y-m-d')])
                                    ->andWhere(['student_id' => $hostellar->student_id])
                                    ->one();

                                if (empty($hostellerAttendance)) {
                                    $attendanceRecord = new HostellersAttandance();
                                    $attendanceRecord->campus_id = $hostellar->campus_id;
                                    $attendanceRecord->hostel_id = $hostellar->hostel_id;
                                    $attendanceRecord->student_id = $hostellar->student_id;
                                    $attendanceRecord->room_id = $hostellar->room_id;
                                    $attendanceRecord->attandance = HostellersAttandance::NOT_MARKED;
                                    $attendanceRecord->attendance_count_perday = $i;
                                    $attendanceRecord->date = date('Y-m-d');
                                    $attendanceRecord->attandance_by = $user_id;
                                    $attendanceRecord->save(false);
                                }
                            }
                        }
                        $data['status'] = self::API_OK;
                        $data['details'] = $dd;
                        $data['total_pages'] = $totalPages - 1;
                        $data['current_page'] = $page;
                        $data['totalCount'] = $count;
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

    // Mark Attandance


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
                $attendance = isset($post['attendance']) ? $post['attendance'] : 0;
                $attendance_no = isset($post['attendance_no']) ? $post['attendance_no'] : 1;

                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);

                    $hostellars = Hostellers::find()
                        ->where(['student_id' => (int)$student_id])
                        ->andWhere(['campus_id' => (int)$wardenCampusId])
                        ->one();

                    if (empty($hostellars)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Invalid Student Id";
                    } else {
                        $today = date('Y-m-d');
                        $existingAttendance = HostellersAttandance::find()
                            ->where(['student_id' => $hostellars->student_id])
                            ->andWhere(['attendance_count_perday' => $attendance_no])
                            ->andWhere(['DATE(date)' => $today])
                            ->one()
                        ;
                        // print_r($existingAttendance->createCommand()->getRawSql());
                        // exit;
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
                            $wardenAttandance = new HostellersAttandance();
                            $wardenAttandance->campus_id = $hostellars->campus_id;
                            $wardenAttandance->room_id = $hostellars->room_id;
                            $wardenAttandance->hostel_id = $hostellars->hostel_id;
                            $wardenAttandance->student_id = $hostellars->student_id;
                            $wardenAttandance->attendance_count_perday = (int)$attendance_no;
                            $wardenAttandance->attandance = $attendance;
                            $wardenAttandance->date = date('Y-m-d H:i:s');
                            $wardenAttandance->attandance_by = $user_id;
                            $wardenAttandance->status = HostellersAttandance::STATUS_ACTIVE;

                            if ($wardenAttandance->save(false)) {
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





    public function actionGetAllStudents()
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {

                // var_dump($page);exit;
                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Fond";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);

                    // Get hostellers' student IDs
                    $hostellerStudentIds = Hostellers::find()
                        ->select('student_id')
                        ->where(['campus_id' => $wardenCampusId])
                        ->andWhere(['room_id' => null])
                        ->andWhere(['status' => Hostellers::STATUS_ACTIVE])
                        ->column();

                    // Exclude hostellers from the query
                    $hostellarsQuery = StudentDetails::find()
                        ->where(['campus_id' => (int)$wardenCampusId])
                        ->andWhere(['IN', 'user_id', $hostellerStudentIds])
                        ->all();

                    if (empty($hostellarsQuery)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Students Found.";
                        return $this->sendJsonResponse($data);
                    } else {
                        foreach ($hostellarsQuery as $hostellar) {
                            $dd[] = $hostellar->asJsonForHostel();
                        }
                        $data['status'] = self::API_OK;
                        $data['details'] = $dd;
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

    // Assign rooms to student

    public function actionAssignRooms()
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {

                // var_dump($page);exit;
                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Fond";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                    // var_dump($wardenCampusId);exit;
                    $studentId = isset($post['student_id']) ? $post['student_id'] : "";
                    $hostelId = isset($post['hostel_id']) ? $post['hostel_id'] : "";
                    $floor = isset($post['floor_id']) ? $post['floor_id'] : "";
                    $room_id = isset($post['room_id']) ? $post['room_id'] : "";
                    // Check Beds available in the room or not


                    $room = Rooms::find()->where(['id' => $room_id])->one();
                    if ($room->available_bed == 0) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Bed Available in the room please choose another room.";
                        return $this->sendJsonResponse($data);
                    }

                    $wardenToHostel = WardenToHostel::find()->where(['floor_id' => (int)$floor])->one();
                    if (!empty($wardenToHostel)) {
                        $warden_id = $wardenToHostel->warden_id;
                    } else {
                        $warden_id = 0;
                    }

                    // Check Hosteller is alredy assigned with the room or not

                    $hostellers = Hostellers::find()->where(['student_id' => $studentId])->andWhere(['campus_id' => $wardenCampusId])->one();
                    if (empty($hostellers)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Invalid Student or student not imported.";
                        return $this->sendJsonResponse($data);
                    }

                    if (empty($hostellers->room_id)) {
                        $studentDetail = StudentDetails::find()->where(['user_id' => (int)$studentId])->andWhere(['campus_id' => $wardenCampusId])->one();
                        if (empty($studentDetail)) {
                            $data['status'] = self::API_NOK;
                            $data['error'] = "Invalid Student id";
                            return $this->sendJsonResponse($data);
                        } else {
                            $hostellers->campus_id = (int)$wardenCampusId;
                            $hostellers->hostel_id = (int)$hostelId;
                            $hostellers->joining_date = date('Y-m-d H:i:s');
                            $hostellers->room_id = (int)$room_id;
                            $hostellers->floor_id = (int)$floor;
                            $hostellers->warden_id = $warden_id;
                            $hostellers->photo = $studentDetail->profile_photo ?? "";
                            $hostellers->address = $studentDetail->permanent_address ?? "";
                            $hostellers->status = Hostellers::STATUS_ACTIVE;
                            $hostellers->onboarded_by = $user_id;
                            if ($hostellers->save(false)) {
                                // Updating beds number in the room

                                $room = Rooms::find()->where(['id' => $room_id])->one();
                                $room->available_bed = $room->available_bed - 1;
                                if ($room->save(false)) {
                                    $data['status'] = self::API_NOK;
                                    $data['error'] = "Room Assigned Succesfully.";
                                    return $this->sendJsonResponse($data);
                                }
                            }
                        }
                    } else {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Room alredy assigned for this student.";
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

    // Wardens

    public function actionWarden()
    {
        $data = [];
        $dd = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        try {
            if (!empty($user_id)) {

                // var_dump($page);exit;
                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Fond";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                    $wardens =  User::find()->where(['campus_id' => (int)$wardenCampusId])->andWhere(['user_role' => User::ROLE_WARDEN])->andWhere(['status' => User::STATUS_ACTIVE])->all();

                    if (empty($wardens)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "No Warden found";
                        return $this->sendJsonResponse($data);
                    } else {
                        foreach ($wardens as $warden) {
                            $dd[] = $warden->asWardenList();
                        }
                        if (!empty($dd)) {
                            $data['status'] = self::API_OK;
                            $data['details'] = $dd;
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

    // Marking Warden Attandance
    public function actionMarkWardenAttendance()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        try {
            if (!empty($user_id)) {
                $warden_id = isset($post['warden_id']) ? $post['warden_id'] : 0;
                $attendance = isset($post['attendance']) ? $post['attendance'] : 0;

                if (empty($user_id)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Session Not Found";
                    return $this->sendJsonResponse($data);
                } else {
                    $wardenCampusId = (new User())->getChiefWardenCampus($user_id);

                    $wardens = User::find()
                        ->where(['id' => (int)$warden_id])
                        ->andWhere(['campus_id' => (int)$wardenCampusId])
                        ->andWhere(['user_role' => User::ROLE_WARDEN])
                        ->one();

                    if (empty($wardens)) {
                        $data['status'] = self::API_NOK;
                        $data['error'] = "Invalid Warden Id";
                    } else {

                        $wardenToHostel = WardenToHostel::find()->where(['warden_id' => $wardens->id])->one();
                        if (!empty($wardenToHostel)) {
                            $hostelId = $wardenToHostel->hostel_id;
                        } else {
                            $hostelId = Null;
                        }

                        $today = date('Y-m-d');
                        $existingAttendance = WardenAttandance::find()
                            ->where(['warden_id' => $wardens->id])
                            ->andWhere(['DATE(date)' => $today]) // Compare only the date part
                            ->one();

                        if ($existingAttendance) {
                            // Attendance already exists for today, update it
                            $existingAttendance->hostel_id = $hostelId;
                            $existingAttendance->attandance = $attendance;
                            $existingAttendance->date = date('Y-m-d H:i:s');
                            $existingAttendance->attandance_by = $user_id;


                            if ($existingAttendance->save(false)) {
                                $data['status'] = self::API_OK;
                                $data['details'] = "Attendance Updated Successfully";
                            }
                        } else {
                            // Attendance does not exist for today, add new entry
                            $wardenAttandance = new WardenAttandance();
                            $wardenAttandance->campus_id = $wardens->campus_id;
                            $wardenAttandance->hostel_id = $hostelId;
                            $wardenAttandance->warden_id = $warden_id;
                            $wardenAttandance->attandance = $attendance;
                            $wardenAttandance->date = date('Y-m-d H:i:s');
                            $wardenAttandance->attandance_by = $user_id;
                            $wardenAttandance->status = WardenAttandance::STATUS_ACTIVE;

                            if ($wardenAttandance->save(false)) {
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


    // Attandance History

    public function actionStudentAttandanceHistory()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');

        try {
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (!empty($user_id)) {
                $wardenCampusId = (new User())->getChiefWardenCampus($user_id);
                $attandanceType = $post["attandance_type"] ?? "";
                $start_date = $post["start_date"] ?? date('Y-m-d');
                $end_date = $post["end_date"] ?? date('Y-m-d');
                $hostelId = $post["hostel_id"] ?? "";
                $attendance_no = $post["attendance_no"] ?? "";
                $page = $post['page'] ?? "";

                $hostellersAttandanceQuery = HostellersAttandance::find()->where(['campus_id' => $wardenCampusId])->andWhere(['attendance_count_perday' => $attendance_no]);

                if (!empty($attandanceType)) {
                    $hostellersAttandanceQuery->andWhere(['attandance' => $attandanceType]);
                }

                if (!empty($start_date) && !empty($end_date)) {
                    $hostellersAttandanceQuery->andWhere(['between', 'Date(date)', $start_date, $end_date]);
                }

                if (!empty($hostelId)) {
                    $hostellersAttandanceQuery->andWhere(['hostel_id' => $hostelId]);
                } else {
                    // Set default values for today's attendance if no hostelId is provided
                    $hostellersAttandanceQuery;
                }

                $dataProvider = new ActiveDataProvider([
                    'query' => $hostellersAttandanceQuery,
                    'sort' => [
                        'defaultOrder' => ['id' => SORT_DESC],
                    ],
                    'pagination' => [
                        'pageSize' => 10000,
                        'page' => $page,
                    ],
                ]);

                $data['totalPages'] = $dataProvider->pagination->pageCount;
                $data['totalCount'] = $dataProvider->totalCount;

                if (empty($dataProvider->models)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Students Found.";
                } else {
                    $data['status'] = self::API_OK;
                    $data['details'] = array_map(fn ($hostellar) => $hostellar->asJsonForAttendenceHistory(), $dataProvider->models);
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
        }

        return $this->sendJsonResponse($data);
    }




    public function actionStudentDetail()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');

        try {
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (!empty($user_id)) {
                $studentDetail = StudentDetails::findOne(['user_id' => $post['student_id']]);
                if (!empty($studentDetail)) {
                    $data['status'] = self::API_OK;
                    $data['details'] = $studentDetail->studentDetailWarden();
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Detials found";
                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No User found.";
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
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
}
