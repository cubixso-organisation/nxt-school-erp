<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Institutes]].
 *
 * @see Institutes
 */
class InstitutesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Institutes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Institutes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
