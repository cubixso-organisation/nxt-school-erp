<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\StaffAttendenceSettings as BaseStaffAttendenceSettings;

/**
 * This is the model class for table "staff_attendence_settings".
 */
class StaffAttendenceSettings extends BaseStaffAttendenceSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'title', 'daily_attendance_count', 'status', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'daily_attendance_count', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['title'], 'string', 'max' => 255]
        ]);
    }
	

}
