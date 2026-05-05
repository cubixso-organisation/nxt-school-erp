<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[StaffAttendence]].
 *
 * @see StaffAttendence
 */
class StaffAttendenceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffAttendence[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffAttendence|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
