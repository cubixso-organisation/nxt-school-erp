<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\DaycareTeachers as BaseDaycareTeachers;

/**
 * This is the model class for table "daycare_teachers".
 */
class DaycareTeachers extends BaseDaycareTeachers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['daycare_id', 'campus_id', 'teacher_id', 'status'], 'required'],
            [['daycare_id', 'campus_id', 'teacher_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	

}
