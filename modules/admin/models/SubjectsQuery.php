<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Subjects]].
 *
 * @see Subjects
 */
class SubjectsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Subjects[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Subjects|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
