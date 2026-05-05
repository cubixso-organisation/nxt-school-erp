<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\FinalMarksheet as BaseFinalMarksheet;

/**
 * This is the model class for table "final_marksheet".
 */
class FinalMarksheet extends BaseFinalMarksheet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['student_id', 'student_user_id', 'class_id', 'section_id', 'campus_id', 'session_id', 'marksheet_url', 'status', 'create_user_id', 'update_user_id', 'created_on', 'updated_on'], 'required'],
            // [['student_id', 'student_user_id', 'class_id', 'section_id', 'campus_id', 'session_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['marksheet_url'], 'string'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
