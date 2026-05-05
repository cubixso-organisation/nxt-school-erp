<?php

namespace app\modules\hostelmanagement\models;

/**
 * This is the ActiveQuery class for [[WardenToHostel]].
 *
 * @see WardenToHostel
 */
class WardenToHostelQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return WardenToHostel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WardenToHostel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
