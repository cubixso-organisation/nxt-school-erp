<?php

namespace app\modules\inventory\models;

/**
 * This is the ActiveQuery class for [[IssueReturnInventory]].
 *
 * @see IssueReturnInventory
 */
class IssueReturnInventoryQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return IssueReturnInventory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return IssueReturnInventory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
