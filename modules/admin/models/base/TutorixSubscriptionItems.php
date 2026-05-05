<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;
/**
 * This is the base model class for table "tutorix_subscription_items".
 *
 * @property integer $id
 * @property integer $subscription_id
 * @property integer $student_id
 * @property integer $class_id
 * @property integer $parent_id
 * @property integer $class_type
 * @property double $item_price
 * @property string $start_date
 * @property string $expiry_date
 * @property integer $is_free_trail
 * @property integer $payment_status
 * @property string $tutorix_user_access_token
 * @property string $unique_id
 * @property integer $year_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\ParentDetails $parent
 */
class TutorixSubscriptionItems extends \yii\db\ActiveRecord
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
            'parent',
            'class'
        ];
    }

    const STATUS_INACTIVE = 3;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 const IS_FREE_TRAIL = 1;
 const IS_ACTIVATION = 2;
 const PAYMENT_PENDING = 1;
const PAYMENT_PAID = 2;
const PAYMENT_FAILED = 3;
public $campus_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscription_id', 'student_id', 'class_id', 'parent_id', 'class_type', 'item_price', 'start_date', 'expiry_date', 'is_free_trail', 'payment_status', 'tutorix_user_access_token', 'unique_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'subscription_id', 'student_id', 'class_id', 'parent_id', 'class_type', 'is_free_trail', 'payment_status', 'year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['item_price'], 'number'],
            ['campus_name', 'safe'],
            [['start_date', 'expiry_date', 'created_on', 'updated_on'], 'safe'],
            [['tutorix_user_access_token'], 'string'],
            [['unique_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tutorix_subscription_items';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Expaired',

        ];
    }
    public function getPaymentStateOptions()
    {
        return [

            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_PAID => 'Completed',
            self::PAYMENT_FAILED => 'Failed',

        ];
    }
    public function getTrailStateOptions()
    {
        return [

            self::IS_FREE_TRAIL => 'Free Trail',
            self::IS_ACTIVATION => 'Activation',
           

        ];
    }

    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Pending</span>';
        }elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Expaired</span>';
        }

    }
    public function getPaymentStateOptionsBadges()
    {

        if ($this->payment_status == self::PAYMENT_PAID) {
            return '<span class="badge badge-success">Completed</span>';
        } elseif ($this->payment_status == self::PAYMENT_PENDING) {
            return '<span class="badge badge-default">Pending</span>';
        }elseif ($this->payment_status == self::PAYMENT_FAILED) {
            return '<span class="badge badge-danger">Failed</span>';
        }

    }
    public function getTrailStateOptionsBadges()
    {

        if ($this->is_free_trail == self::IS_FREE_TRAIL) {
            return '<span class="badge badge-success">Free Trail</span>';
        } elseif ($this->is_free_trail == self::IS_ACTIVATION) {
            return '<span class="badge badge-default">Activation</span>';
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
            'subscription_id' => Yii::t('app', 'Subscription ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'class_type' => Yii::t('app', 'Class Type'),
            'item_price' => Yii::t('app', 'Item Price'),
            'start_date' => Yii::t('app', 'Start Date'),
            'expiry_date' => Yii::t('app', 'Expiry Date'),
            'is_free_trail' => Yii::t('app', 'Is Free Trail'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'tutorix_user_access_token' => Yii::t('app', 'Tutorix User Access Token'),
            'unique_id' => Yii::t('app', 'Unique ID'),
            'year_id' => Yii::t('app', 'Year ID'),
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
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\app\modules\admin\models\ParentDetails::className(), ['id' => 'parent_id']);
    }
    public function getclass()
    {
        return $this->hasOne(\app\modules\admin\models\TutorixClass::className(), ['id' => 'class_id']);
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
     * @return \app\modules\admin\models\TutorixSubscriptionItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TutorixSubscriptionItemsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['subscription_id'] =  $this->subscription_id;
        
                $data['student_id'] =  $this->student_id;
        
                $data['class_id'] =  $this->class_id;
        
                $data['parent_id'] =  $this->parent_id;
        
                $data['class_type'] =  $this->class_type;
        
                $data['item_price'] =  $this->item_price;
        
                $data['start_date'] =  $this->start_date;
        
                $data['expiry_date'] =  $this->expiry_date;
        
                $data['is_free_trail'] =  $this->is_free_trail;
        
                $data['payment_status'] =  $this->payment_status;
        
                $data['tutorix_user_access_token'] =  $this->tutorix_user_access_token;
        
                $data['unique_id'] =  $this->unique_id;
        
                $data['year_id'] =  $this->year_id;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


