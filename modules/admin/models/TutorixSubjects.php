<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixSubjects as BaseTutorixSubjects;

/**
 * This is the model class for table "tutorix_subjects".
 */
class TutorixSubjects extends BaseTutorixSubjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['class_id', 'subject_id', 'name', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['class_id', 'subject_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name'], 'string', 'max' => 50]
        ]);
    }
	

}
