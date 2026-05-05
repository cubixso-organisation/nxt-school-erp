<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentHasDairy]].
 *
 * @see StudentHasDairy
 */
class StudentHasDairyQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentHasDairy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentHasDairy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
