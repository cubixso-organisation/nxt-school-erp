<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\ParentHasCampus as BaseParentHasCampus;

/**
 * This is the model class for table "parent_has_campus".
 */
class ParentHasCampus extends BaseParentHasCampus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['patient_id', 'campus_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['patient_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
