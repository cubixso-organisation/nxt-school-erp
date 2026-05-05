<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentHasBus]].
 *
 * @see StudentHasBus
 */
class StudentHasBusQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentHasBus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentHasBus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
