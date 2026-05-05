<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ActivationModules]].
 *
 * @see ActivationModules
 */
class ActivationModulesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ActivationModules[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ActivationModules|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
