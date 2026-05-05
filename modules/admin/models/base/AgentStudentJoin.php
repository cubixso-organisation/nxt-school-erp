<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "agent_student_join".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_id
 * @property integer $agent_id
 * @property double $amount
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentDetailsAgentLead $student
 * @property \app\modules\admin\models\User $agent
 * @property \app\modules\admin\models\Campus $campus
 */
class AgentStudentJoin extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'student',
            'agent',
            'campus'
        ];
    }



    const STATUS_FAILED = 0;
    const STATUS_PAID = 1;
    const STATUS_PENDING = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


    const PAYMENT_CASH = 1;
    const PAYMENT_DD = 2;
    const PAYMENT_CHEQUE = 3;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_id', 'agent_id'], 'required'],
            [['campus_id', 'student_id', 'agent_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['amount'], 'number'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_student_join';
    }

    public function getStateOptions()
    {




        return [

            self::STATUS_FAILED => 'Failed',
            self::STATUS_PAID => 'Paid',
            self::STATUS_PENDING => 'Pending',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_FAILED) {
            return '<span class="badge badge-danger">Failed</span>';
        } elseif ($this->status == self::STATUS_PAID) {
            return '<span class="badge badge-success">Paid</span>';
        } elseif ($this->status == self::STATUS_PENDING) {
            return '<span class="badge badge-info">Pending</span>';
        }
    }

    public function getPaymentOptions()
    {
        return [

            self::PAYMENT_CASH => 'Cash',
            self::PAYMENT_DD => 'DD',
            self::PAYMENT_CHEQUE => 'Cheque',

        ];
    }
    public function getPaymentOptionsBadges()
    {
        if ($this->status == self::PAYMENT_CASH) {
            return '<span class="badge badge-primary">Cash</span>';
        } elseif ($this->status == self::PAYMENT_DD) {
            return '<span class="badge badge-success">DD</span>';
        } elseif ($this->status == self::PAYMENT_CHEQUE) {
            return '<span class="badge badge-info">Cheque</span>';
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'agent_id' => Yii::t('app', 'Agent ID'),
            'amount' => Yii::t('app', 'Amount'),
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetailsAgentLead::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
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
     * @return \app\modules\admin\models\AgentStudentJoinQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\AgentStudentJoinQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['student_id'] =  $this->student_id;

        $data['agent_id'] =  $this->agent_id;

        $data['amount'] =  $this->amount;

        $data['amount'] =  $this->amount;
        $data['razorpay_order_id'] =  $this->razorpay_order_id;
        $data['razorpay_payment_id'] =  $this->razorpay_payment_id;
        $data['utr_number'] =  $this->utr_number;
        $data['payment_receipt'] =  $this->payment_receipt;
        $data['payment_mode'] =  $this->payment_mode;


        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
