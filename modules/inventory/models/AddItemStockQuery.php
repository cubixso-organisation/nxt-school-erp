<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[AddItemStock]].
 *
 * @see AddItemStock
 */
class AddItemStockQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AddItemStock[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AddItemStock|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
