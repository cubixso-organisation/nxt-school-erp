<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[StaffSalary]].
 *
 * @see StaffSalary
 */
class StaffSalaryQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffSalary[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffSalary|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
