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
use app\modules\admin\models\base\Banners;
use app\modules\admin\models\Category;
use app\modules\admin\models\EmployeeDetails;
use Exception;
use yii\web\Response;

class ManagementController extends BKController
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
                            'logout',
                            'get-active-banners'


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
                            'logout',
                            'get-active-banners'


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

        if (!empty($post)) {
            $contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
            $send_otp = Yii::$app->notification->sendOtp($contact_no);
            $send_otp = json_decode($send_otp, true);
            // var_dump($send_otp);exit;
            if ($send_otp['Status'] == 'Success') {
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
            $contact_no = !empty($post['contact_no'])?$post['contact_no']:'';
            $session_code = $post['session_code'];
            $otp_code = $post['otp_code'];

            $send_otp = Yii::$app->notification->verifyOtp($session_code, $otp_code);

            $send_otp = json_decode($send_otp, true);

            if ($send_otp['Status'] == 'Success') {
                $providerId = "Management";

                $number =  $contact_no;
                $auth_id = $contact_no;

                $auth = Auth::find()->where([
                    'source' => $providerId,
                    'source_id' => $auth_id,
                ])->one();

                if ($auth) {
                 
                    $userHasCampusAccess = (new User())->userHasCampusAccess($auth->user_id);
if ($userHasCampusAccess == true) {
    $user = $auth->user;
    $user->device_token = !empty($post['device_token']) ? $post['device_token'] : '';
    $user->device_type = !empty($post['device_type']) ? $post['device_type'] : '';
    Yii::$app->user->login($user);
    $data['status'] = self::API_OK;
    $data['details'] = $user;
    $data['auth_code'] = AuthSession::newSession($user)->auth_code;
}else {
    $data['status'] = self::API_NOK;
    $data['error'] = 'you don\'t have access contact to admin';
}

                } else {
    $data['status'] = self::API_NOK;
    $data['error'] = Yii::t("app", "You Not Have Account Contact To Admin");



                    // $check = User::find()->where(['contact_no'=>$number])->andWhere(['status'=>User::STATUS_ACTIVE])->andWhere(['user_role'=>User::ROLE_PARENT])->one();
                    // $check = User::find()->where(['contact_no'=>$number])->andWhere(['status'=>User::STATUS_ACTIVE])->andWhere(['user_role'=>User::ROLE_PARENT])->one();
                    // if (empty($check)) {
                    //     $model = new User();
                    //     $model->username = $number.'@management.com';
                    //     $model->contact_no = $number;
                    //     $model->device_token =!empty($post['device_token'])?$post['device_token']:'';
                    //     $model->device_type =  !empty($post['device_type'])?$post['device_type']:'';
                    //     $model->referal_code = $model->GenerateRandString1(6);
                    //     $model->user_role = User::ROLE_MANAGEMENT;

                    //     if ($model->validate()) {
                    //         // $model->roles = array($model->user_role);
                    //         if ($model->save()) {
                    //             $auth = new Auth();
                    //             $auth->user_id = $model->id;
                    //             $auth->source = $providerId;
                    //             $auth->source_id = $auth_id;
                    //             if ($auth->save(false)) {
                    //                 // //Find User

                    //                 $user = $auth->user;
                    //                 $user->device_token = $post['device_token'];
                    //                 $user->device_type = $post['device_type'];
                    //                 Yii::$app->user->login($user);

                    //                 $data['status'] = self::API_OK;
                    //                 $data['details'] = $user;
                    //                 $data['auth_code'] = AuthSession::newSession($user)->auth_code;
                    //             } else {
                    //                 $data['status'] = self::API_NOK;
                    //                 $data['error'] = $auth->getErrors();
                    //             }
                    //         } else {
                    //             $data['status'] = self::API_NOK;
                    //             $data['error'] = $model->getErrors();
                    //         }
                    //     } else {
                    //         $data['status'] = self::API_NOK;
                    //         $data['error'] = $model->getErrors();
                    //     }
                    // } else {
                    //     $data['status'] = self::API_NOK;
                    //     $data['error'] = Yii::t("app", 'This number is already registered with us.Please Contact Support');
                    // }


                }
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = Yii::t("app", "OTP failed");
            }
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = Yii::t("app", "No  Data Posted");
        }
        return $this->sendJsonResponse($data);
    }




    public function actionViewProfile(){
        
        $data = [];
        $post = Yii::$app->request->post();
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $userHasCampus =  (new User())->userHasCampus($user_id);
            if (!empty($userHasCampus)) {
              
                $campus_id = $userHasCampus->campus_id;
                $profile = EmployeeDetails::find()->where(['user_id'=>$user_id])->one();
                if(!empty($profile)){
                    $data['status'] = self::API_OK;
                     $data['details'] = $profile->asJson();

                }else{
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

 

    // Action to get all active banners
    public function actionGetActiveBanners()
    {
        $data = [];
        
        try {
            // Fetch all active banners from the database
            $banners = Banners::find()->where(['status' => Banners::STATUS_ACTIVE])->all();
    
            if (empty($banners)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No active banners found.";
            } else {
                $bannerData = [];
                foreach ($banners as $banner) {
                    // Assuming you have an `asJson()` method to format banner data
                    $bannerData[] = $banner->asJson();
                }
    
                // Return the list of active banners
                $data['status'] = self::API_OK;
                $data['banners'] = $bannerData;
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
    
        return $this->sendJsonResponse($data);
    }
    


}
