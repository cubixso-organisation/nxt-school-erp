<?php

namespace app\modules\hostelmanagement\models;

use Yii;
use \app\modules\hostelmanagement\models\base\HostellersAttandance as BaseHostellersAttandance;

/**
 * This is the model class for table "hostellers_attandance".
 */
class HostellersAttandance extends BaseHostellersAttandance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'hostel_id', 'student_id', 'room_id', 'date', 'attandance_by', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'hostel_id', 'student_id', 'room_id', 'attandance', 'attandance_by', 'status', 'create_user_id', 'update_user_id','attendance_count_perday'], 'integer'],
            [['date', 'created_on', 'updated_on','attendance_count_perday'], 'safe']
        ]);
    }
	

}
