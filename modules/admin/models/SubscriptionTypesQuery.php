<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[SubscriptionTypes]].
 *
 * @see SubscriptionTypes
 */
class SubscriptionTypesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SubscriptionTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubscriptionTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
