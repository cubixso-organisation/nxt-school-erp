<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[StaffAttendenceSettings]].
 *
 * @see StaffAttendenceSettings
 */
class StaffAttendenceSettingsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffAttendenceSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffAttendenceSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
