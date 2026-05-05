<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\SalaryGroups as BaseSalaryGroups;

/**
 * This is the model class for table "salary_groups".
 */
class SalaryGroups extends BaseSalaryGroups
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['name', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['name'], 'string', 'max' => 255]
        ]);
    }
	

}
