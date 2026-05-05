<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[SalaryGroupComponents]].
 *
 * @see SalaryGroupComponents
 */
class SalaryGroupComponentsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SalaryGroupComponents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalaryGroupComponents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
