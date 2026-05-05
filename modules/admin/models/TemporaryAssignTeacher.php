<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TemporaryAssignTeacher as BaseTemporaryAssignTeacher;

/**
 * This is the model class for table "temporary_assign_teacher".
 */
class TemporaryAssignTeacher extends BaseTemporaryAssignTeacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'campus_id', 'teacher_detail_id', 'teacher_timetable_id', 'date', 'day_id', 'period', 'time_from', 'time_to', 'class_id', 'section_id', 'subject_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'campus_id', 'teacher_detail_id', 'teacher_timetable_id', 'date', 'day_id', 'period', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['time_from', 'time_to'], 'string', 'max' => 255]
        ]);
    }
	

}
