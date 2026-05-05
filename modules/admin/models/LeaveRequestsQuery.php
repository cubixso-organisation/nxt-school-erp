<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[LeaveRequests]].
 *
 * @see LeaveRequests
 */
class LeaveRequestsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return LeaveRequests[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LeaveRequests|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
