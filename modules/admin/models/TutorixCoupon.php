<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixCoupon as BaseTutorixCoupon;

/**
 * This is the model class for table "tutorix_coupon".
 */
class TutorixCoupon extends BaseTutorixCoupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['code', 'coupon_tye', 'coupon_discount', 'max_discount', 'min_cart_item', 'max_cart_item', 'min_cart_value', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['coupon_tye', 'min_cart_item', 'max_cart_item', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['coupon_discount', 'max_discount', 'min_cart_value'], 'number'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['code'], 'string', 'max' => 255]
        ]);
    }
	

}
