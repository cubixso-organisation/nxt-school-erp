<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[SalaryGroupToStaff]].
 *
 * @see SalaryGroupToStaff
 */
class SalaryGroupToStaffQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SalaryGroupToStaff[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalaryGroupToStaff|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
