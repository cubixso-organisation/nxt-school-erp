<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\DaycareAttendance as BaseDaycareAttendance;

/**
 * This is the model class for table "daycare_attendance".
 */
class DaycareAttendance extends BaseDaycareAttendance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'student_id', 'teacher_id', 'type', 'lat', 'lng', 'status'], 'required'],
            [['campus_id', 'student_id', 'teacher_id', 'type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['time', 'created_on', 'updated_on'], 'safe'],
            [['lat', 'lng'], 'string', 'max' => 200],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	

}
