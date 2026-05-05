<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixSubscriptionItems as BaseTutorixSubscriptionItems;

/**
 * This is the model class for table "tutorix_subscription_items".
 */
class TutorixSubscriptionItems extends BaseTutorixSubscriptionItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'subscription_id', 'student_id', 'class_id', 'parent_id', 'class_type', 'item_price', 'start_date', 'expiry_date', 'is_free_trail', 'payment_status', 'tutorix_user_access_token', 'unique_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'subscription_id', 'student_id', 'class_id', 'parent_id', 'class_type', 'is_free_trail', 'payment_status', 'year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['item_price'], 'number'],
            [['start_date', 'expiry_date', 'created_on', 'updated_on'], 'safe'],
            [['tutorix_user_access_token'], 'string'],
            [['unique_id'], 'string', 'max' => 255]
        ]);
    }
	

}
