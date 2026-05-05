<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\EventNotificationSettings as BaseEventNotificationSettings;

/**
 * This is the model class for table "event_notification_settings".
 */
class EventNotificationSettings extends BaseEventNotificationSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['send_notification', 'campus_id', 'status'], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['send_notification'], 'string', 'max' => 199]
        ]);
    }
	

}
