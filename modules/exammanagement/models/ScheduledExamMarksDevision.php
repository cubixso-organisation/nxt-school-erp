<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\ScheduledExamMarksDevision as BaseScheduledExamMarksDevision;

/**
 * This is the model class for table "scheduled_exam_marks_devision".
 */
class ScheduledExamMarksDevision extends BaseScheduledExamMarksDevision
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['marks_devision_id', 'exam_schedule_id', 'campus_id', 'max_marks', 'min_marks', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['marks_devision_id', 'exam_schedule_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['max_marks', 'min_marks'], 'number'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
