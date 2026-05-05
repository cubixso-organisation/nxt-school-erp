<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[Grade]].
 *
 * @see Grade
 */
class GradeQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Grade[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Grade|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
