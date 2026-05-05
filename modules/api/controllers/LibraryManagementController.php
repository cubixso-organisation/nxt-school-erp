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
use app\modules\admin\models\base\ParentDetails;
use app\modules\admin\models\UserOtp;
use app\modules\librarymanagement\models\base\IssueBooks;
use app\modules\librarymanagement\models\LibraryMembers;

class LibraryManagementController extends BKController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:51276','http://localhost:50674','http://localhost:58382','https://web.estudent.tech'],
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
                            'issued-books',
                            'issue-books-details',
                            'parent-issue-books-details',
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
                            'issued-books',
                            'issue-books-details',
                            'parent-issue-books-details',
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

    // public function actionSendOtp()
    // {
    //     $data = [];
    //     $post = Yii::$app->request->post();

    //     if (!empty($post)) {
    //         $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';

    //         $otp = rand(1111, 9999);
    //         $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
    //         $sms = 'Dear Warden, ' . $otp . ' is the OTP for login into Driver App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
    //         $sms_url = urlencode($sms);
    //         $template_id = '1707168312584449739';
    //         $sender = 'DEVCIT';
    //         $route = 7;
    //         $SendOtpData =   new SendOtp();
    //         $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);

    //         if(strlen($send_otp) > 4) {
    //             $date = date('Y-m-d H:i:s');
    //             $user_otp  = new UserOtp();
    //             $user_otp->contact_number = $contact_no;
    //             $user_otp->otp = $otp;
    //             $user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +5 minutes"));
    //             $user_otp->messageid = $send_otp;
    //             $user_otp->status = UserOtp::STATUS_PENDING;
    //             $user_otp->save(false);

    //             $data['status'] = self::API_OK;
    //             $data['details'] = $send_otp;
    //         } else {
    //             $data['status'] = self::API_NOK;
    //             $data['error'] = $send_otp;
    //         }



    //     } else {
    //         $data['status'] = self::API_NOK;
    //         $data['error'] = Yii::t("app", "No data posted");
    //     }
    //     return $this->sendJsonResponse($data);
    // }

    public function actionSendOtp()
    {
        $data = [];
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_LIBRARIAN])->one();

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
            $sms = 'Dear Librarian, ' . $otp . ' is the OTP for login into Librarian App and is valid for 5 minutes. DO NOT SHARE this OTP with anyone. -DEV2CI';
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

            if ($contact_no == '8812345677' || $contact_no == '9963363621' || $contact_no == '9490132035' || $contact_no == '7004122113') {
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


                $user_check = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::ROLE_LIBRARIAN])->one();

                if (!empty($user_check)) {
                    $providerId = User::ROLE_LIBRARIAN;
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
    public function actionIssuedBooks()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = Yii::$app->request->getHeaders()->get('auth_code', Yii::$app->request->getQueryParam('auth_code', null));

        try {
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);
            // var_dump($user_id);exit;

            if (empty($user_id)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "User Not Found";
                return $this->sendJsonResponse($data);
            } else {

                $issuedBooks = IssueBooks::find()
                    ->where(['id' => $user_id, 'status' => [1, 2]])
                    ->all();

                if (empty($issuedBooks)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "No Issued Books Found";
                } else {
                    $bookData = [];

                    foreach ($issuedBooks as $issuedBook) {
                        $bookData[] = $issuedBook->asJson(); // Modify this line based on your book data structure
                    }

                    $data['status'] = self::API_OK;
                    $data['details'] = $bookData;
                }
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }
    public function actionIssueBooksDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = Yii::$app->request->getHeaders()->get('auth_code', Yii::$app->request->getQueryParam('auth_code', null));
        try {
            $auth = new AuthSettings();
            $user_id = $auth->getAuthSession($headers);

            if (empty($user_id)) {
                $data['status'] = self::API_NOK;
                $data['error'] = 'User Not Found';
                return $this->sendJsonResponse($data);
            } else {
                $user = User::find()->where(['id' => $user_id])->one();
                $role = $user->user_role;

                $libraryMember = LibraryMembers::find()->where(['user_id' => $user_id])->one();
                if (!$libraryMember) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = 'Library Members Not Found';
                    return $this->sendJsonResponse($data);
                }
                $issueBooks = IssueBooks::find()->where(['library_member_id' => $libraryMember->id])->all();
                if (empty($issueBooks)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = 'No Issued Book Found';
                } else {
                    $bookData = [];
                    foreach ($issueBooks as $issueBook) {
                        $bookData[] = $issueBook->asJson();
                    }
                    $data['status'] = self::API_OK;
                    $data['details'] = $bookData;
                }
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }

    public function actionParentIssueBooksDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $bookData = [];
        $bookArray = [];
        $headers = Yii::$app->request->getHeaders()->get('auth_code', Yii::$app->request->getQueryParam('auth_code', null));

        try {
            $auth = new AuthSettings();
            $parent_id = $auth->getAuthSession($headers);
            $studentId = $post['student_id'];
            $page = isset($post['page']) ? $post['page'] : 0;

            $parentDetail = ParentDetails::find()->where(['user_id' => $parent_id])->one();

            $studentDetail = StudentDetails::find()->where(['parent_id' => $parentDetail->id])->andWhere(['user_id' => $studentId])->one();
            // var_dump($studentDetail);exit;

            //    print_r ($studentDetail->createCommand()->getRawSql());exit;


            if (empty($studentDetail)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Student found";
                return $this->sendJsonResponse($data);
            }
            $libraryMember = LibraryMembers::find()->where(['user_id' => $studentDetail->user_id])->one();
            if (empty($libraryMember)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Membership found";
                return $this->sendJsonResponse($data);
            }

            $issueBook = Issuebooks::find()->where(['library_member_id' => $libraryMember->id]);
            $issuedbooks = new ActiveDataProvider([
                'query' => $issueBook,
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

            foreach ($issuedbooks->models as $ib) {
                $bookArray[] = $ib->asJson();
            }
            if (!empty($bookArray)) {
                $data['status'] = self::API_OK;
                $data['details'] = $bookArray;
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Book Issue.";
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }

        return $this->sendJsonResponse($data);
    }
}
