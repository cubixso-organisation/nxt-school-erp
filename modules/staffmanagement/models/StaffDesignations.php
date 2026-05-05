<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\StaffDesignations as BaseStaffDesignations;

/**
 * This is the model class for table "staff_designations".
 */
class StaffDesignations extends BaseStaffDesignations
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['title', 'campus_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['title'], 'string', 'max' => 255]
        ]);
    }
	

}
