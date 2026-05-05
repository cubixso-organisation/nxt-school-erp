<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\PocketMoney as BasePocketMoney;

/**
 * This is the model class for table "pocket_money".
 */
class PocketMoney extends BasePocketMoney
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['student_id', 'campus_id', 'academic_year_id', 'amount', 'descriptions', 'payment_status', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['student_id', 'campus_id', 'academic_year_id', 'payment_status', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['amount'], 'number'],
            // [['descriptions'], 'string'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
