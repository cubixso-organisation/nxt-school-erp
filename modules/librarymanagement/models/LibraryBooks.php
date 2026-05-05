<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\LibraryBooks as BaseLibraryBooks;

/**
 * This is the model class for table "library_books".
 */
class LibraryBooks extends BaseLibraryBooks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['description'], 'string'],
            [['rack_number', 'qty', 'available', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['book_price'], 'number'],
            [['book_title', 'publisher', 'author', 'created_on', 'updated_on'], 'string', 'max' => 255],
            [['book_number', 'campus_id'], 'safe'],
            [['isbn_number'], 'string', 'max' => 20],
            [['subject'], 'string', 'max' => 50]
        ]);
    }
	

}
