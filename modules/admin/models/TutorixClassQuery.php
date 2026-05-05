<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TutorixClass]].
 *
 * @see TutorixClass
 */
class TutorixClassQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TutorixClass[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TutorixClass|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
