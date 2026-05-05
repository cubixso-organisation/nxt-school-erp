<?php

namespace app\modules\hostelmanagement\models;

/**
 * This is the ActiveQuery class for [[WardenAttandance]].
 *
 * @see WardenAttandance
 */
class WardenAttandanceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return WardenAttandance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WardenAttandance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
