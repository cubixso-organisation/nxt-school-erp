<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\TeacherClassAndSubjects as BaseTeacherClassAndSubjects;

/**
 * This is the model class for table "teacher_class_and_subjects".
 */
class TeacherClassAndSubjects extends BaseTeacherClassAndSubjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'teacher_detail_id', 'teacher_user_id', 'section_id', 'subject_id', 'status', 'created_on', 'updated_on', 'update_user_id', 'create_user_id'], 'required'],
            // [['campus_id', 'teacher_detail_id', 'teacher_user_id', 'section_id', 'subject_id', 'status', 'update_user_id', 'create_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
