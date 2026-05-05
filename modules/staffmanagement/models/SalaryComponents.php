<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\SalaryComponents as BaseSalaryComponents;

/**
 * This is the model class for table "salary_components".
 */
class SalaryComponents extends BaseSalaryComponents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['name', 'component_type', 'value_type', 'component_value_monthly', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['component_type', 'value_type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['component_value_monthly'], 'number'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['name'], 'string', 'max' => 255]
        ]);
    }
	

}
