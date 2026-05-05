<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[DaycareAttendance]].
 *
 * @see DaycareAttendance
 */
class DaycareAttendanceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return DaycareAttendance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DaycareAttendance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
