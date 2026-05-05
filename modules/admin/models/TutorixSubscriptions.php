<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixSubscriptions as BaseTutorixSubscriptions;

/**
 * This is the model class for table "tutorix_subscriptions".
 */
class TutorixSubscriptions extends BaseTutorixSubscriptions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'user_id', 'student_id', 'parent_id', 'subscription_type', 'campus_id', 'total_item', 'total_item_price', 'gst_percentage', 'gst_amount', 'other_charges', 'coupon_applied_id', 'coupon_code', 'coupon_discount', 'total_amount', 'tutorix_user_access_token', 'unique_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'user_id', 'student_id', 'parent_id', 'subscription_type', 'campus_id', 'total_item', 'coupon_applied_id', 'payment_status', 'payment_method', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_item_price', 'gst_percentage', 'gst_amount', 'other_charges', 'coupon_discount', 'total_amount'], 'number'],
            [['tutorix_user_access_token'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['coupon_code', 'unique_id'], 'string', 'max' => 255]
        ]);
    }
	

}
