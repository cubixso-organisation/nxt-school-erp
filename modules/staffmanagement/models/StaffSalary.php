<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\StaffSalary as BaseStaffSalary;

/**
 * This is the model class for table "staff_salary".
 */
class StaffSalary extends BaseStaffSalary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'staff_id', 'ctc', 'basic_salary_type', 'basic_salary_value', 'earnings', 'ctc_monthly', 'ctc_yearly', 'total_deduction_monthly', 'total_deduction_yearly', 'salary_group_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'staff_id', 'basic_salary_type', 'salary_group_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['ctc', 'basic_salary_value', 'ctc_monthly', 'ctc_yearly', 'total_deduction_monthly', 'total_deduction_yearly'], 'number'],
            [['earnings'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
