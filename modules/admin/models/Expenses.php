<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Expenses as BaseExpenses;

/**
 * This is the model class for table "expenses".
 */
class Expenses extends BaseExpenses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'amount', 'student_id', 'item_description', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'student_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['amount'], 'number'],
            // [['item_description'], 'string'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
