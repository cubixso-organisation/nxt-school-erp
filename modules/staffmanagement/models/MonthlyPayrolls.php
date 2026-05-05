<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\MonthlyPayrolls as BaseMonthlyPayrolls;

/**
 * This is the model class for table "monthly_payrolls".
 */
class MonthlyPayrolls extends BaseMonthlyPayrolls
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'staff_id', 'user_id', 'yearly_ctc', 'monthly_ctc', 'salary_components', 'total_monthly_pay', 'date', 'month', 'salary_group_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'staff_id', 'user_id', 'salary_group_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['yearly_ctc', 'monthly_ctc', 'total_monthly_pay'], 'number'],
            [['salary_components'], 'string'],
            [['date', 'created_on', 'updated_on'], 'safe'],
            [['month'], 'string', 'max' => 255]
        ]);
    }
	

}
