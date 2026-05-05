<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\ClassRooms as BaseClassRooms;

/**
 * This is the model class for table "class_rooms".
 */
class ClassRooms extends BaseClassRooms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'class_room_title'], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['class_room_title'], 'string', 'max' => 255]
        ]);
    }
	

}
