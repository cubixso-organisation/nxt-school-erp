<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[ItemCategory]].
 *
 * @see ItemCategory
 */
class ItemCategoryQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ItemCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ItemCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
