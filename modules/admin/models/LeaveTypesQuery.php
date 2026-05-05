<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[LeaveTypes]].
 *
 * @see LeaveTypes
 */
class LeaveTypesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return LeaveTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LeaveTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
