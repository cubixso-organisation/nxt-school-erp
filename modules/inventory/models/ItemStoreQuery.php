<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[ItemStore]].
 *
 * @see ItemStore
 */
class ItemStoreQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ItemStore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ItemStore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
