<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\LibraryBooksLogs as BaseLibraryBooksLogs;

/**
 * This is the model class for table "library_books_logs".
 */
class LibraryBooksLogs extends BaseLibraryBooksLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['issue_books_id', 'library_book_id', 'library_school_wise_id', 'book_due_date', 'book_return_date', 'book_return_late_fine', 'created_on', 'updated_on', 'created_user_id', 'updated_user_id'], 'required'],
            [['issue_books_id', 'library_book_id', 'library_school_wise_id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['book_due_date', 'book_return_date', 'created_on', 'updated_on'], 'safe'],
            [['book_return_late_fine'], 'string', 'max' => 255]
        ]);
    }
	

}
