<?php

namespace app\modules\hostelmanagement\models;

/**
 * This is the ActiveQuery class for [[Floor]].
 *
 * @see Floor
 */
class FloorQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Floor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Floor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
