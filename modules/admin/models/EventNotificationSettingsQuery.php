<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[EventNotificationSettings]].
 *
 * @see EventNotificationSettings
 */
class EventNotificationSettingsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return EventNotificationSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EventNotificationSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
