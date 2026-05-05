<?php

namespace app\modules\librarymanagement\models;

/**
 * This is the ActiveQuery class for [[LibraryBooksLogs]].
 *
 * @see LibraryBooksLogs
 */
class LibraryBooksLogsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return LibraryBooksLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LibraryBooksLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
