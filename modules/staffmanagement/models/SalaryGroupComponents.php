<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\SalaryGroupComponents as BaseSalaryGroupComponents;

/**
 * This is the model class for table "salary_group_components".
 */
class SalaryGroupComponents extends BaseSalaryGroupComponents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['group_id', 'component_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['group_id', 'component_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
