<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[Permissions]].
 *
 * @see Permissions
 */
class PermissionsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Permissions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Permissions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
