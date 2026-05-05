<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TestUsers]].
 *
 * @see TestUsers
 */
class TestUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TestUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TestUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
