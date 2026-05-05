<?php

namespace app\components;

use Google_Client;
use app\models\User;
use Yii;
use yii\base\Component;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FcmNotification;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\StoreLike;
use CURLFile;
use Exception;

class FirebaseNotification extends Component
{

    public function FirebaseApi($id = '', $user_id, $title, $body, $api_key, $device_token, $type = '')
    {

        $token = $this->getGoogleAccessToken();

        $msg = array(
            'body' => strip_tags($body),
            'title' => $title,
            'vibrate' => 1,
            'sound' => 1,
            'order_id' => $id,
            'type' => $type,

        );

        $msg1 = array(
            'body' => strip_tags($body),
            'title' => $title,
            'vibrate' => 1,
            'sound' => 1,
            'order_id' => $id,
            'type' => $type,
        );
        // var_dump($msg1);exit;
        $fields = array(
            'to' => $device_token,
            'collapse_key' => 'type_a',
            'notification' => $msg1,
            'data' => $msg,

        );
        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        // print_r($result);exit;
        curl_close($ch);
        $response = json_decode($result, true);
    }
    public function newFirebaseNotificationApi($id = '', $user_id = '', $title, $message, $customer_notification_key = '', $device_token)
    {

        $curl = curl_init();
        $token = $this->getGoogleAccessToken();
        $project_id = 'nxtschoolparent-a603c'; // Replace with your actual project ID

        // Ensure $device_token is an array or an object implementing Countable

        // if (!is_array($device_token) && !($device_token instanceof Countable)) {
        //     // Handle the situation where $device_token is not as expected
        //     return 'Error: $device_token is not an array or object implementing Countable.';
        // }

        if (!empty($device_token)) {

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/$project_id/messages:send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array(
                    "message" => array(
                        "token" => (string)$device_token,
                        "notification" => array(
                            "title" => (string)$title,
                            "body" => (string)$message,
                        ),
                    ),
                )),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
    }







    //Send Notification to User
    // public function UserNotification($id = '', $user_id, $title, $body, $type = '', $notificationType = '', $notificationTypeId = '')
    // {
    //     $setting = new WebSetting();
    //     $customer_notification_key = $setting->getSettingBykey('user_notification_key');
    //     $auth_sess = new AuthSession();
    //     $device_token = $auth_sess->getDeviceToken($user_id);
    //     $title = $title;
    //     $body = $body;
    //     $fcm_notifications = new FcmNotification();
    //     Yii::$app->notification->FirebaseApi($id, $user_id, $title, $body, $customer_notification_key, $device_token, $type);
    //     if (empty($type)) {
    //         $fcm_notifications->user_id  = $user_id;
    //         $fcm_notifications->title  = $title;
    //         $fcm_notifications->description  = $body;
    //         $fcm_notifications->notification_type  = isset($notificationType) ? $notificationType : Null;
    //         $fcm_notifications->notification_type_id  = isset($notificationTypeId) ? $notificationTypeId : Null;
    //         $fcm_notifications->status  = FcmNotification::STATUS_ACTIVE;
    //         $fcm_notifications->save(false);
    //     }
    // }
    public function UserNotification($id = '', $user_id, $title, $body, $type = '', $notificationType = '', $notificationTypeId = '')
    {
        $setting = new WebSetting();
        $customer_notification_key = $setting->getSettingBykey('user_notification_key');
        $auth_sess = new AuthSession();
        $device_token = $auth_sess->getDeviceToken($user_id);
        // if (!empty($device_token)) {

        // }

        $title = $title;
        $body = $body;
        $fcm_notifications = new FcmNotification();
        Yii::$app->notification->newFirebaseNotificationApi($id, $user_id, $title, $body, $customer_notification_key, $device_token, $type);
        if (empty($type)) {
            $fcm_notifications->user_id  = $user_id;
            $fcm_notifications->title  = $title;
            $fcm_notifications->description  = $body;
            $fcm_notifications->notification_type  = isset($notificationType) ? $notificationType : Null;
            $fcm_notifications->notification_type_id  = isset($notificationTypeId) ? $notificationTypeId : Null;
            $fcm_notifications->status  = FcmNotification::STATUS_ACTIVE;
            $fcm_notifications->save(false);
        }
    }


