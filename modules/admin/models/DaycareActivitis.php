<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\DaycareActivitis as BaseDaycareActivitis;

/**
 * This is the model class for table "daycare_activitis".
 */
class DaycareActivitis extends BaseDaycareActivitis
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'student_id', 'teacher_id', 'parent_id', 'activity', 'status'], 'required'],
            [['campus_id', 'student_id', 'teacher_id', 'parent_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['activity'], 'string', 'max' => 200]
        ]);
    }
	

}
