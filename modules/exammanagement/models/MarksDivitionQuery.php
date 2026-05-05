<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[MarksDivition]].
 *
 * @see MarksDivition
 */
class MarksDivitionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return MarksDivition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MarksDivition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
