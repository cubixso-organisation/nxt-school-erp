<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\SpecialDays as BaseSpecialDays;

/**
 * This is the model class for table "special_days".
 */
class SpecialDays extends BaseSpecialDays
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'academic_year_id', 'date', 'title'], 'required'],
            [['campus_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ]);
    }
	

}
