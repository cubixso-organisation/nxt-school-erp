<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[EmployeeDetails]].
 *
 * @see EmployeeDetails
 */
class EmployeeDetailsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return EmployeeDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EmployeeDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
