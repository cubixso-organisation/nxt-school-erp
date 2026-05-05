<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\PaymentDetails;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\FeesTyps;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\search\FeeStructuresSearch;
use app\modules\admin\models\search\PayFeesSearch;
use app\modules\admin\models\search\StudentDetailsSearch;
use app\modules\admin\models\search\PaymentDetailsSearch;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentDetails;
use DateTime;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use Exception;
use app\components\SendOtp;
use app\modules\admin\models\base\StudentDetails as BaseStudentDetails;
use app\modules\admin\models\UserOtp;
use app\modules\admin\models\WebSetting;

/**
 * PayFeesController implements the CRUD actions for PayFees model.
 */
class PayFeesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'student-details-get-by-ft-id',
                            'assign-fee-all-student',
                            'assign-fee-details',
                            'verify-otp',
                            'pay-now',
                            'pay-fees-view-history',
                            'remove-fee',
                            'get-fee-structure-data',
                            'pay-old-fee',
                            'search-students-by-name',
                            'fetch-student-details',
                            'get-student-details'

                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'student-details-get-by-ft-id',
                            'assign-fee-all-student',
                            'assign-fee-details',
                            'assign-fee-details-delete-conform',
                            'assign-fee-details-delete-data',
                            'verify-otp',
                            'pay-now',
                            'pay-fees-view-history',
                            'remove-fee',
                            'get-fee-structure-data',
                            'pay-old-fee',
                            'search-students-by-name',
                            'fetch-student-details',
                            'get-student-details'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }
    // $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams,User::getCampusesByUser(Yii::$app->user->identity->id));


    /**
     * Lists all PayFees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayFeesSearch();
        $FeeStructuresSearchSearchModel = new FeeStructuresSearch();
        $studentDetailsSearch = new  StudentDetailsSearch();
        $get = Yii::$app->request->get();
        $feeStructuresModal = new FeeStructures();

        $student_class_id = isset($get['FeeStructuresSearch']['student_class_id']) ? $get['FeeStructuresSearch']['student_class_id'] : '';
        $class_section_id = isset($get['FeeStructuresSearch']['class_section_id']) ? $get['FeeStructuresSearch']['class_section_id'] : '';
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams);
            $dataProviderStudentDetails = $studentDetailsSearch->StudentSearchBySidCid(Yii::$app->request->queryParams, $student_class_id, $class_section_id);
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
            $dataProviderStudentDetails = $studentDetailsSearch->StudentSearchBySidCid(Yii::$app->request->queryParams, $student_class_id, $class_section_id);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'FeeStructuresSearchSearchModel' => $FeeStructuresSearchSearchModel,
            'dataProviderStudentDetails' => $dataProviderStudentDetails,
            'studentDetailsSearch' => $studentDetailsSearch,
            'feeStructuresModal' => $feeStructuresModal


        ]);
    }

    /**
     * Displays a single PayFees model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PayFees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PayFees();

        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            $student_id = $post['PayFees']['student_id'];
            $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $fee_structures_id = $post['PayFees']['fee_structures_id'];
            $fees_cut = $post['PayFees']['fees_cut'];
            $status = $post['PayFees']['status'];
            foreach ($student_id as $student_id_data) {
                $model = new  PayFees();
                $model->campus_id  = $campus_id;
                $model->student_id   = $student_id_data;
                $model->fee_structures_id   = $fee_structures_id;
                $model->fees_cut   = $fees_cut;
                $model->status   = $status;
                $model->save(false);
                //update reference id
                $pay_fees = PayFees::find()->where(['id' => $model->id])->one();
                $pay_fees->reference_number = $campus_id . $student_id_data . $fee_structures_id . $model->id;
                $pay_fees->save(false);
            }

            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PayFees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->loadAll(Yii::$app->request->post())) {
            if ($model->save(false)) {
                Yii::$app->session->remove('verify_otp');
                Yii::$app->session->remove('pay_fees_id');
                Yii::$app->session->remove('fee_deduction');
            }
            $re_direct_url =    '/admin/pay-fees/assign-fee-details?' . Yii::$app->session->get('redirect_url');


            return $this->redirect([$re_direct_url]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PayFees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = PayFees::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['assign-fee-details']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = PayFees::find()->where(['id' => $post['id']])->one();
            if (!empty($model)) {
                $model->status = $post['val'];
            }
            if ($model->save(false)) {
                $data['message'] = "Updated";
                $data['id'] = $model->status;
            } else {
                $data['message'] = "Not Updated";
            }
        }
        return $data;
    }


    /**
     * Finds the PayFees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PayFees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayFees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for PaymentDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddPaymentDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('PaymentDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formPaymentDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }



    public function actionStudentDetailsGetByFtId()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $fee_structures = $parents[0];
                $out = (new StudentDetails())->getStudentDataByFeeStructureId($fee_structures);
                return $out;
            }
        }

        return $out;
    }


    public function actionAssignFeeAllStudent()
    {
        $post = Yii::$app->request->post();
        // try {
        $student_id = !empty($post['selection']) ? $post['selection'] : '';
        $fee_structures_id =  !empty($post['fee_structure_id']) ? $post['fee_structure_id'] : '';
        if (!empty($student_id) && !empty($fee_structures_id)) {




            foreach ($student_id as $student_id_data) {

                $fee_structures = FeeStructures::find()->where(['id' => $fee_structures_id])->one();
                $fee_type_id   = $fee_structures->fee_type_id;
                $fees_typs = FeesTyps::find()->where(['id' => $fee_type_id])->one();
                $months =  !empty($fees_typs->months) ? $fees_typs->months : 12;


                //get student details
                $student_details = StudentDetails::find()->where(['id' => $student_id_data])->one();

                if (!empty($student_details)) {
                    $student_class_id = $student_details->student_class_id;
                    $section_id = $student_details->section_id;
                    //check PayFees with student exist or not
                    $checkPayFees = PayFees::find()->where(['student_id' => $student_id_data])->andWhere(['fee_structures_id' => $fee_structures_id])->one();
                    $date = new DateTime('now');
                    $date->modify('+' . $months . ' month');
                    $due_date = $date->format('Y-m-d');
                    if (!empty($checkPayFees)) {
                        $pay_fees =  PayFees::find()->where(['id' => $checkPayFees->id])->one();
                        $reference_number = $checkPayFees->reference_number;
                        $pay_fees->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $pay_fees->student_id  = $student_id_data;
                        $pay_fees->fee_structures_id  = $fee_structures_id;
                        $pay_fees->academic_year_id   = $student_details->academic_year_id;
                        $pay_fees->fees_cut  = 0;
                        $pay_fees->status  = PayFees::STATUS_ACTIVE;
                        $pay_fees->save(false);
                        $balance =  ParentDetails::getBalanceAmount($student_id, $student_class_id, $section_id, $pay_fees->id);
                        $pay_fees->balance_fee  = $balance;
                        if (empty($pay_fees->due_date)) {
                            $pay_fees->due_date  = $due_date;
                        }
                        $pay_fees->save(false);
                    } else {

                        $pay_fees = new PayFees();
                        $pay_fees->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $pay_fees->student_id  = $student_id_data;
                        $pay_fees->reference_number = empty($reference_number) ? $student_id_data . rand(11111111, 99999999) : $reference_number;
                        $pay_fees->fee_structures_id  = $fee_structures_id;
                        $pay_fees->academic_year_id   = $student_details->academic_year_id;
                        $pay_fees->fees_cut  = 0;
                        $pay_fees->status  = PayFees::STATUS_ACTIVE;
                        $pay_fees->save(false);
                        $balance =  ParentDetails::getBalanceAmount($student_id, $student_class_id, $section_id, $pay_fees->id);
                        $pay_fees->balance_fee  = $balance;
                        $pay_fees->due_date  = $due_date;
                        $pay_fees->save(false);
                    }
                }
            }
            return json_encode(array('status' => 'ok'));
        } else {
            return json_encode(array('status' => 'nok'));
        }
        // } catch (Exception $e) {
        //     return json_encode(array('status' => 'nok', 'error' => $e->getMessage()));
        // }
    }

    public function actionAssignFeeDetails()
    {
        // Debug the logged-in user
        if (Yii::$app->user->isGuest) {
            return 'No user is logged in';
        }

        // Get logged-in user's identity
        $user = Yii::$app->user->identity;


        // var_dump($user);
        // exit;

        // Existing code
        $student_id = '';
        $model = new PayFees();
        $searchModel = new PayFeesSearch();
        $PaymentDetailsModel = new PaymentDetails();
        if (isset($_GET['PayFeesSearch'])) {
            $student_id = $_GET['PayFeesSearch']['student_id'];
        }

        // Continue with your existing logic...
        $PaymentDetailsSearch = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->campusSearch(
                Yii::$app->request->queryParams,
                $student_id,
                '',
                (new Campus())->getCurrentSession(
                    (new Campus())->getCampusId() ? (new Campus())->getCampusId() : (new User())->getCampusesByUser(\Yii::$app->user->identity->id)
                )
            );
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->campusSubAdminSearchSearch(Yii::$app->request->queryParams, $student_id);
        }

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $query = $dataProvider->query;
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(
                Yii::$app->request->queryParams,
                '',
                (new Campus())->getCurrentSession((new Campus())->getCampusId())
            );
            $query = $dataProvider->query;
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
            $query = $dataProvider->query;
        }
        $balanceAmount = PayFees::find()->where(['student_id' => $student_id])->andWhere(['academic_year_id' => (new Campus())->getCurrentSession((new Campus())->getCampusId())])->sum('balance_fee');
        $studentDetails = StudentDetails::find()->where(['id' => $student_id])->one();

        return $this->render('assign_fee_details', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProviderPaymentDetailsSearch' => $dataProviderPaymentDetailsSearch,
            'PaymentDetailsModel' => $PaymentDetailsModel,
            'PaymentDetailsSearch' => $PaymentDetailsSearch,
            'balanceAmount' => $balanceAmount,
            'studentDetails' => $studentDetails
        ]);
    }


    public function actionAssignFeeDetailsDeleteConform()
    {
        $post = Yii::$app->request->post();
        $campusId = $post['campusId'];

        $get_key_member = user::find()->where(['campus_id' => $campusId])
            ->andWhere(['user_role' => User::role_key_person])
            ->andWhere(['status' => user::STATUS_ACTIVE])->one();
        // var_dump($get_key_member);exit;
        if (!empty($get_key_member->contact_no)) {
            $keyNumber = $get_key_member->contact_no;
        } else {
            $keyNumber = "";
        }
        $user = new User();
        $campusId = $user->getCampusId();
        $campusId = $campusId !== null ? $campusId : $user->getCampusesByUser(\Yii::$app->user->identity->id);
        if ($campusId == 77) {
            $keyNumber = 8084167697;
        }

        if (!empty($keyNumber)) {
            $contact_no = $keyNumber;
            $otp = rand(1111, 9999);
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
            $route = 7;

            // $template_id = '1707168312544700319';
            $sender = $senderId;
            $SendOtpData =  new SendOtp();
            $send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);
            // var_dump($get_key_member);exit;

            if (strlen($send_otp) > 4) {
                $date = date('Y-m-d H:i:s');
                $user_otp  = new UserOtp();
                $user_otp->contact_number = $contact_no;
                $user_otp->otp = $otp;
                $user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +5 minutes"));
                $user_otp->messageid = $send_otp;
                $user_otp->status = UserOtp::STATUS_PENDING;
                $user_otp->save(false);

                return  json_encode(array('status' => 'ok', 'message' => $send_otp));
            } else {
                $msg = 'Otp Not Sent';
                return  json_encode(array('status' => 'error', 'message' => $msg));
            }
        } else {
            $msg = 'You don\'t have Key Members contact to admin and retry!';
            return  json_encode(array('status' => 'error', 'message' => $msg));
        }
    }



    public function actionAssignFeeDetailsDeleteData()
    {
        $post = Yii::$app->request->post();

        $pay_fees_otp = $post['pay_fees_otp'];
        $payfeesDeleteDataId = $post['payfeesDeleteDataId'];
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $get_key_member = user::find()->where(['campus_id' => $campusId])
            ->andWhere(['user_role' => User::role_key_person])
            ->andWhere(['status' => user::STATUS_ACTIVE])->one();
        $contact_no = $get_key_member->contact_no;
        $user_otp = UserOtp::find()->where(['contact_number' => $contact_no])->andWhere(['otp' => $pay_fees_otp])->one();
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

        if ($otp_match  === true) {

            $pay_fees = PayFees::find()->where(['id' => $payfeesDeleteDataId])->one();
            if (!empty($pay_fees)) {
                $pay_fees->status = PayFees::STATUS_DELETE;
                if ($pay_fees->save(false)) {
                    return  json_encode(array('status' => 'ok', 'message' => 'done'));
                } else {
                    $msg = 'Data Detailed Failed!';
                    return  json_encode(array('status' => 'error', 'message' => $msg));
                }
            } else {
                $msg = 'Data Not Found!';
                return  json_encode(array('status' => 'error', 'message' => $msg));
            }
        } else {
            $msg = 'Otp Validation Failed!';
            return  json_encode(array('status' => 'error', 'message' => $msg));
        }
    }




    public function actionVerifyOtp($id = '')
    {
        $post = Yii::$app->request->post();
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        $pay_fees_otp = $post['pay_fees_otp'];
        $payfeesDeleteDataId = $post['payfeesDeleteDataId'];
        $sessionKey = $post['sessionKey'];
        $fee_deduction = $post['fee_deduction'];
        $remarks_of_pay_fee = !empty($post['remarks_of_pay_fee']) ? $post['remarks_of_pay_fee'] : '';
        $get_key_member = user::find()->where(['campus_id' => $campusId])
            ->andWhere(['user_role' => User::role_key_person])
            ->andWhere(['status' => user::STATUS_ACTIVE])->one();


        $user = new User();
        $campusId = $user->getCampusId();
        $campusId = $campusId !== null ? $campusId : $user->getCampusesByUser(\Yii::$app->user->identity->id);
        if (!empty($get_key_member)) {
            $contact_no = $get_key_member->contact_no;
        } else {
            $contact_no = "";
        }

        if ($campusId == 77) {
            $contact_no = 8084170284;
            $otp_match  = true;
        } else {
            $user_otp = UserOtp::find()->where(['contact_number' => $contact_no])->andwhere(['otp' => $pay_fees_otp])->one();

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
            Yii::$app->session->set('pay_fees_id', $id);
            Yii::$app->session->set('verify_otp', 1);
            Yii::$app->session->set('fee_deduction', $fee_deduction);
            if (!empty($remarks_of_pay_fee)) {
                Yii::$app->session->set('remarks_of_pay_fee', $remarks_of_pay_fee);
            }
            return json_encode(array('status' => 'ok', 'message' => 'done'));
        } else {
            $msg = 'Otp Validation Failed!';
            return json_encode(array('status' => 'error', 'message' => $msg));
        }
    }

    public function actionPayNow()
    {
        $data = [];
        $post = Yii::$app->request->post();

        $pay_fees_id = !empty($post['pay_fees_id']) ? $post['pay_fees_id'] : '';
        $remarks = !empty($post['remarks']) ? $post['remarks'] : '';
        $payment_mode = !empty($post['paymentMode']) ? $post['paymentMode'] : '';
        $orderAmount = !empty($post['amount']) ? $post['amount'] : 0;
        $payment_date = !empty($post['payment_date']) ? $post['payment_date'] : date('Y-m-d H:i:s');

        if (!empty($pay_fees_id)) {
            $pay_fees = PayFees::find()->where(['id' => $pay_fees_id])->one();
            if (!empty($pay_fees)) {
                $student_id = $pay_fees->student_id;
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                $student_class_id = $student_details->student_class_id;
                $section_id = $student_details->section_id;
                $fee_structures_id  = $pay_fees->fee_structures_id;
                $fee_structures = FeeStructures::find()->where(['id' => $fee_structures_id])->one();
                $fee = $fee_structures->fee;
                $fees_cut = $pay_fees->fees_cut;
                $firstPayAmount = $fee - $fees_cut;
                //check amount paid or not
                $payment_details = PaymentDetails::find()
                    ->select("payment_details.paid_amount, payment_details.balance_amount,payment_details.pay_fees_id")
                    ->joinWith(['payFees'])
                    ->where(['payment_details.student_id' => $student_id])
                    ->andWhere(['payment_details.class_id' => $student_class_id])
                    ->andWhere(['payment_details.section_id' => $section_id])
                    ->andWhere(['payment_details.pay_fees_id' => $pay_fees_id])
                    ->andWhere(['payment_details.status' => PaymentDetails::status_success])
                    ->sum('payment_details.paid_amount');
                $pay_amount = $firstPayAmount - $payment_details;

                if (!empty($orderAmount) && !empty($payment_mode)) {


                    if ($orderAmount > 0) {
                        if ($orderAmount <= $pay_amount && $pay_amount > 0) {
                            $paid_reference_number = rand(11111111, 99999999);
                            $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                            $student_class_id  = $student_details->student_class_id;
                            $section_id   = $student_details->section_id;
                            $getCampusByStudentId = (new Campus())->getCampusByStudentId($student_id);
                            $parent_number = StudentDetails::getPatentNumberByStudentId($student_id);
                            $payment_details = new PaymentDetails();
                            $payment_details->campus_id  = $getCampusByStudentId;
                            $payment_details->student_id   = $student_id;
                            $payment_details->class_id    = $student_class_id;
                            $payment_details->section_id    = $section_id;
                            $payment_details->pay_fees_id   = $pay_fees_id;
                            $payment_details->fee_receipt   = '';
                            $payment_details->fee_collected_by   = Yii::$app->user->identity->id;
                            $payment_details->paid_reference_number = $paid_reference_number;
                            $payment_details->status = PaymentDetails::status_success;
                            $payment_details->paid_amount   = $orderAmount;
                            $payment_details->razorpay_order_id = '';
                            $payment_details->razorpay_order_id = '';
                            $payment_details->payment_mode = $payment_mode;
                            $payment_details->remarks = $remarks;


                            if ($payment_details->save(false)) {


                                $pay_fees_update = PayFees::find()->where(['id' => $pay_fees_id])->one();

                                $balance =  ParentDetails::getBalanceAmount($student_id, $student_class_id, $section_id, $pay_fees_id);
                                $pay_fees_update->balance_fee = $balance;

                                $pay_fees_update->save(false);

                                $payment_details->created_on   = $payment_date;
                                $payment_details->save(false);


                                $fee_type = !empty($payment_details->payFees->feeStructures->title) ? $payment_details->payFees->feeStructures->title : 'Fee Type Not Set';
                                $contact_no = $pay_fees_update->student->parent->contact_number ?? "1234567890";
                                $campusName = isset($payment_details->student->campus->name_of_the_educational_Institution)
                                    ? substr($payment_details->student->campus->name_of_the_educational_Institution, 0, 30)
                                    : "";

                                // $sms = " Dear Sir/Madam, We have received your payment of Rs.$payment_details->paid_amount. Your remaining balance is Rs.$balance/-($fee_type). - EStudent";
                                $sms = "Dear Sir/Ma'am We have successfully received your payment of Rs$payment_details->paid_amount/-($fee_type). Best regards, $campusName -EStudent";
                                $sms_url = urlencode($sms);
                                // $template_id = '1007615301263722753';
                                $template_id = '1007289423840380424';
                                $sender = 'ESTDNT';
                                $route = 7;
                                // $SendOtpData = new SendOtp();
                                // $send_otp = $SendOtpData->sendSMS($contact_no, $sms_url, $template_id, $sender, $route);
                                // if (strlen($send_otp) > 4) {
                                // }




                                $data['status'] = 'ok';
                                $data['message'] = 'Payment Updated Successfully';
                            } else {
                                $data['status'] = 'Nok';
                                $data['error'] = "Payment Updated Failed Retry After Some Time";
                            }
                        } else {
                            $data['status'] = 'Nok';
                            $data['error'] = "Pay Amount is " . $pay_amount;
                        }
                    } else {
                        $data['status'] = 'Nok';
                        $data['error'] = "Minimum accepted amount ₹1";
                    }
                } else {
                    $data['status'] = 'Nok';
                    $data['error'] = "student id and and payment mode and amount required";
                }
            } else {
                $data['status'] = 'Nok';
                $data['error'] = "Pay Fee Data Not Found";
            }
        } else {
            $data['status'] = 'Nok';
            $data['error'] = "Pay Fee id Not Found";
        }

        return json_encode($data);
    }



    public function actionPayFeesViewHistory($id, $academic_year_id = '')
    {
        $model = PaymentDetails::find()->where(['id' => $id])->one();
        $student_id  = $model->student_id;
        $student_class_id = $model->student->studentClass->id;
        $student_section_id = $model->student->section->id;

        // var_dump($academic_year_id);
        // exit;

        if (!empty($academic_year_id)) {
            $pay_fees =  $pay_fees = PayFees::find()->joinWith('feeStructures')
                // ->where(['fee_structures.student_class_id' => $student_class_id])
                // ->andWhere(['fee_structures.class_section_id' => $student_section_id])
                ->Where(['pay_fees.student_id' => $student_id])
                ->andWhere(['pay_fees.academic_year_id' => $academic_year_id])
                ->all();
        } else {
            $pay_fees = PayFees::find()->joinWith('feeStructures')
                // ->where(['fee_structures.student_class_id' => $student_class_id])
                // ->andWhere(['fee_structures.class_section_id' => $student_section_id])
                ->andWhere(['pay_fees.student_id' => $student_id])
                ->all();
        }



        // var_dump(
        //     $pay_fees->createCommand()->getRawSql()
        // );
        // exit;
        return $this->render('view', [
            'model' => $model,
            'pay_fees' => $pay_fees,
            'academic_year_id' => $academic_year_id
        ]);
    }

    public function actionRemoveFee()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $fee_structure_id = isset($post['fee_structure_id']) ? $post['fee_structure_id'] : '';
        $student_id = isset($post['student_id']) ? $post['student_id'] : '';
        if (!empty($fee_structure_id) && !empty($student_id)) {
            $pay_fees = PayFees::find()->where(['student_id' => $student_id])->andWhere(['fee_structures_id' => $fee_structure_id])->one();
            if (!empty($pay_fees)) {
                $payment_details = PaymentDetails::find()->where(['pay_fees_id' => $pay_fees->id])->one();
                if (empty($payment_details)) {
                    if ($pay_fees->delete()) {
                        $data['status'] = 'ok';
                        $data['message'] = 'Payment Details Deleted Successfully';
                    } else {
                        $data['status'] = 'nok';
                        $data['message'] = 'Pay fee details delete failed';
                    }
                } else {
                    $data['status'] = 'nok';
                    $data['message'] = 'payment Details Already exist you can not delete this student of fee structure';
                }
            } else {
                $data['status'] = 'nok';
                $data['message'] = 'Pay Fee Details Not Found';
            }
        } else {
            $data['status'] = 'nok';
            $data['message'] = 'Fee Structure id and student id required';
        }

        return json_encode($data);
    }

    public function actionGetFeeStructureData()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $fee_structures = FeeStructures::find()->where(['id' => $id])->one();
        if (!empty($fee_structures)) {
            $fee_structuresData['fee'] = $fee_structures->fee;
            $fee_structuresData['maximum_detuction'] = $fee_structures->maximum_detuction;
            $fee_structuresData['title'] = $fee_structures->title;
            $data['status'] = 'ok';
            $data['details'] = $fee_structuresData;
        } else {
            $data['status'] = 'nok';
            $data['message'] = 'Fee structure data not found';
        }

        return json_encode($data);
    }

    public function actionPayOldFee()
    {
        $student_id = '';
        $model = new PayFees();
        $searchModel = new PayFeesSearch();
        $PaymentDetailsModel = new PaymentDetails();
        if (isset($_GET['PayFeesSearch'])) {
            $student_id = isset($_GET['PayFeesSearch']['student_id']) ? $_GET['PayFeesSearch']['student_id'] : '';
        }


        $PaymentDetailsSearch = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->campusSearch(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProviderPaymentDetailsSearch = $PaymentDetailsSearch->campusSubAdminSearchSearch(Yii::$app->request->queryParams, (new User())->getCampusesByUser(Yii::$app->user->identity->id), $student_id);
        }



        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, 'yes');
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }

        return $this->render('pay_old_fee', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProviderPaymentDetailsSearch' => $dataProviderPaymentDetailsSearch,
            'PaymentDetailsModel' => $PaymentDetailsModel,
            'PaymentDetailsSearch' => $PaymentDetailsSearch


        ]);
    }
    public function actionSearchStudentsByName($name)
    {
        $students = StudentDetails::find()
            ->select(['id', 'student_name', 'parent_id', 'student_class_id'])
            ->where(['like', 'student_name', $name])
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->all();

        $results = [];
        foreach ($students as $student) {
            // Fetch class and section names
            $classRoom = StudentClass::findOne($student->student_class_id);
            $className = $classRoom ? $classRoom->title : 'Unknown';
            $section = ClassSections::findOne($student->section_id);
            $sectionName = $section ? $section->section_name : 'Unknown';
            $father = ParentDetails::findOne($student->parent_id);
            $fatherName = $father ? $father->name_of_the_mother : 'Unknown';
            $results[] = [
                'id' => $student->id,
                'text' => "{$student->student_name} ({$className},{$fatherName})",
            ];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['results' => $results];
    }


    public function actionFetchStudentDetails($id)
    {
        $student = StudentDetails::findOne($id);
        return json_encode([
            'class_id' => $student->class_id,
            'section_id' => $student->section_id,
            // Add more fields as needed
        ]);
    }
    public function actionGetStudentDetails($id)
    {
        $student = StudentDetails::findOne($id);
        if ($student) {
            // Fetch class name
            $classRoom = StudentClass::findOne($student->student_class_id);
            $className = $classRoom ? $classRoom->title : 'Unknown';

            // Fetch section name
            $section = ClassSections::findOne($student->section_id);
            $sectionName = $section ? $section->section_name : 'Unknown';
            $father = ParentDetails::findOne($student->parent_id);
            $fatherName = $father ? $father->name_of_the_father : 'Unknown';

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'class_id' => $student->student_class_id, // ID to send
                'section_id' => $student->section_id, // ID to send
                'father_name' => $fatherName, // ID to send
                'phone_number' => $student->phone_number, // ID to send

                'class_name' => $className, // Name to display
                'section_name' => $sectionName, // Name to display
            ];
        }
        throw new \yii\web\NotFoundHttpException('Student not found.');
    }
}
