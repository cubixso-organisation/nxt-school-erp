<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixLectures as BaseTutorixLectures;

/**
 * This is the model class for table "tutorix_lectures".
 */
class TutorixLectures extends BaseTutorixLectures
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['class_id', 'subject_id', 'section_id', 'lecture_id', 'name', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['class_id', 'subject_id', 'section_id', 'lecture_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
