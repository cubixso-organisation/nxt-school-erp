<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\EventClasses as BaseEventClasses;

/**
 * This is the model class for table "event_classes".
 */
class EventClasses extends BaseEventClasses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'event_id', 'section_id', 'campus_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'event_id', 'section_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
