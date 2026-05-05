<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\Grade as BaseGrade;

/**
 * This is the model class for table "grade".
 */
class Grade extends BaseGrade
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                // [['campus_id', 'section_id', 'maximum_exam_marks', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
                // [['campus_id', 'section_id', 'maximum_exam_marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
                // [['created_on', 'updated_on'], 'safe']
            ]
        );
    }
}
