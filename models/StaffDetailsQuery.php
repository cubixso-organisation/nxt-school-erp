<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\StaffDetails]].
 *
 * @see \app\models\StaffDetails
 */
class StaffDetailsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\StaffDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\StaffDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
