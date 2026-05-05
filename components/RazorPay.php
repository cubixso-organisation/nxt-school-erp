<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\base\Component;
use app\modules\admin\models\Orders;
use app\modules\admin\models\WebSetting;


class RazorPay extends Component
{

    public function header()
    {
        $setting = new WebSetting();
        $razorpay_key_id = $setting->getSettingBykey('razorpay_key_id');
        $razorpay_key_secret = $setting->getSettingBykey('razorpay_key_secret');


        return array(
            'Authorization: Basic ' . base64_encode($razorpay_key_id . ':' . $razorpay_key_secret),
            'content-type: application/json',
        );
    }



    public function CreateOrder($amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "amount": ' . $amount * 100 . ',
            "currency": "INR"
    
   
    }',

            CURLOPT_HTTPHEADER => $this->header(),


        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // Get Payment 

    public function checkPaymentByPayId($payId)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $payId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $this->header(),

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // Capture payment

    public function CapturePayment($amount, $payId)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $payId . '/capture',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "amount": ' . $amount . ',
            "currency": "INR"
        }',
            CURLOPT_HTTPHEADER => $this->header(),

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function generateToken($user)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8080/api/v1/token/generate',
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
    "username" : "admin@estudent.tech"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


    // razorpay route apis


    function createAccount($data)
    {

        $data = [];
        $res = [];

        $data['user_id'] = 1;
        $data['user_role'] = "admin";
        $data['username'] = "admin@estudent.tech";
        $userData = json_encode($data);
        $generateToken = $this->generateToken($userData);
        $decodeResponse = json_decode($generateToken);

        if (!$decodeResponse || $decodeResponse['success'] == false) {

            $res['success']  = false;
            $res['error']  = "unable to generate token" . $decodeResponse['message'];
            return json_encode($res);
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8080/api/v1/account/create-account',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "email": "ww121211.rohitrk@gmail.com",
    "phone": "7986932720",
    "reference_id": "2e32dwe2d2wd2wwd1d",
    "legal_business_name": "Estudent",
    "contact_name": "Rohit",
    "street_one": "Madhapiut t hub 4th floor",
    "street_two": "Madhapiut",
    "city": "Madhapiut",
    "state": "Telangana",
    "postal_code": "500081",
    "pan": "ABCTY1234F",
    "gst":"29GGGGG1314R9Z6",
    "account_number": "315201000001138",
    "ifsc_code": "HDFC0000545",
    "beneficiary_name": "Rohit Kumar",
    "campus_id":55
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $decodeResponse['token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
