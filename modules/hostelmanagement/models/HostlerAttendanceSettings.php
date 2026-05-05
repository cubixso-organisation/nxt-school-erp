<?php

namespace app\modules\hostelmanagement\models;

use Yii;
use \app\modules\hostelmanagement\models\base\HostlerAttendanceSettings as BaseHostlerAttendanceSettings;

/**
 * This is the model class for table "hostler_attendance_settings".
 */
class HostlerAttendanceSettings extends BaseHostlerAttendanceSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'daily_attendance_count', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ]);
    }
	

}
