<?php

namespace app\modules\hostelmanagement\models;

use Yii;
use \app\modules\hostelmanagement\models\base\WardenAttandance as BaseWardenAttandance;

/**
 * This is the model class for table "warden_attandance".
 */
class WardenAttandance extends BaseWardenAttandance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'hostel_id', 'warden_id', 'date', 'attandance_by', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'hostel_id', 'warden_id', 'attandance', 'attandance_by', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
