<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\OnlineAssessment as BaseOnlineAssessment;

/**
 * This is the model class for table "online_assessment".
 */
class OnlineAssessment extends BaseOnlineAssessment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                // [['campus_id', 'subject_id', 'academic_year_id', 'section_id', 'title', 'duration', 'total_marks'], 'required'],
                // [['id', 'campus_id', 'subject_id', 'academic_year_id', 'section_id', 'duration', 'total_marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
                // [['created_on', 'updated_on'], 'safe'],
                // [['title'], 'string', 'max' => 199]
            ]
        );
    }


}
