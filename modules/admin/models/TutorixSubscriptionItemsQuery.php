<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TutorixSubscriptionItems]].
 *
 * @see TutorixSubscriptionItems
 */
class TutorixSubscriptionItemsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TutorixSubscriptionItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TutorixSubscriptionItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
