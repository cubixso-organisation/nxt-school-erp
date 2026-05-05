<?php

namespace app\modules\childassessment\models;

use Yii;
use \app\modules\childassessment\models\base\ChildMerit as BaseChildMerit;

/**
 * This is the model class for table "child_merit".
 */
class ChildMerit extends BaseChildMerit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                [['campus_id', 'name', 'description', 'max_marks', 'status'], 'required'],
                [['campus_id', 'max_marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
                [['created_on', 'updated_on', 'created_on', 'updated_on', 'created_user_id', 'updated_user_id'], 'safe'],
                [['name', 'description'], 'string', 'max' => 255]
            ]
        );
    }
}
