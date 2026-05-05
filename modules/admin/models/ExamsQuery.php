<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Exams]].
 *
 * @see Exams
 */
class ExamsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Exams[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Exams|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
