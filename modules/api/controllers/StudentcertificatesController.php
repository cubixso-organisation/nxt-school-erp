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
use app\modules\documentgenerator\models\base\GeneratedCertificateData;
use app\modules\librarymanagement\models\base\IssueBooks;
use app\modules\librarymanagement\models\LibraryMembers;

class StudentcertificatesController extends BKController
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
                            
                            'generated-certificate-details',
                        ],

                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [

                        'actions' => [
                    
                            'generated-certificate-details',

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



   
    public function actionGeneratedCertificateDetails()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $headers = Yii::$app->request->getHeaders()->get('auth_code', Yii::$app->request->getQueryParam('auth_code', null));

        try {
            $auth = new AuthSettings();
            $parent_id = $auth->getAuthSession($headers);
            $studentId = $post['student_id'];

            $parentDetail = ParentDetails::find()->where(['user_id' => $parent_id])->one();
            $studentDetail = StudentDetails::find()->where(['parent_id' => $parentDetail->id])->andWhere(['id' => $studentId])->one();
            // var_dump($studentDetail);exit;

            if (empty($studentDetail)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Student Found Or Wrong Student Id";
                return $this->sendJsonResponse($data);
            }
            $certificateIssued = GeneratedCertificateData::find()->where(['student_id' => $studentDetail->id])->one();
            if (empty($certificateIssued)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Certificates found or Issued";
                return $this->sendJsonResponse($data);
            }
            $issuedCertificate = GeneratedCertificateData::find()->where(['student_id' => $studentDetail->id])->all();
            if (empty($issuedCertificate)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "No Issued Certificates Found";
            } else {
                $certificates = [];

                foreach ($issuedCertificate as $CertificateStu) {
                    
                    $certificates[] = $CertificateStu->asJson();
                }

                $data['status'] = self::API_OK;
                $data['details'] = $certificates;
            }
        } catch (\Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }
}
