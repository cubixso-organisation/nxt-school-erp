<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\GradeDefination as BaseGradeDefination;

/**
 * This is the model class for table "grade_defination".
 */
class GradeDefination extends BaseGradeDefination
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                // [['grade_id', 'section_id', 'campus_id', 'max_marks', 'min_marks', 'grade', 'cgpa', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
                // [['grade_id', 'section_id', 'campus_id', 'create_user_id', 'update_user_id'], 'integer'],
                // [['max_marks', 'min_marks', 'grade', 'cgpa'], 'number'],
                // [['created_on', 'updated_on'], 'safe']
            ]
        );
    }
}
