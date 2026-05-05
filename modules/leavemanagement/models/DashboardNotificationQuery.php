<?php

namespace app\modules\leavemanagement\models;

/**
 * This is the ActiveQuery class for [[DashboardNotification]].
 *
 * @see DashboardNotification
 */
class DashboardNotificationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return DashboardNotification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DashboardNotification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
