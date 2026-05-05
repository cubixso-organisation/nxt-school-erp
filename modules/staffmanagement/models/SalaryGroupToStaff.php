<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\SalaryGroupToStaff as BaseSalaryGroupToStaff;

/**
 * This is the model class for table "salary_group_to_staff".
 */
class SalaryGroupToStaff extends BaseSalaryGroupToStaff
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['staff_id', 'staff_user_id', 'salary_group_id', 'campus_id', 'status', 'updated_on', 'created_on', 'create_user_id', 'update_user_id'], 'required'],
            [['staff_id', 'staff_user_id', 'salary_group_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['updated_on', 'created_on'], 'safe']
        ]);
    }
	

}
