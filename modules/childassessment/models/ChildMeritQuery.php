<?php

namespace app\modules\childassessment\models;

/**
 * This is the ActiveQuery class for [[ChildMerit]].
 *
 * @see ChildMerit
 */
class ChildMeritQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ChildMerit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ChildMerit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
