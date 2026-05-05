<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\search\PaymentDetailsSearch;
use app\modules\admin\models\StudentDetails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\search\FeeStructuresSearch;
use app\modules\admin\models\search\PayFeesSearch;
use app\modules\admin\models\search\StudentDetailsSearch;
use app\modules\admin\models\StudentHasParent;
use yii\data\ActiveDataProvider;


/**
 * PaymentDetailsController implements the CRUD actions for PaymentDetails model.
 */
class PaymentDetailsController extends Controller
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
                            'pay-fee-id-data',
                            'fees-reports',
                            'payment-details-pending',
                            'balance-sheet',
                            'status-change',
                            'today-transactions',
                            'fee-report-by-fee-type'
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
    /**
     * Lists all PaymentDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearchSearch(Yii::$app->request->queryParams);
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionFeeReportByFeeType()
    {



        return $this->render('fee_report_structure');
    }
    public function actionPaymentDetailsPending()
    {
        $searchModel = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, '', PaymentDetails::status_pending);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {

            $dataProvider = $searchModel->campusSubAdminSearchSearch(Yii::$app->request->queryParams, '', PaymentDetails::status_pending);
        }




        return $this->render('payment_details_pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    public function actionFeesReports()
    {


        $searchModel = new PayFeesSearch();
        $FeeStructuresSearchSearchModel = new FeeStructuresSearch();
        $studentDetailsSearch = new StudentDetailsSearch();
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



        return $this->render('fees_reports', [
            'dataProvider' => $dataProvider,
            'FeeStructuresSearchSearchModel' => $FeeStructuresSearchSearchModel,
            'dataProviderStudentDetails' => $dataProviderStudentDetails,
            'studentDetailsSearch' => $studentDetailsSearch,
            'feeStructuresModal' => $feeStructuresModal,
            'searchModel' => $searchModel
        ]);
    }



    public function actionTodayTransactions()
    {
        $searchModel = new PaymentDetailsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, '', '', '', date('Y-m-d'));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->todaysTransactionSearch(Yii::$app->request->queryParams);
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    /**
     * Displays a single PaymentDetails model.
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
     * Creates a new PaymentDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentDetails();

        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();




            $pay_fees_id_ref = $post['PaymentDetails']['pay_fees_id'];
            $pay_fees = PayFees::find()->where(['reference_number' => $pay_fees_id_ref])->one();
            $pay_fees_id = $pay_fees->id;
            $model->pay_fees_id = $pay_fees_id;
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $student_id = $post['PaymentDetails']['student_id'];
            $section_id = $post['PaymentDetails']['section_id'];
            $class_id = $post['class_id']['class_id'];
            if ($model->save(false)) {
                $paid = (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id);
                $fee_structures = FeeStructures::find()->where(['id' => $pay_fees->fee_structures_id])->one();
                $fees_cut = $pay_fees->fees_cut;
                $fee = $fee_structures->fee;
                $studentPayAmount = $fee - $fees_cut;
                $balanceAmount = $studentPayAmount - $paid;
                //update balance amount
                $payment_details = PaymentDetails::find()->where(['id' => $model->id])->one();
                $payment_details->balance_amount = $balanceAmount;
                $payment_details->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }




    /**
     * Updates an existing PaymentDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {

            $model->save();
            if ($model->status == PaymentDetails::status_failed) {
                $title = 'Payment Failed';
                $body = 'Your Payment Failed  ' . $model->paid_amount . '/-';
            } elseif ($model->status == PaymentDetails::status_success) {
                $title = 'Payment Success';
                $body = 'Your Payment Successfully paid  ' . $model->paid_amount . '/-';
            } elseif ($model->status == PaymentDetails::status_pending) {
                $title = 'Payment Pending';
                $body = 'Your Payment is Pending  ' . $model->paid_amount . '/-';
            }



            $type = '';
            $student_id = $model->student_id;
            $student_has_parent = StudentHasParent::find()->where(['student_id' => $student_id])->one();
            $parent_id = $student_has_parent->parent_id;
            if (!empty($parent_id)) {
                Yii::$app->notification->UserNotification('', $parent_id, $title, $body, $type);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PaymentDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = PaymentDetails::status_failed;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = PaymentDetails::find()->where([
                'id' => $post['id'],
            ])->one();
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


    public function actionPayFeeIdData()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $student_id = $parents[0];
                $out = (new PaymentDetails())->getPayFeeId($student_id);
                return $out;
            }
        }

        return $out;
    }
    public function actionStatusChange()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $id = isset($post['id']) ? $post['id'] : '';
        $val = isset($post['val']) ? $post['val'] : '';
        if (!empty($id)) {
            $payment_details = PaymentDetails::find()->where(['id' => $id])->one();
            if (!empty($payment_details)) {
                $payment_details->status = $val;
                if ($payment_details->save(false)) {
                    //send conformation sms to phone
                    $parent_number = StudentDetails::getPatentNumberByStudentId($payment_details->student_id);
                    $student_details = StudentDetails::find()->where(['id' => $payment_details->student_id])->one();
                    if ($payment_details->status == PaymentDetails::status_success) {
                        $arr_var_data = [];
                        $arr_var_data['VAR1'] = $payment_details->paid_amount;
                        $arr_var_data['VAR2'] = $student_details->student_name;
                        $sms = Yii::$app->notification->sendSMSDynamicTemplateV2($parent_number, 'Accountant Payment_Office', $arr_var_data);
                    }


                    $data['status'] = 'ok';
                    $data['message'] = 'details updated success';
                } else {
                    $data['status'] = 'nok';
                    $data['message'] = 'details updated failed';
                }
            } else {
                $data['status'] = 'nok';
                $data['message'] = 'Payment Details Not Found';
            }
        } else {
            $data['status'] = 'nok';
            $data['message'] = 'Payment Details Id  Required';
        }


        return json_encode($data);
    }



    /**
     * Finds the PaymentDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
