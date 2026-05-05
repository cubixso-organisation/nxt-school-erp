<?php

namespace app\modules\librarymanagement\models;

use Yii;
use \app\modules\librarymanagement\models\base\IssueBooks as BaseIssueBooks;

/**
 * This is the model class for table "issue_books".
 */
class IssueBooks extends BaseIssueBooks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['library_id', 'library_member_id','book_id', 'author', 'subject_code', 'serial_no','issued_date','due_date','status'], 'required'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['book_id', 'library_member_id','author', 'subject_code', 'serial_no','issued_date','due_date','returned_date', 'updated_on','librarian_user_id'], 'safe'],
            [['library_id'], 'string', 'max' => 20],
            [['note', 'created_on'], 'string', 'max' => 255]
        ]);
    }
	

}
