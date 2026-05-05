<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[StaffDesignations]].
 *
 * @see StaffDesignations
 */
class StaffDesignationsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StaffDesignations[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StaffDesignations|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
