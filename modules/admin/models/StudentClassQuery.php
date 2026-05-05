<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentClass]].
 *
 * @see StudentClass
 */
class StudentClassQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentClass[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentClass|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
