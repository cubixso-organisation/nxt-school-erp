<?php

namespace app\modules\hostelmanagement\models;

/**
 * This is the ActiveQuery class for [[HostlerAttendanceSettings]].
 *
 * @see HostlerAttendanceSettings
 */
class HostlerAttendanceSettingsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return HostlerAttendanceSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return HostlerAttendanceSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
