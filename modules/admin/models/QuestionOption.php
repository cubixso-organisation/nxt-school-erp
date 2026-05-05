<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\QuestionOption as BaseQuestionOption;

/**
 * This is the model class for table "question_option".
 */
class QuestionOption extends BaseQuestionOption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['id', 'question_id', 'option_text', 'campus_id', 'status'], 'required'],
            // [['id', 'question_id', 'campus_id', 'is_correct', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['option_text'], 'string'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
