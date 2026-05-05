<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ExamsResult]].
 *
 * @see ExamsResult
 */
class ExamsResultQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ExamsResult[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExamsResult|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
