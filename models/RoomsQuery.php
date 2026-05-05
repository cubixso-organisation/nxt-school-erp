<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\Rooms]].
 *
 * @see \app\models\Rooms
 */
class RoomsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\Rooms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Rooms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
