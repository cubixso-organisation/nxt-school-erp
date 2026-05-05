<?php

namespace app\modules\librarymanagement\models;

/**
 * This is the ActiveQuery class for [[LibraryBooks]].
 *
 * @see LibraryBooks
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
     * @return LibraryBooks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LibraryBooks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
