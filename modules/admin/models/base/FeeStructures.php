<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\ClassSections;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\PaymentDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "fee_structures".
 *
 * @property integer $id 
 * @property integer $campus_id
 * @property integer $fee_type_id
 * @property integer $student_class_id
 * @property integer $class_section_id
 * @property double $fee
 * @property double $maximum_detuction
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id 
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\AssignFeeToStudent[] $assignFeeToStudents
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\ClassSections $classSection
 * @property \app\modules\admin\models\StudentClass $studentClass
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\FeesTyps $feeType
 * @property \app\modules\admin\models\PayFees[] $payFees
 */
class FeeStructures extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $total_paid_fee;
    public $total_balance_fee;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'classSection',
            'studentClass',
            'updateUser',
            'createUser',
            'feeType',
            'payFees'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'fee_type_id', 'title', 'student_class_id', 'class_section_id', 'fee', 'maximum_detuction'], 'required'],
            [['campus_id', 'fee_type_id', 'student_class_id', 'class_section_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['fee', 'maximum_detuction'], 'number'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_structures';
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),

            'campus_id' => Yii::t('app', 'School Or College'),
            'title' => Yii::t('app', 'title'),
            'fee_type_id' => Yii::t('app', 'Fee Type'),
            'student_class_id' => Yii::t('app', 'Student Class'),
            'class_section_id' => Yii::t('app', 'Class Section'),
            'fee' => Yii::t('app', 'Fee'),
            'maximum_detuction' => Yii::t('app', 'Maximum Deduction'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }


    public function getTotalFeeByFeeStructureId($fee_structures_id)
    {
        $pay_fees = PayFees::find()
            ->joinWith('feeStructures as fs')
            ->where(['fee_structures_id' => $fee_structures_id])
            ->sum('fs.fee');

        $fees_cut = PayFees::find()
            ->joinWith('feeStructures as fs')
            ->where(['fee_structures_id' => $fee_structures_id])
            ->sum('pay_fees.fees_cut');
        if (!empty($pay_fees)) {
            $max_deduction = !empty($fees_cut) ? $fees_cut : 0;
            $total_fee = $pay_fees - $max_deduction;
            return $total_fee;
        } else {
            return 0;
        }
    }


    public function getTotalPaidFeeByFeeStructureId($fee_structures_id)
    {

        $payment_details = PaymentDetails::find()
            ->joinWith('payFees.feeStructures as fs')
            ->where(['fs.id' => $fee_structures_id])
            ->andWhere(['payment_details.status' => PaymentDetails::status_success])
            ->sum('paid_amount');

        if (!empty($payment_details)) {
            return $payment_details;
        } else {
            return 0;
        }
    }

    public function getTotalBalanceFeeFeeStructureId($fee_structures_id)
    {
        $totalFee = $this->getTotalFeeByFeeStructureId($fee_structures_id);
        $paidFee = $this->getTotalPaidFeeByFeeStructureId($fee_structures_id);
        $balanceFee = $totalFee - $paidFee;
        if (!empty($balanceFee)) {
            return $balanceFee;
        } else {
            return 0;
        }
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
    public function getClassSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'class_section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'student_class_id']);
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
    public function getFeeType()
    {
        return $this->hasOne(\app\modules\admin\models\FeesTyps::className(), ['id' => 'fee_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayFees()
    {
        return $this->hasMany(\app\modules\admin\models\PayFees::className(), ['fee_structures_id' => 'id']);
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
    public function getSectionData($class_id)
    {
        $out = [];
        $data = ClassSections::find()
            ->where(['student_class_id' => $class_id])
            ->andWhere(['status' => Campus::STATUS_ACTIVE])

            ->asArray()
            ->all();
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['section_name']];
        }
        return $output = [
            'output' => $out
        ];
    }
    public function getSectionDataFee($class_ids)
    {
        $out = [];
        $data = ClassSections::find()
            ->where(['student_class_id' => $class_ids])
            ->andWhere(['status' => Campus::STATUS_ACTIVE])
            ->asArray()
            ->all();

        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['section_name']];
        }

        return $out;
    }




    /**
     * @inheritdoc
     * @return \app\modules\admin\models\FeeStructuresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\FeeStructuresQuery(get_called_class());
    }
    public function asJson($student_id = '')
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['fee_type_id'] =  $this->fee_type_id;
        $data['title'] =  $this->title;
        $data['student_class_id'] =  $this->student_class_id;

        $data['class_section_id'] =  $this->class_section_id;

        $data['fee'] =  $this->fee;

        $data['maximum_detuction'] =  $this->maximum_detuction;

        if (!empty($student_id)) {
            $payFees = PayFees::find()->where(['student_id' => $student_id])->andWhere(['fee_structures_id' => $this->id])->one();
            if (!empty($payFees)) {
                $data['pay_fees'] =   $payFees->asJsonParent();
            } else {
                $data['pay_fees'] = '';
            }
        } else {
            $data['pay_fees'] = '';
        }




        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;


        return $data;
    }
}
