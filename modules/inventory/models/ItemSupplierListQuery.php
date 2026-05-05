<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[ItemSupplierList]].
 *
 * @see ItemSupplierList
 */
class ItemSupplierListQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ItemSupplierList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ItemSupplierList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
