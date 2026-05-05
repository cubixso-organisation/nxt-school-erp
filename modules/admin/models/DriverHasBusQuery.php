<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[DriverHasBus]].
 *
 * @see DriverHasBus
 */
class DriverHasBusQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return DriverHasBus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DriverHasBus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
