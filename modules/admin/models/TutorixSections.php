<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixSections as BaseTutorixSections;

/**
 * This is the model class for table "tutorix_sections".
 */
class TutorixSections extends BaseTutorixSections
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['class_id', 'subject_id', 'section_id', 'name', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['class_id', 'subject_id', 'section_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
