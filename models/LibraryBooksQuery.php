<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\LibraryBooks]].
 *
 * @see \app\models\LibraryBooks
 */
class LibraryBooksQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\LibraryBooks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LibraryBooks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
