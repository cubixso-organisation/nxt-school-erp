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
use app\modules\leavemanagement\models\base\DashboardNotification;
use app\modules\leavemanagement\models\base\StaffLeaveApplied;
use app\modules\leavemanagement\models\base\StaffLeaveTypes;
use app\modules\leavemanagement\models\StaffLeaveApplied as ModelsStaffLeaveApplied;
use app\components\Dashboard;
use Exception;

class LeaveManagementController extends BKController
{


    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:*', 'http://localhost:58382', 'http://localhost:50674', 'https://web.estudent.tech'],
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
                            'get-leave-types',
                            'apply-for-leave',
                            'get-leave-history',
                            'edit-leave',
                            'cancel-leave',
                            'leave-detail'

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'get-leave-types',
                            'apply-for-leave',
                            'get-leave-history',
                            'edit-leave',
                            'cancel-leave',
                            'leave-detail'

                        ],
                        'allow' => true,
                        'roles' => ['?', '*']
                    ]
                ]

            ]

        ]);
    }

    public function actionGetLeaveTypes()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        } else {
            try {
                $campus_id = (new User())->getTeacherCampus($user_id);
                $leave_types = StaffLeaveTypes::find()->where(['status' => StaffLeaveTypes::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->all();
                if (empty($leave_types)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Leave Type not found";
                    return $this->sendJsonResponse($data);
                } else {
                    $list = [];
                    foreach ($leave_types as $results) {
                        $list[] = $results->asJson();
                    }

                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'api');
                $data['status'] = self::API_NOK;
                $data['error'] = "An error occurred while processing the request.";
            }
        }
        return $this->sendJsonResponse($data);
    }

    public function actionApplyForLeave()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $user = User::findOne($user_id);
        if (empty($user)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        }

        $post = Yii::$app->request->post();
        $leave_type = $post['leave_type'] ?? "";
        $campus_id = (new User())->getTeacherCampus($user_id);
        $leaveTypes = \app\modules\leavemanagement\models\StaffLeaveTypes::find()->where(['id' => $leave_type])->andWhere(['status' => StaffLeaveTypes::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->one();

        if (empty($leave_type) || empty($leaveTypes)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "Invalid Leave Type";
            return $this->sendJsonResponse($data);
        }

        $no_of_days = $post['no_of_days'] ?? 0;
        $from_date = $post['from_date'] ?? "";
        $to_date = $post['to_date'] ?? "";
        $leave_reason = $post['leave_reason'] ?? "";
        $document_uploaded = $post['document_uploaded'] ?? "";


        try {
            $campus_id = (new User())->getTeacherCampus($user_id);



            $leave_applied = new StaffLeaveApplied();
            $leave_applied->user_id = $user_id;
            $leave_applied->campus_id = (new User())->getTeacherCampus($user_id);
            $leave_applied->leave_type_id = $leave_type;
            $leave_applied->no_of_days = $no_of_days;
            $leave_applied->leave_reason = $leave_reason;
            $leave_applied->from_date = $from_date;
            $leave_applied->to_date = $to_date;
            $leave_applied->user_role = $user->user_role;
            $leave_applied->document_uploaded = $document_uploaded;
            $leave_applied->status = StaffLeaveApplied::STATUS_PENDING;

            if ($leave_applied->save(false)) {
                $title = "Leave Applied Succesfully";
                $message = "You have applied leave. Please wait for the leave approval";


                $sendNoti = Yii::$app->notification->UserNotification('', $user_id, $title, $message, '', 'teacher_leave', $leave_applied->id);

                $title = "New Leave Request";
                $message = $leave_applied->user->first_name ?? "" . " Has applied for a leave.";

                $url = "leave-management/staff-leave-applied?id=" . $leave_applied->id;
                $adminNotificaton = (new Dashboard)->addDashboardNotification($message, $title,  $user_id, $leave_applied->campus_id, $url);




                $data['status'] = self::API_OK;
                $data['message'] = "Leave Applied";
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Failed to save leave application";
            }
        } catch (Exception $e) {
            $data['status'] = self::API_NOK;
            $data['error'] = $e->getMessage();
        }
        return $this->sendJsonResponse($data);
    }

    public function actionGetLeaveHistory()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);
        // $to_date = $post['to_date'] ?? "";
        // $from_date = $post['from_date'] ?? "";

        if (empty($user_id)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        } else {
            try {
                $leave_history = StaffLeaveApplied::find()->where(['user_id' => $user_id])->all();
                if (empty($leave_history)) {
                    $data['status'] = self::API_NOK;
                    $data['error'] = "Leaves not found for the user";
                    return $this->sendJsonResponse($data);
                } else {
                    $list = [];
                    foreach ($leave_history as $results) {
                        $list[] = $results->asJson();
                    }

                    if (!empty($list)) {
                        $data['status'] = self::API_OK;
                        $data['details'] = $list;
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'api');
                $data['status'] = self::API_NOK;
                $data['error'] = "An error occurred while processing the request.";
            }
        }
        return $this->sendJsonResponse($data);
    }

    public function actionEditLeave()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $user = User::findOne($user_id);
        if (empty($user)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        }

        $post = Yii::$app->request->post();
        $leave_id = $post['leave_id'];

        try {
            $leave_applied = StaffLeaveApplied::find()->where(['id' => $leave_id])->andWhere(['user_id' => $user_id])->one();
            if (empty($leave_applied)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "Leave Record not found";
                return $this->sendJsonResponse($data);
            }

            $leave_type = !empty($post['leave_type']) ? $post['leave_type'] :  $leave_applied->leave_type;
            $from_date = !empty($post['from_date']) ? $post['from_date'] :  $leave_applied->from_date;
            $to_date = !empty($post['to_date']) ? $post['to_date'] :  $leave_applied->to_date;
            $leave_reason = !empty($post['leave_reason']) ? $post['leave_reason'] :  $leave_applied->leave_reason;
            $document_uploaded = !empty($post['document_uploaded']) ? $post['document_uploaded'] :  $leave_applied->document_uploaded;
            $campus_id = $leave_applied->campus_id;
            $user_id =   $leave_applied->user_id;
            $status = $leave_applied->status;

            $leave_applied->user_id = $user_id;
            $leave_applied->campus_id = $campus_id;
            $leave_applied->leave_type_id = $leave_type;
            $leave_applied->leave_reason = $leave_reason;
            $leave_applied->from_date = $from_date;
            $leave_applied->to_date = $to_date;
            $leave_applied->document_uploaded = $document_uploaded;
            $leave_applied->status = $status;

            if ($leave_applied->save(false)) {
                $data['status'] = self::API_OK;
                $data['message'] = "Leave Details Updated";
                return $this->sendJsonResponse($data);
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Failed to Update leave application";
                return $this->sendJsonResponse($data);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'api');
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
        }

        return $this->sendJsonResponse($data);
    }

    public function actionCancelLeave()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $user = User::findOne($user_id);
        if (empty($user)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        }

        $post = Yii::$app->request->post();
        $leave_id = $post['leave_id'];

        try {
            $leave_applied = StaffLeaveApplied::find()->where(['id' => $leave_id])->andwhere(['user_id' => $user_id])->one();
            if (empty($leave_applied)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "Leave Record not found";
                return $this->sendJsonResponse($data);
            }
            $leave_applied->status = StaffLeaveApplied::STATUS_CANCELLED_BY_APPLICANT;
            if ($leave_applied->save(false)) {
                $data['status'] = self::API_OK;
                $data['message'] = "Leave Cancelled";
                return $this->sendJsonResponse($data);
            } else {
                $data['status'] = self::API_NOK;
                $data['error'] = "Failed to Cancel leave application";
                return $this->sendJsonResponse($data);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'api');
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
        }

        return $this->sendJsonResponse($data);
    }

    public function actionLeaveDetail()
    {
        $data = [];
        $headers = Yii::$app->request->headers->get('auth_code') ?? Yii::$app->request->getQueryParam('auth_code');
        $auth = new AuthSettings();
        $user_id = $auth->getAuthSession($headers);

        $user = User::findOne($user_id);
        if (empty($user)) {
            $data['status'] = self::API_NOK;
            $data['error'] = "User not found";
            return $this->sendJsonResponse($data);
        }

        $post = Yii::$app->request->post();
        $leave_id = $post['id'];

        try {
            $leave_applied = StaffLeaveApplied::find()->where(['id' => $leave_id])->andwhere(['user_id' => $user_id])->one();
            if (empty($leave_applied)) {
                $data['status'] = self::API_NOK;
                $data['error'] = "Leave Record not found";
                return $this->sendJsonResponse($data);
            }
            $data['status'] = self::API_OK;
            $data['message'] = $leave_applied->asJson();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'api');
            $data['status'] = self::API_NOK;
            $data['error'] = "An error occurred while processing the request.";
        }

        return $this->sendJsonResponse($data);
    }
}
