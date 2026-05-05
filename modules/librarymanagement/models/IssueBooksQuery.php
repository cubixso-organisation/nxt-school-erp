<?php

namespace app\modules\librarymanagement\models;

/**
 * This is the ActiveQuery class for [[IssueBooks]].
 *
 * @see IssueBooks
 */
class IssueBooksQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return IssueBooks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return IssueBooks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
