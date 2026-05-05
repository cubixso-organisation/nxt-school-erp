<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ClassRooms]].
 *
 * @see ClassRooms
 */
class ClassRoomsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ClassRooms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClassRooms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
