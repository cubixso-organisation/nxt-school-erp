<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[EventClasses]].
 *
 * @see EventClasses
 */
class EventClassesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return EventClasses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EventClasses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
