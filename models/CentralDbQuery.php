<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CentralDb]].
 *
 * @see CentralDb
 */
class CentralDbQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return CentralDb[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CentralDb|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
