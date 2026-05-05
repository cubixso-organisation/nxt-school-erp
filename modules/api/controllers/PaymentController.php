<?php

namespace app\modules\api\controllers;

use app\components\AccessRule;
use app\components\AuthSettings;
use app\components\RazorPay;
use app\modules\admin\models\AgentStudentJoin;
use app\modules\admin\models\TestUsers;
use app\modules\api\controllers\BKController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use Exception;


class PaymentController extends BKController
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

                    'class' => AccessRule::className(),
                ],

                'rules' => [
                    [
                        'actions' => [
                            'create-payment',
                            'verify-payment',
                            'create-user',
                            'get-all-users',
                            'get-user-by-id',



                        ],

                        'allow' => true,
                        'roles' => [
                            '@',
                        ],
                    ],
                    [

                        'actions' => [

                    
                            'create-payment',
                            'verify-payment',
                            'create-user',
                            'get-all-users',
                            'get-user-by-id',


                        ],

                        'allow' => true,
                        'roles' => [

                            '?',
                            '*',
                            '@',
                        ],
                    ],
                ],
            ],

        ]);
    }


    public function actionCreatePayment($amount='',$sid='')
    {
        $data = [];
        $headers = isset(\Yii::$app->request->headers['auth_code']) ? \Yii::$app->request->headers['auth_code'] : Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        if (!empty($user_id)) {
            $orderAmount = $amount;
            if(!empty($amount) && !empty($sid)){
                $raorPay = new RazorPay();
                $createOrder = $raorPay->CreateOrder($orderAmount);
                if (!empty($createOrder)) {
                    $createOrd = json_decode($createOrder);
                    if(!empty($createOrd)){
                        $agent_student_join = AgentStudentJoin::find()->where(['student_id'=>$sid])->one();
                        $agent_student_join ->amount = $amount;
                        $agent_student_join ->razorpay_order_id = $createOrd->id;
                        $agent_student_join ->save(false);
                        $data['status'] = self::API_OK;
                        $data['details'] = $createOrd;
                    }else{
                        $data['status'] = self::API_NOK;
                    $data['error'] = "Orders not created";  
                    }
                   
                } else {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Orders not created";
                }
            }else{
                 $data['status'] = self::API_NOK;
                $data['error'] = "student id and amount required";
            }
         
          
        } else {
            $data['status'] = self::API_NOK;
            $data['error'] = "User Not Found";
        }
        return $this->sendJsonResponse($data);
    }





    public function actionCreateUser(){

        $data = [];
        $post = Yii::$app->request->post();
            try {
                $patient_id = isset($post['patient_id']) ? $post['patient_id'] : '';
                $title  = isset($post['title ']) ? $post['title'] : '';
                $patient_name = isset($post['patient_name']) ? $post['patient_name'] : '';
                $contact_no = isset($post['contact_no']) ? $post['contact_no'] : '';
                $date_of_birth = isset($post['date_of_birth']) ? $post['date_of_birth'] : '';
                $gender = isset($post['gender']) ? $post['gender'] : '';
                $address = isset($post['address']) ? $post['address'] : '';
                $address = isset($post['address']) ? $post['address'] : '';
                $city = isset($post['city']) ? $post['city'] : '';
                $state = isset($post['state']) ? $post['state'] : '';
                $age = isset($post['age']) ? $post['age'] : '';


                $user = new TestUsers();
                $user->patient_id = $patient_id;
                $user->title = $title;
                $user->patient_name = $patient_name;
                $user->contact_no = $contact_no;
                $user->date_of_birth = $date_of_birth;
                $user->gender = $gender;
                $user->address = $address;
                $user->city = $city;
                $user->state = $state;
                $user->age = $age;


                if($user->save(false)){
                    $data['status'] = self::API_OK;
                    $data['details'] = $user;

                }else{
                    $data['status'] = self::API_NOK;
                    $data['error'] ="data Update failed";
                }


        
   
            } catch(Exception $e) {
                $data['status'] = self::API_NOK;
                $data['error'] =$e->getMessage();
            }
  

        return $this->sendJsonResponse($data);

    }


    public function actionGetAllUsers(){
        $user =  TestUsers::find()->all();
        if(!empty(  $user )){
            $data['status'] = self::API_OK;
            $data['details'] = $user;

        }else{
            $data['status'] = self::API_NOK;
            $data['error'] ="data Not Found";
        }
        return $this->sendJsonResponse($data);

    }


        public function actionGetUserById($id=''){
            if(!empty($id)){
                $user =  TestUsers::find()->where(['id'=>$id])->one();
                if(!empty(  $user )){
                    $data['status'] = self::API_OK;
                    $data['details'] = $user;
        
                }else{
                    $data['status'] = self::API_NOK;
                    $data['error'] ="data Not Found";
                }
            }else{
                $data['status'] = self::API_NOK;
                $data['error'] ="id Not Found";
            }
    
        return $this->sendJsonResponse($data);

    }





}
