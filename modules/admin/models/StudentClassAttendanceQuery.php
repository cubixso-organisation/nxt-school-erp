<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentClassAttendance]].
 *
 * @see StudentClassAttendance
 */
class StudentClassAttendanceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentClassAttendance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentClassAttendance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
