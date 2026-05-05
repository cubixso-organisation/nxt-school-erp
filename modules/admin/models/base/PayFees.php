<?php

namespace app\modules\admin\models\base;

use app\components\RazorPay;
use app\models\User;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PaymentDetails;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "pay_fees".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_id
 * @property string $reference_number
 * @property integer $fee_structures_id
 * @property double $fees_cut
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\FeeStructures $feeStructures
 * @property \app\modules\admin\models\PaymentDetails[] $paymentDetails
 */
class PayFees extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $title;
    public $class_id;
    public $section_id;
    public $student_class_id;
    public $class_section_id;
    public $otp;
    public $sessionKey;
    public $deleteDataId;
    public $balance;
    public $payment_mode;
    public $payNow;
    public $amount;
    public $remarks;
    public $total_fee;
    public $assigned_fee_details;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'student',
            'updateUser',
            'createUser',
            'feeStructures',
            'paymentDetails',
            'academicYear'
        ];
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;

    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;


    public const payment_mode_online = 1;
    public const payment_mode_offline = 2;
    public const payment_mode_net_banking = 3;
    public const payment_mode_counter_pay = 4;
    public const razor_pay = 5;





    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_id', 'reference_number', 'fee_structures_id', 'remarks_of_pay_fee'], 'required'],
            [['campus_id', 'student_id', 'fee_structures_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['fees_cut', 'balance_fee'], 'number'],
            [['created_on', 'updated_on', 'section_id', 'student_class_id', 'remarks', 'balance_fee'], 'safe'],
            [['reference_number'], 'string', 'max' => 255],
            ['fees_cut', 'validateFeeDiscount', 'on' => 'update'],

        ];
    }



    public function validateFeeDiscount($attribute, $params)
    {
        $fee_structures = FeeStructures::find()
            ->where(['id' => $this->fee_structures_id])
            ->one();

        if (!empty($fee_structures)) {
            if ($fee_structures->maximum_detuction >= $this->fees_cut) {
            } else {
                $this->addError($attribute, 'Max Deduction Amount is' . $fee_structures->maximum_detuction);
            }
        } else {
        }
    }


    function checkRazorpayAccount($campus_id)
    {
        $razorPayAccount = RazorpayLinkedAccount::find()->where(['campus_id' => $campus_id])->andWhere(['status' => RazorpayLinkedAccount::STATUS_ACTIVE])->one();
        if (!empty($razorPayAccount)) {
            return true;
        } else {
            return false;
        }
    }
    public function gePaymentModeOptions()
    {
        $check = $this->checkRazorpayAccount((new User())->getCampusId());
        // var_dump($check);exit;
        if ($check) {
            return [

                self::payment_mode_offline => 'offline',
                self::payment_mode_net_banking => 'online/UPI',
                self::payment_mode_counter_pay => 'Counter Pay',
                self::razor_pay => "Razor Pay"



            ];
        } else {
            return [

                self::payment_mode_offline => 'offline',
                self::payment_mode_net_banking => 'online/UPI',
                self::payment_mode_counter_pay => 'Counter Pay',
                // self::razor_pay => "Razor Pay"



            ];
        }
    }



    public function showListArr($arrData, $property, $student_id = '')
    {
        if (empty($arrData)) {
            return '<span style="color:#888">No fee assigned</span>';
        }
        $dd = '';
        $current_color = -1;


        foreach ($arrData as $arrDataVal) {

            global $current_color;
            $colorArray = array('primary', 'success', 'danger', 'warning', 'info');
            $current_color++;
            $current_color = ($current_color < count($colorArray)) ? $current_color : 0;
            $classTitle = $arrDataVal->studentClass->title ?? ""; // Check if studentClass exists, else assign an empty string
            $sectionName = $arrDataVal->classSection->section_name ?? ""; // Check if classSection exists, else assign an empty string

            $dd .= '<div class="mb-2 bg-' . $colorArray[$current_color] . ' text-white" >' . $arrDataVal->$property . ' ' . $classTitle . '(' . $sectionName . ')' .
                '<button type="button" class="btn btn-tool"  onclick="unassignFee(' . $arrDataVal->id . ',' . $student_id . ')">
                    <i class="fas fa-times"></i>
                    </button></div>';
        }


        return $dd;
    }

    public static function getTillDatePendingByPayFee($pa_fee_id)
    {
        $pay_fees = PayFees::find()->where(['id' => $pa_fee_id])->one();
        $fees_cut = $pay_fees->fees_cut;
        $fee = $pay_fees->feeStructures->fee;


        $due_date = $pay_fees->due_date;
        $created_on = date('Y-m-d', strtotime($pay_fees->created_on));
        $to_day_date = date('Y-m-d');
        $startDate = new DateTime($created_on);
        $endDate = new DateTime($to_day_date);
        // Calculate the difference in months
        $interval = $startDate->diff($endDate);
        $monthsCompleted = $interval->y * 12 + $interval->m;
        if ($monthsCompleted == 0) {
            $monthsCompleted = 1;
        } else {
            $monthsCompleted = $monthsCompleted;
        }





        $payAbleFee = $fee - $fees_cut;
        $fees_type_months = $pay_fees->feeStructures->feeType->months;
        $till_now_paid_amount = PaymentDetails::find()->where(['pay_fees_id' => $pa_fee_id])->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
        //get monthly pay amount
        if ($payAbleFee != 0 && $fees_type_months) {
            $monthly_pay_able_amount = round($payAbleFee / $fees_type_months, 2);
            $nee_to_pay_able_amount_till_now = $monthly_pay_able_amount * $monthsCompleted;
            if ($nee_to_pay_able_amount_till_now <= $till_now_paid_amount) {
                $pay_amount = 0;
            } else {

                $pay_amount =   $nee_to_pay_able_amount_till_now - $till_now_paid_amount;
            }
        }





        $pay_amount = $pay_amount;
        return $pay_amount;
    }







    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_fees';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Inactive</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
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
            return '<span class="badge badge-info">Counter Pay</span>';
        } elseif ($this->payment_mode == self::razor_pay) {
            return '<span class="badge badge-info">Razor Pay</span>';
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Fee ID'),
            'campus_id' => Yii::t('app', 'Campus'),
            'student_id' => Yii::t('app', 'Student'),
            'reference_number' => Yii::t('app', 'Reference Number'),
            'fee_structures_id' => Yii::t('app', 'Fee Structures'),
            'fees_cut' => Yii::t('app', 'Fees Cut'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
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
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
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
    public function getFeeStructures()
    {
        return $this->hasOne(\app\modules\admin\models\FeeStructures::className(), ['id' => 'fee_structures_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\PaymentDetails::className(), ['pay_fees_id' => 'id']);
    }



    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
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
     * @return \app\modules\admin\models\PayFeesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\PayFeesQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['student_id'] =  $this->student_id;

        $data['reference_number'] =  $this->reference_number;

        $data['fee_structures_id'] =  $this->fee_structures_id;

        $data['fees_cut'] =  $this->fees_cut;

        $data['due_date'] = $this->due_date;


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


        $data['student_id'] =  $this->student_id;

        $data['reference_number'] =  $this->reference_number;
        $data['fee_structures_id'] =  $this->fee_structures_id;


        $data['fees_cut'] =  $this->fees_cut;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;


        $data['balance'] = !empty($this->balance_fee) ? $this->balance_fee : 0.00;


        $data['due_date'] = $this->due_date;



        if (!empty($this->student_id)) {
            $student_details = StudentDetails::find()->where(['id' => $this->student_id])->one();
            $class_id = $student_details->student_class_id;
            $section_id = $student_details->section_id;
            $total_fee = (new PaymentDetails())->getTotalFeeByStudentId($this->student_id, $this->fee_structures_id);

            $paid = (new PaymentDetails())->getPaidAmount($this->student_id, $class_id, $section_id, $this->id);
            $remaining_fee = $total_fee - $paid;
            if ($remaining_fee > 0) {
                $data['paid'] = false;
            } else {
                $data['paid'] = true;
            }
        } else {
            $data['paid'] = false;
        }






        return $data;
    }
}
