<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ParentHasCampus]].
 *
 * @see ParentHasCampus
 */
class ParentHasCampusQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ParentHasCampus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ParentHasCampus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
