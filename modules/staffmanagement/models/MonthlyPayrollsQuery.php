<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[MonthlyPayrolls]].
 *
 * @see MonthlyPayrolls
 */
class MonthlyPayrollsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return MonthlyPayrolls[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MonthlyPayrolls|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
