<?php

namespace app\modules\hostelmanagement\models;

use Yii;
use \app\modules\hostelmanagement\models\base\Floor as BaseFloor;

/**
 * This is the model class for table "floor".
 */
class Floor extends BaseFloor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['hostel_id', 'campus_id', 'name_of_floor', 'no_of_rooms', 'status'], 'required'],
            // [['hostel_id', 'campus_id', 'no_of_rooms', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['name_of_floor'], 'string', 'max' => 255]
        ]);
    }
	

}
