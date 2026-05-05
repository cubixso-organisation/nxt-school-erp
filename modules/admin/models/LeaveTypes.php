<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\LeaveTypes as BaseLeaveTypes;

/**
 * This is the model class for table "leave_types".
 */
class LeaveTypes extends BaseLeaveTypes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'title'], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ]);
    }
	

}
