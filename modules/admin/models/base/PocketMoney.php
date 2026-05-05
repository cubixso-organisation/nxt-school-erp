<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "pocket_money".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $campus_id
 * @property integer $academic_year_id
 * @property double $amount
 * @property string $descriptions
 * @property integer $payment_status
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\AcademicYears $academicYear
 */
class PocketMoney extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'student',
            'academicYear'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;



    const CREDIT = 1;
    const DEBIT = 0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'descriptions', 'payment_status', 'status',], 'required'],
            [['student_id', 'campus_id', 'academic_year_id', 'payment_status', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['amount'], 'number'],
            [['descriptions'], 'string'],
            [['created_on', 'updated_on', 'created_on', 'updated_on', 'create_user_id', 'update_user_id', 'student_id', 'campus_id', 'academic_year_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pocket_money';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
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
    public function getPaymentStatus()
    {
        return [

            self::CREDIT => 'Credit',
            self::DEBIT => 'Debit',

        ];
    }

    public function getPaymentStatusOptionsBadges()
    {
        if ($this->payment_status == self::CREDIT) {
            return '<span class="badge badge-success">Credit</span>';
        } elseif ($this->payment_status == self::DEBIT) {
            return '<span class="badge badge-danger">Debit</span>';
        }
    }

    public function getPendingAmount($student_id, $campus_id)
    {
        $credits = PocketMoney::find()
            ->where(['student_id' => $student_id, 'campus_id' => $campus_id, 'payment_status' => self::CREDIT])
            ->sum('amount');
        $debits = PocketMoney::find()
            ->where(['student_id' => $student_id, 'campus_id' => $campus_id, 'payment_status' => self::DEBIT])
            ->sum('amount');

        return $credits - $debits;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'academic_year_id' => Yii::t('app', 'Academic Year ID'),
            'amount' => Yii::t('app', 'Amount'),
            'descriptions' => Yii::t('app', 'Descriptions'),
            'payment_status' => Yii::t('app', 'Payment Status'),
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
                'value' => new \yii\db\Expression('NOW()'),
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
     * @return \app\modules\admin\models\PocketMoneyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\PocketMoneyQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['student_id'] =  $this->student_id;

        $data['campus_id'] =  $this->campus_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['amount'] =  $this->amount;

        $data['descriptions'] =  $this->descriptions;

        $data['payment_status'] =  $this->payment_status;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
