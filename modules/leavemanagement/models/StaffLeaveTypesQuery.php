<?php

namespace app\modules\leavemanagement\models;

/**
 * This is the ActiveQuery class for [[StaffLeaveTypes]].
 *
 * @see StaffLeaveTypes
 */
class StaffLeaveTypesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffLeaveTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffLeaveTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
