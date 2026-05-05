<?php

namespace app\modules\hostelmanagement\models;

/**
 * This is the ActiveQuery class for [[HostellersAttandance]].
 *
 * @see HostellersAttandance
 */
class HostellersAttandanceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return HostellersAttandance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return HostellersAttandance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
