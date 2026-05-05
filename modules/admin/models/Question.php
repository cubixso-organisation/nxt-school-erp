<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Question as BaseQuestion;

/**
 * This is the model class for table "question".
 */
class Question extends BaseQuestion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['id', 'assessment_id', 'campus_id', 'question_text', 'marks', 'status'], 'required'],
            // [['id', 'assessment_id', 'campus_id', 'type', 'marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['question_text'], 'string'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
