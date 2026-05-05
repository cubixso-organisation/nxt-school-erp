<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "tutorix_subscriptions".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $student_id
 * @property integer $parent_id
 * @property integer $subscription_type
 * @property integer $campus_id
 * @property integer $total_item
 * @property double $total_item_price
 * @property double $gst_percentage
 * @property double $gst_amount
 * @property double $other_charges
 * @property integer $coupon_applied_id
 * @property string $coupon_code
 * @property double $coupon_discount
 * @property double $total_amount
 * @property integer $payment_status
 * @property integer $payment_method
 * @property string $tutorix_user_access_token
 * @property string $unique_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class TutorixSubscriptions extends \yii\db\ActiveRecord
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
            'parent'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


    const PAYMENT_PENDING = 1;
    const PAYMENT_PAID = 2;
    const PAYMENT_FAILED = 3;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'student_id', 'parent_id', 'subscription_type', 'campus_id', 'total_item', 'total_item_price', 'gst_percentage', 'gst_amount', 'other_charges', 'coupon_applied_id', 'coupon_code', 'coupon_discount', 'total_amount', 'tutorix_user_access_token', 'unique_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'user_id', 'student_id', 'parent_id', 'subscription_type', 'campus_id', 'total_item', 'coupon_applied_id', 'payment_status', 'payment_method', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_item_price', 'gst_percentage', 'gst_amount', 'other_charges', 'coupon_discount', 'total_amount'], 'number'],
            [['tutorix_user_access_token'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['coupon_code', 'unique_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tutorix_subscriptions';
    }

    public function getPaymentStateOptions()
    {
        return [

            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_PAID => 'Completed',
            self::PAYMENT_FAILED => 'Failed',

        ];
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'subscription_type' => Yii::t('app', 'Subscription Type'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'total_item' => Yii::t('app', 'Total Item'),
            'total_item_price' => Yii::t('app', 'Total Item Price'),
            'gst_percentage' => Yii::t('app', 'Gst Percentage'),
            'gst_amount' => Yii::t('app', 'Gst Amount'),
            'other_charges' => Yii::t('app', 'Other Charges'),
            'coupon_applied_id' => Yii::t('app', 'Coupon Applied ID'),
            'coupon_code' => Yii::t('app', 'Coupon Code'),
            'coupon_discount' => Yii::t('app', 'Coupon Discount'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'tutorix_user_access_token' => Yii::t('app', 'Tutorix User Access Token'),
            'unique_id' => Yii::t('app', 'Unique ID'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
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
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ],

        ];
    }


    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }

    public function getParent()
    {
        return $this->hasOne(\app\modules\admin\models\ParentDetails::className(), ['id' => 'parent_id']);
    }
    /**
     * @inheritdoc
     * @return \app\modules\admin\models\TutorixSubscriptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TutorixSubscriptionsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['user_id'] =  $this->user_id;

        $data['student_id'] =  $this->student_id;

        $data['parent_id'] =  $this->parent_id;

        $data['subscription_type'] =  $this->subscription_type;

        $data['campus_id'] =  $this->campus_id;

        $data['total_item'] =  $this->total_item;

        $data['total_item_price'] =  $this->total_item_price;

        $data['gst_percentage'] =  $this->gst_percentage;

        $data['gst_amount'] =  $this->gst_amount;

        $data['other_charges'] =  $this->other_charges;

        $data['coupon_applied_id'] =  $this->coupon_applied_id;

        $data['coupon_code'] =  $this->coupon_code;

        $data['coupon_discount'] =  $this->coupon_discount;

        $data['total_amount'] =  $this->total_amount;

        $data['payment_status'] =  $this->payment_status;

        $data['payment_method'] =  $this->payment_method;

        $data['tutorix_user_access_token'] =  $this->tutorix_user_access_token;

        $data['unique_id'] =  $this->unique_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
