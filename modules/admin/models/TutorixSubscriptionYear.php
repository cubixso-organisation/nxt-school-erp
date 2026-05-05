<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixSubscriptionYear as BaseTutorixSubscriptionYear;

/**
 * This is the model class for table "tutorix_subscription_year".
 */
class TutorixSubscriptionYear extends BaseTutorixSubscriptionYear
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['name', 'value', 'price', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['value', 'price'], 'number'],
            // [['status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['name'], 'string', 'max' => 255]
        ]);
    }
	

}