    //Vendor notification
    public function vendorNotification($id = '', $user_id, $title, $body)
    {
        $setting = new WebSetting();
        $customer_notification_key = $setting->getSettingBykey('vendor_notification_key');
        $auth_sess = new AuthSession();
        $device_token = $auth_sess->getDeviceToken($user_id);
        $title = $title;
        $body = $body;

        Yii::$app->notification->FirebaseApi($id, $user_id, $title, $body, $customer_notification_key, $device_token);
    }

    //Driver notification 
    public function driverNotification($id = '', $user_id, $title, $body, $type = '')
    {


        $setting = new WebSetting();
        $customer_notification_key = $setting->getSettingBykey('driver_notification_key');
        $auth_sess = new AuthSession();
        $device_token = $auth_sess->getDeviceToken($user_id);
        $title = $title;
        $body = $body;

        Yii::$app->notification->FirebaseApi($id, $user_id, $title, $body, $customer_notification_key, $device_token, $type);
    }

    //Send SMS
    public function sendSMS($contact_no, $msg)
    {

        $setting = new WebSetting();
        $sms_api_key = $setting->getSettingBykey('sms_api_key');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://2factor.in/API/V1/$sms_api_key/ADDON_SERVICES/SEND/TSMS",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('From' => 'DMAAIA', 'To' => '91' . $contact_no, 'Msg' => $msg),

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;
    }



