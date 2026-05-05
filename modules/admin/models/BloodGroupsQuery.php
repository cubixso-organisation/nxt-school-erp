<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[BloodGroups]].
 *
 * @see BloodGroups
 */
class BloodGroupsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return BloodGroups[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BloodGroups|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
