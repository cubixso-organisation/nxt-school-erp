<?php

namespace app\modules\hostelmanagement\models;

use Yii;
use \app\modules\hostelmanagement\models\base\WardenToHostel as BaseWardenToHostel;

/**
 * This is the model class for table "warden_to_hostel".
 */
class WardenToHostel extends BaseWardenToHostel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'hostel_id', 'warden_id', 'floor_id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['status'], 'string', 'max' => 255]
        ]);
    }
	

}
