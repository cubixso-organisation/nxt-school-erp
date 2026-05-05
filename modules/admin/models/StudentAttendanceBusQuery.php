<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentAttendanceBus]].
 *
 * @see StudentAttendanceBus
 */
class StudentAttendanceBusQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentAttendanceBus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentAttendanceBus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
