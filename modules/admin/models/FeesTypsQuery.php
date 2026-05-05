<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[FeesTyps]].
 *
 * @see FeesTyps
 */
class FeesTypsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return FeesTyps[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FeesTyps|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
