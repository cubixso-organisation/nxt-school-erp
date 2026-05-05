<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[BusRoute]].
 *
 * @see BusRoute
 */
class BusRouteQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return BusRoute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BusRoute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
