<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\AssessmentResults as BaseAssessmentResults;

/**
 * This is the model class for table "assessment_results".
 */
class AssessmentResults extends BaseAssessmentResults
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['assessment_id', 'student_id', 'total_marks', 'marks_scored', 'start_time', 'end_time', 'last_attempt_question_id', 'test_completed', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['assessment_id', 'student_id', 'last_attempt_question_id', 'test_completed', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_marks', 'marks_scored'], 'number'],
            [['start_time', 'end_time', 'created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
