<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TutorixSubscriptions]].
 *
 * @see TutorixSubscriptions
 */
class TutorixSubscriptionsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TutorixSubscriptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TutorixSubscriptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
