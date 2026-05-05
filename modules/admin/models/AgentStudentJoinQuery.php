<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AgentStudentJoin]].
 *
 * @see AgentStudentJoin
 */
class AgentStudentJoinQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AgentStudentJoin[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AgentStudentJoin|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
