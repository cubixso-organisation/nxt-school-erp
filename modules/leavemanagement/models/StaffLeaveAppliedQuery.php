<?php

namespace app\modules\leavemanagement\models;

/**
 * This is the ActiveQuery class for [[StaffLeaveApplied]].
 *
 * @see StaffLeaveApplied
 */
class StaffLeaveAppliedQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffLeaveApplied[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffLeaveApplied|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
