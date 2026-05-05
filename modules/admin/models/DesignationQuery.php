<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Designation]].
 *
 * @see Designation
 */
class DesignationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Designation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Designation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
