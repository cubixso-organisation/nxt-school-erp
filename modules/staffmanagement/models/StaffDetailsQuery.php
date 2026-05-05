<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[StaffDetails]].
 *
 * @see StaffDetails
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
     * @return StaffDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
