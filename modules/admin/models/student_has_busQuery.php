<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[student_has_bus]].
 *
 * @see student_has_bus
 */
class student_has_busQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return student_has_bus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return student_has_bus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
