<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentDetails]].
 *
 * @see StudentDetails
 */
class StudentDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return StudentDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
