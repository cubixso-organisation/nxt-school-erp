<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[QuestionOption]].
 *
 * @see QuestionOption
 */
class QuestionOptionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return QuestionOption[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return QuestionOption|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
