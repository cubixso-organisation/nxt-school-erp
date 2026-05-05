<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults as BaseScheduledExamMarksDevisionResults;

/**
 * This is the model class for table "scheduled_exam_marks_devision_results".
 */
class ScheduledExamMarksDevisionResults extends BaseScheduledExamMarksDevisionResults
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['exam_result_id', 'student_id', 'marks_devision_id', 'exam_schedule_id', 'scheduled_exam_devision_id', 'marks_scored', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['exam_result_id', 'student_id', 'marks_devision_id', 'exam_schedule_id', 'scheduled_exam_devision_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['marks_scored'], 'number'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
