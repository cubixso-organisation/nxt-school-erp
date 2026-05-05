<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TeacherHasStudents as BaseTeacherHasStudents;

/**
 * This is the model class for table "teacher_has_students".
 */
class TeacherHasStudents extends BaseTeacherHasStudents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['teacher_id', 'student_id'], 'required'],
            [['teacher_id', 'student_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
