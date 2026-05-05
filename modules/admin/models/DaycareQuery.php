<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Daycare]].
 *
 * @see Daycare
 */
class DaycareQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Daycare[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Daycare|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
