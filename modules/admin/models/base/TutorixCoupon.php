<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "tutorix_coupon".
 *
 * @property integer $id
 * @property string $code
 * @property integer $coupon_type
 * @property double $coupon_discount
 * @property double $max_discount
 * @property integer $min_cart_item
 * @property integer $max_cart_item
 * @property string $start_date
 * @property string $end_date	
 * @property double $min_cart_value
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class TutorixCoupon extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;


    const TYPE_PERCENTAGE = 1;
    const TYPE_FLAT = 2;
    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'coupon_type', 'coupon_discount', 'min_cart_item', 'max_cart_item', 'min_cart_value','max_cart_value','start_date','end_date', 'status'], 'required'],
            [['coupon_type', 'min_cart_item', 'max_cart_item', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['coupon_discount', 'max_discount', 'min_cart_value'], 'number'],
            [['created_on', 'updated_on','start_date','end_date'], 'safe'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tutorix_coupon';
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
        }elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }

    }



    public function getTypeOptions()
    {
        return [

            self::TYPE_PERCENTAGE => 'Percentage',
            self::TYPE_FLAT => 'Flat Discount',

        ];
    }
    public function getTypeOptionsBadges()
    {

        if ($this->coupon_type == self::TYPE_PERCENTAGE) {
            return '<span class="badge badge-success">Percentage</span>';
        } elseif ($this->coupon_type == self::TYPE_FLAT) {
            return '<span class="badge badge-default">Flat Discount</span>';
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
            'id' => 'ID',
            'code' => 'Code',
            'coupon_type' => 'Coupon Tye',
            'coupon_discount' => 'Coupon Discount',
            'max_discount' => 'Max Discount',
            'min_cart_item' => 'Min Cart Item',
            'max_cart_item' => 'Max Cart Item',
            'min_cart_value' => 'Min Cart Value',
            'start_date' => 'Start Date',
            'end_date	' => 'End Date',
            'status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
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



    /**
     * @inheritdoc
     * @return \app\modules\admin\models\TutorixCouponQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TutorixCouponQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['code'] =  $this->code;
        
                $data['coupon_type'] =  $this->coupon_type;
        
                $data['coupon_discount'] =  $this->coupon_discount;
        
                $data['max_discount'] =  $this->max_discount;
        
                $data['min_cart_item'] =  $this->min_cart_item;
        
                $data['max_cart_item'] =  $this->max_cart_item;
        
                $data['min_cart_value'] =  $this->min_cart_value;
                $data['start_date'] =  $this->start_date;
                $data['end_date	'] =  $this->end_date	;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


