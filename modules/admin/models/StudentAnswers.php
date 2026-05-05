<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\StudentAnswers as BaseStudentAnswers;

/**
 * This is the model class for table "student_answers".
 */
class StudentAnswers extends BaseStudentAnswers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'question_id', 'status'], 'required'],
            [['id', 'question_id', 'selected_option_id', 'marks_awarded', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['answer_text'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
