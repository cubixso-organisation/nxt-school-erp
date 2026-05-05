<?php

namespace app\components;

use app\models\User;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\WebSetting;
use Yii;
use yii\base\Component;

class BrevoEmail extends Component{
    


public static function sendEmail($user_id,$to_email,$name,$subject,$htmlContent){
 

    $campus_id = User::getCampusesByUser($user_id);
    $InstitutesId = Institutes::getInstituteIdOfUser($user_id);
    if(!empty($campus_id)){
        $campus = Campus::find()->where(['id'=>$campus_id])->one();
        $sender_name = !empty($campus->name_of_the_educational_Institution)?$campus->name_of_the_educational_Institution:'Default Sender';
    }else if(!empty($InstitutesId)){
        $institutes = Institutes::find()->where(['id'=>$InstitutesId])->one();
        $sender_name = !empty($institutes->name_of_the_educational_Institution)?$institutes->name_of_the_educational_Institution:'Default Sender';
    }else{
        $sender_name ='Admin';
    }
     

    $setting = new WebSetting();
    $sender_email = $setting->getSettingBykey('sender_email');
    $brevo_email_api_key = $setting->getSettingBykey('brevo_email_api_key');
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.brevo.com/v3/smtp/email',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{  
       "sender":{  
          "name":"'.$sender_name.'",
          "email":"'.$sender_email.'"
       },
       "to":[  
          {  
             "email":"'.$to_email.'",
             "name":"'.$name.'"
          }
       ],
       "subject":"'.$subject.'",
       "htmlContent":"'.$htmlContent.'"
    }',
      CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'api-key:'.$brevo_email_api_key,
        'content-type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return  $response;


}



}