<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\StaffAttendenceSettings]].
 *
 * @see \app\models\StaffAttendenceSettings
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
     * @return \app\models\StaffAttendenceSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\StaffAttendenceSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
