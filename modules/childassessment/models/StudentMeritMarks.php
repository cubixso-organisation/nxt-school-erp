<?php

namespace app\modules\childassessment\models;

use Yii;
use \app\modules\childassessment\models\base\StudentMeritMarks as BaseStudentMeritMarks;

/**
 * This is the model class for table "student_merit_marks".
 */
class StudentMeritMarks extends BaseStudentMeritMarks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id','child_merit_id', 'student_details_id', 'teacher_details_id', 'marks_scored','max_marks', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
