<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[InventoryItems]].
 *
 * @see InventoryItems
 */
class InventoryItemsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return InventoryItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InventoryItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
