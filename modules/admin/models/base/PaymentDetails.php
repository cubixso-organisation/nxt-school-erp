<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\base\FeeStructures as BaseFeeStructures;
use app\modules\admin\models\FeeStructures;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "payment_details".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_id
 * @property integer $pay_fees_id
 * @property string $paid_reference_number
 * @property integer $payment_mode
 * @property double $paid_amount
 * @property double $balance_amount
 * @property string $remarks
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\PayFees $payFees
 */
class PaymentDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $student_class_id;
    public $class_section_id;
    public $fee_structures_id;
    public $total_fee_amount;
    public $parent_name;



    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'updateUser',
            'createUser',
            'campus',
            'student',
            'payFees',
            'class',
            'feeCollectedBy',
            'section'
        ];
    }

    public const status_success = 1;
    public const status_failed = 2;
    public const status_pending = 3;

    public const payment_mode_online = 1;
    public const payment_mode_offline = 2;
    public const payment_mode_net_banking = 3;
    public const payment_mode_counter_pay = 4;




    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_id', 'pay_fees_id', 'paid_reference_number', 'payment_mode', 'paid_amount', 'class_id', 'section_id'], 'required'],
            [['campus_id', 'student_id', 'class_id', 'section_id', 'pay_fees_id', 'payment_mode', 'fee_collected_by', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['paid_amount', 'balance_amount'], 'number'],
            [['remarks'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['paid_reference_number', 'razorpay_order_id', 'razorpay_payment_id'], 'string', 'max' => 255]




        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_details';
    }




    public function gePaymentModeOptions()
    {
        return [

            self::payment_mode_offline => 'offline',
            self::payment_mode_net_banking => 'Net Banking',
            self::payment_mode_counter_pay => 'counter pay'


        ];
    }


    public function getStateOptions()
    {
        return [
            self::status_success => 'Success',
            self::status_pending => 'Pending',
            self::status_failed => 'failed',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::status_success) {
            return '<span class="badge badge-success">success</span>';
        } elseif ($this->status == self::status_failed) {
            return '<span class="badge badge-danger">Failed</span>';
        } elseif ($this->status == self::status_pending) {
            return '<span class="badge badge-danger">Pending</span>';
        }
    }

    public function getFeatureOptions()
    {
        return [

            self::IS_FEATURED => 'Is Featured',
            self::IS_NOT_FEATURED => 'Not Featured',

        ];
    }

    public function getFeatureOptionsBadges()
    {
        if ($this->is_featured == self::IS_FEATURED) {
            return '<span class="badge badge-success">Featured</span>';
        } elseif ($this->is_featured == self::IS_NOT_FEATURED) {
            return '<span class="badge badge-danger">Not Featured</span>';
        }
    }


    public function getPaymentModeOptionsBadges()
    {
        if ($this->payment_mode == self::payment_mode_net_banking) {
            return '<span class="badge badge-success">Net Banking</span>';
        } elseif ($this->payment_mode == self::payment_mode_offline) {
            return '<span class="badge badge-warning">Offline</span>';
        } elseif ($this->payment_mode == self::payment_mode_online) {
            return '<span class="badge badge-info">Online</span>';
        } elseif ($this->payment_mode == self::payment_mode_counter_pay) {
            return '<span class="badge badge-primary">Counter Pay</span>';
        }
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'student_id' => Yii::t('app', 'Student'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'pay_fees_id' => Yii::t('app', 'Pay Fees'),
            'paid_reference_number' => Yii::t('app', 'Paid Reference Number'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'paid_amount' => Yii::t('app', 'Paid Amount'),
            'balance_amount' => Yii::t('app', 'Balance Amount'),
            'fee_collected_by' => Yii::t('app', 'Fee Collected By'),
            'remarks' => Yii::t('app', 'Remarks'),
            'razorpay_order_id' => Yii::t('app', 'Razorpay Order ID'),
            'razorpay_payment_id' => Yii::t('app', 'Razorpay Payment ID'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }


    public function getPaidAmount($student_id, $student_class_id, $section_id, $pay_fees_id, $academic_year_id = '')
    {

        // var_dump("student_id", $student_id);
        // var_dump("student_class_id", $student_class_id);
        // var_dump("section_id", $section_id);
        // var_dump("pay_fees_id", $pay_fees_id);

        // exit;

        if (!empty($academic_year_id)) {
            $paid = PaymentDetails::find()
                ->select("payment_details.paid_amount, payment_details.balance_amount,payment_details.pay_fees_id")
                ->joinWith(['payFees'])
                ->where(['payment_details.student_id' => $student_id])
                // ->andWhere(['payment_details.class_id' => $student_class_id])
                ->andWhere(['pay_fees.academic_year_id' => $academic_year_id])
                // ->andWhere(['payment_details.section_id' => $section_id])
                ->andWhere(['payment_details.pay_fees_id' => $pay_fees_id])
                ->andWhere(['payment_details.status' => PaymentDetails::status_success])
                ->sum('payment_details.paid_amount');
        } else {
            $paid = PaymentDetails::find()
                ->select("payment_details.paid_amount, payment_details.balance_amount,payment_details.pay_fees_id")
                ->joinWith(['payFees'])
                ->where(['payment_details.student_id' => $student_id])
                ->andWhere(['payment_details.class_id' => $student_class_id])
                // ->andWhere(['payment_details.section_id' => $section_id])
                ->andWhere(['payment_details.pay_fees_id' => $pay_fees_id])
                ->andWhere(['payment_details.status' => PaymentDetails::status_success])
                ->sum('payment_details.paid_amount');
        }


        return !empty($paid) ? $paid : 0;
    }



    public static function getTotalFeeByStudentId($student_id, $feeStructures_id = '')
    {

        // 
        $fee_structures = FeeStructures::find()->joinWith('payFees')->where(['pay_fees.student_id' => $student_id])->andWhere(['fee_structures.id' => $feeStructures_id])->sum('fee');

        $fees_cut = PayFees::find()->where(['student_id' => $student_id])->andWHere(['fee_structures_id' => $feeStructures_id])->sum('fees_cut');
        $total_fee = $fee_structures - $fees_cut;
        // var_dump("fee_structures", $fee_structures);
        // exit;
        return $total_fee;
    }


    public static function getTotalFeeByStudentIdWithoutFeeCut($student_id, $feeStructures_id = '')
    {

        $fee_structures = FeeStructures::find()
            ->joinWith('payFees')
            ->where(['pay_fees.student_id' => $student_id])
            ->andWhere(['fee_structures.id' => $feeStructures_id])
            ->sum('fee');



        $total_fee = $fee_structures;
        return $total_fee;
    }



    public static function getTotalFeeByStudentIdFeeDiscount($student_id, $feeStructures_id = '')
    {




        $pay_discount = PayFees::find()->where(['student_id' => $student_id])->andWHere(['fee_structures_id' => $feeStructures_id])->sum('fees_cut');


        return $pay_discount;
    }




    public function getPaymentAmount($fee_structures_id, $student_id)
    {
        // return $fee_structures_id;
        $fee = FeeStructures::find()->joinWith('payFees')
            ->where(['pay_fees.fee_structures_id' => $fee_structures_id])
            ->andWhere(['pay_fees.student_id' => $student_id])
            ->sum('fee_structures.fee');
        $fees_cut = PayFees::find()
            ->where(['fee_structures_id' => $fee_structures_id])
            ->andWhere(['student_id' => $student_id])
            ->sum('fees_cut');
        $pay_amount = $fee - $fees_cut;
        return !empty($pay_amount) ? $pay_amount : 0;
    }

    public function getDueAmount($student_id, $student_class_id, $section_id, $pay_fees_id, $fee_structures_id, $academic_year_id = '')
    {

        $due =  $this->getPaymentAmount($fee_structures_id, $student_id) - $this->getPaidAmount($student_id, $student_class_id, $section_id, $pay_fees_id, $academic_year_id);
        return !empty($due) ? $due : 0;
    }





    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
    }


    public function getFeeCollectedBy()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'fee_collected_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayFees()
    {
        return $this->hasOne(\app\modules\admin\models\PayFees::className(), ['id' => 'pay_fees_id']);
    }





    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
    }


    public function getPayFeeId($student_id)
    {
        $out = [];
        $data = PayFees::find()
            ->select('pay_fees.*,fee_structures.*')
            ->joinWith(['feeStructures'])
            ->where(['pay_fees.student_id' => $student_id])->all();


        foreach ($data as $dat) {
            $out[] = ['id' => $dat['reference_number'], 'name' => $dat['title']];
        }
        return $output = [
            'output' => $out
        ];
    }




    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_on',
                'updatedAtAttribute' => 'updated_on',
                'value' => date('Y-m-d H:i:s'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\admin\models\PaymentDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\PaymentDetailsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['student_id'] =  $this->student_id;

        $data['pay_fees_id'] =  $this->pay_fees_id;
        $data['fee_type'] = $this->payFees->feeStructures->title;

        $data['paid_reference_number'] =  $this->paid_reference_number;

        $data['payment_mode'] =  $this->payment_mode;
        $data['fee_collected_by'] =  $this->fee_collected_by;


        $data['paid_amount'] =  $this->paid_amount;

        $data['balance_amount'] =  $this->balance_amount;

        $data['remarks'] =  $this->remarks;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }




    public function asJsonParent()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['student_id'] =  $this->student_id;

        $data['pay_fees_id'] =  $this->pay_fees_id;

        $data['paid_reference_number'] =  $this->paid_reference_number;

        $data['payment_mode'] =  $this->payment_mode;

        $data['fee_collected_by'] =  $this->fee_collected_by;


        $data['paid_amount'] =  $this->paid_amount;

        $data['balance_amount'] =  $this->balance_amount;

        $data['remarks'] =  $this->remarks;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