    public function sendSMSDynamicTemplate($contact_no, $msg)
    {

        $setting = new WebSetting();
        $sms_api_key = $setting->getSettingBykey('sms_api_key');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://2factor.in/API/R1/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'module=TRANS_SMS&apikey=' . $sms_api_key . '&to=' . $contact_no . '&from=BVKGRP&msg=' . $msg,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;







        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://2factor.in/API/V1/65ad82fe-a44b-11ed-813b-0200cd936042/ADDON_SERVICES/SEND/TSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('From' => 'BVKGRP', 'To' => '6300565084', 'TemplateName' => 'Counter Pay Payment', 'VAR1' => '100', 'VAR2' => 'sai'),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }




    public function sendSMSDynamicTemplateV2($contact_no, $TemplateName, $varArray)
    {
        $setting = new WebSetting();
        $sms_api_key = $setting->getSettingBykey('sms_api_key');
        $curl = curl_init();
        $smaData = array(
            'From' => 'BVKGRP',
            'To' => $contact_no,
            'TemplateName' => $TemplateName,
        );
        $two_arr = array_merge($smaData, $varArray);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://2factor.in/API/V1/' . $sms_api_key . '/ADDON_SERVICES/SEND/TSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $two_arr
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }




    //Send OTP
    public function sendOtp($contact_no)
    {
        $setting = new WebSetting();
        $sms_api_key = $setting->getSettingBykey('sms_api_key');
        // var_dump(''. $sms_api_key);exit;
        $curl = curl_init();
        $otp = rand(1000, 9999);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://2factor.in/API/V1/$sms_api_key/SMS/91$contact_no/$otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cookie: __cfduid=d3873a75f3e6843a5117359bd027d9c7a1588843417",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    //Verify OTP
    public function verifyOtp($session_code, $otp_code)
    {
        $setting = new WebSetting();
        $sms_api_key = $setting->getSettingBykey('sms_api_key');
        $curl = curl_init();
        //  var_dump($sms_api_key); exit;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://2factor.in/API/V1/$sms_api_key/SMS/VERIFY/$session_code/$otp_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cookie: __cfduid=d3873a75f3e6843a5117359bd027d9c7a1588843417",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }




    public function imageKitUpload($image, $folder = '')
    {

        $setting = new WebSetting();

        $public_key = $setting->getSettingBykey('public_key_image_kit');
        $private_key = $setting->getSettingBykey('private_key_image_kit');

        $Campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        if (User::isCampusSubAdmin()) {
            $institutes = Institutes::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
            $name_of_the_educational_Institution = $institutes->name_of_the_educational_Institution;
        } else {
            $name_of_the_educational_Institution = '';
        }
        $Campus = !empty(Campus::getCampusName()) ? Campus::getCampusName() : $name_of_the_educational_Institution;
        $Campus = substr($Campus, 0, 5);
        if (!empty($image->tempName)) {

            $curl = curl_init();

            //                     $folder = preg_replace('/\s+/', '', !empty($Campus) ? $Campus : 'general images') . '/' . $folder;
            // var_dump($folder);exit;

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://upload.imagekit.io/api/v1/files/upload',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'file' => new CURLFile($image->tempName),
                    'fileName' => rand(000000, 999999999999),
                    'folder' => preg_replace('/\s+/', '', !empty($Campus) ? $Campus : 'general images') . '/' . $folder
                ),
                CURLOPT_HTTPHEADER => array(
                    // 'Authorization: Basic cHJpdmF0ZV9NTTFlRzFZNXFRbkVjbWQwZW5FUFpXS2lvSHM9Og=='
                    "Authorization: Basic " . base64_encode($private_key . ":" . $public_key)

                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);

            return json_decode($response, true);
        }
    }

    public function withoutLoginImagekit($image, $folder = '')
    {

        $setting = new WebSetting();

        $public_key = $setting->getSettingBykey('public_key_image_kit');
        $private_key = $setting->getSettingBykey('private_key_image_kit');


        if (!empty($image->tempName)) {

            $curl = curl_init();




            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://upload.imagekit.io/api/v1/files/upload',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'file' => new CURLFile($image->tempName),
                    'fileName' => rand(000000, 999999999999),
                    'folder' => preg_replace('/\s+/', '', !empty($Campus) ? $Campus : 'general images') . '/' . $folder
                ),
                CURLOPT_HTTPHEADER => array(
                    // 'Authorization: Basic cHJpdmF0ZV9NTTFlRzFZNXFRbkVjbWQwZW5FUFpXS2lvSHM9Og=='
                    "Authorization: Basic " . base64_encode($private_key . ":" . $public_key)

                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);

            return json_decode($response, true);
        }
    }




    public function sendSms91()
    {
        //Your authentication key
        $authKey = "343067AdIm5a648QHW5f72f7ddP1";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = "6300565084";

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "BVKGRP";

        //Your message to send, Add URL encoding here.
        $message = urlencode("Test message");

        //Define route 
        $route = "default";
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url = "http://api.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        echo $output;
    }


    private function getGoogleAccessToken()
    {
        // Path to your service account JSON file
        $credentialsFilePath = 'nxtschool.json'; // Replace with your actual path and file name

        // Check if file exists
        if (!file_exists($credentialsFilePath)) {
            throw new Exception('Service account file not found: ' . $credentialsFilePath);
        }

        // Read the service account JSON file
        $jsonKey = json_decode(file_get_contents($credentialsFilePath), true);

        if (!$jsonKey) {
            throw new Exception('Invalid JSON in service account file');
        }

        // Fix the private key format - replace literal \n with actual newlines
        $privateKey = str_replace('\\n', "\n", $jsonKey['private_key']);
        $clientEmail = $jsonKey['client_email'];
        $tokenUri = $jsonKey['token_uri'];

        // Validate that we have the required fields
        if (empty($privateKey) || empty($clientEmail) || empty($tokenUri)) {
            throw new Exception('Missing required fields in service account JSON');
        }

        // Test if the private key is valid
        $testKey = openssl_pkey_get_private($privateKey);
        if ($testKey === false) {
            throw new Exception('Invalid private key format: ' . openssl_error_string());
        }

        // Define the JWT header and payload
        $jwtHeader = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $now = time();
        $jwtPayload = [
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $tokenUri,
            'exp' => $now + 3600,
            'iat' => $now
        ];

        // Encode the header and payload as base64url
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($jwtHeader)));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($jwtPayload)));

        // Create the signature hash
        $signature = '';
        $data = $base64UrlHeader . '.' . $base64UrlPayload;

        // Sign the data with the private key - use OPENSSL_ALGO_SHA256 constant
        if (!openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception('Failed to sign JWT: ' . openssl_error_string());
        }

        // Encode the signature as base64url
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Concatenate the header, payload, and signature to form the JWT
        $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

        // Request an access token using the JWT
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('HTTP error ' . $httpCode . ': ' . $response);
        }

        $tokenData = json_decode($response, true);

        if (isset($tokenData['access_token'])) {
            return $tokenData['access_token'];
        } else {
            throw new Exception('Failed to obtain access token: ' . $response);
        }
    }

    function generateToken()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://localhost:8080/api/v1/token/generate',
            CURLOPT_URL => 'https://api.nxtschools.com/api/v1/token/generate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "user_id" : 1,
    "user_role" : "admin",
    "username" : "nxtschool@domain.com"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        // var_dump($response);
        // exit;
        curl_close($curl);
        return $response;
    }

    public function generateMarksheetPdf($finalStructure)
    {
        // Initialize cURL session

        $generateToken = $this->generateToken();
        $decodeTokenRes = json_decode($generateToken);

        if ($decodeTokenRes->success == false) {
            if (empty($response)) {
                throw new Exception("Error: Token Not Generated");
            }
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nxtschools.com/api/v1/document-generator/generatePdf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $finalStructure,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . (string)$decodeTokenRes->token,
                'Content-Type: application/json'
            ),
        ));

        // Execute cURL request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception("cURL error: $error_msg");
        }

        // Get HTTP response status code
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // // Check for HTTP error responses
        if ($httpStatusCode != 200) {
            throw new Exception("HTTP error: Received status code $httpStatusCode");
        }
        // var_dump($response);
        // exit;
        // Check if the response is empty
        if (empty($response)) {
            throw new Exception("Error: Empty response received from the API.");
        }

        // Set appropriate headers for PDF download
        return \Yii::$app->response->sendContentAsFile($response, 'report.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => false, // Set to false to force download
        ]);

        return $response;
    }



    // Silver crest marksheet

    public function silverCrestMarksheet($finalStructure)
    {
        // Initialize cURL session

        // $generateToken = $this->generateToken();
        // $decodeTokenRes = json_decode($generateToken);

        // if ($decodeTokenRes->success == false) {
        //     if (empty($response)) {
        //         throw new Exception("Error: Token Not Generated");
        //     }
        // }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nxtschools.com/api/v1/document-generator/generate-marksheet-silvercrest',
            // CURLOPT_URL => 'http://localhost:8080/api/v1/document-generator/generate-marksheet-silvercrest',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $finalStructure,
            CURLOPT_HTTPHEADER => array(
                // 'Authorization: Bearer ' . (string)$decodeTokenRes->token,
                'Content-Type: application/json'
            ),
        ));

        // Execute cURL request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception("cURL error: $error_msg");
        }

        // Get HTTP response status code
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // // Check for HTTP error responses
        if ($httpStatusCode != 200) {
            throw new Exception("HTTP error: Received status code $httpStatusCode");
        }
        // var_dump($response);
        // exit;
        // Check if the response is empty
        if (empty($response)) {
            throw new Exception("Error: Empty response received from the API.");
        }

        // Set appropriate headers for PDF download
        return \Yii::$app->response->sendContentAsFile($response, 'report.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => false, // Set to false to force download
        ]);

        return $response;
    }




    // File: FirebaseNotification.php

    public function generateFinalMarksheetPdf($finalStructure)
    {
        // // Initialize cURL session
        // $generateToken = $this->generateToken();

        // $decodeTokenRes = json_decode($generateToken);
        // var_dump($generateToken);exit;

        // if ($decodeTokenRes->success == false) {
        //     throw new Exception("Error: Token Not Generated");
        // }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://localhost:8080/api/v1/document-generator/finalPdf',
            CURLOPT_URL => 'https://api.nxtschools.tech/api/v1/document-generator/finalPdf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $finalStructure,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJ1c2VyX3JvbGUiOiJhZG1pbiIsInVzZXJuYW1lIjoiYWRtaW5AZXN0dWRlbnQudGVjaCIsImlhdCI6MTcyNTk1MjIzNH0.2-BxkPZRe7beKSqqqAncj4s3qnnle6A4RGAgxvrJJcE',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception("cURL error: $error_msg");
        }

        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpStatusCode != 200) {
            throw new Exception("HTTP error: Received status code $httpStatusCode");
        }

        if (empty($response)) {
            throw new Exception("Error: Empty response received from the API.");
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="final_marksheet.pdf"');
        header('Content-Length: ' . strlen($response));

        return $response;
    }
}
