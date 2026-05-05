<?php


namespace app\modules\librarymanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "library_books_logs".
 *
 * @property integer $id
 * @property integer $issue_books_id
 * @property integer $library_book_id
 * @property integer $library_school_wise_id
 * @property string $book_due_date
 * @property string $book_return_date
 * @property string $book_return_late_fine
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\librarymanagement\models\IssueBooks $issueBooks
 * @property \app\modules\librarymanagement\models\LibraryBooks $libraryBook
 * @property \app\modules\librarymanagement\models\LibrarySchoolsWise $librarySchoolWise
 */
class LibraryBooksLogs extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'issueBooks',
            'libraryBook',
            'librarySchoolWise'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_books_id', 'library_book_id', 'library_school_wise_id', 'book_due_date', 'book_return_date', 'book_return_late_fine', 'created_on', 'updated_on', 'created_user_id', 'updated_user_id'], 'required'],
            [['issue_books_id', 'library_book_id', 'library_school_wise_id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['book_due_date', 'book_return_date', 'created_on', 'updated_on'], 'safe'],
            [['book_return_late_fine'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library_books_logs';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
        }elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }

    }

    public function getFeatureOptions()
    {
        return [

            self::IS_FEATURED => 'Is Featured',
            self::IS_NOT_FEATURED => 'Not Featured',
           
        ];
    }

    public function getFeatureOptionsBadges()
    {
        if ($this->is_featured == self::IS_FEATURED) {
            return '<span class="badge badge-success">Featured</span>';
        } elseif ($this->is_featured == self::IS_NOT_FEATURED) {
            return '<span class="badge badge-danger">Not Featured</span>';
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'issue_books_id' => Yii::t('app', 'Issue Books ID'),
            'library_book_id' => Yii::t('app', 'Library Book ID'),
            'library_school_wise_id' => Yii::t('app', 'Library School Wise ID'),
            'book_due_date' => Yii::t('app', 'Book Due Date'),
            'book_return_date' => Yii::t('app', 'Book Return Date'),
            'book_return_late_fine' => Yii::t('app', 'Book Return Late Fine'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueBooks()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\IssueBooks::className(), ['id' => 'issue_books_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibraryBook()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryBooks::className(), ['id' => 'library_book_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibrarySchoolWise()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibrarySchoolsWise::className(), ['id' => 'library_school_wise_id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_on',
                'updatedAtAttribute' => 'updated_on',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\librarymanagement\models\LibraryBooksLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\librarymanagement\models\LibraryBooksLogsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['issue_books_id'] =  $this->issue_books_id;
        
                $data['library_book_id'] =  $this->library_book_id;
        
                $data['library_school_wise_id'] =  $this->library_school_wise_id;
        
                $data['book_due_date'] =  $this->book_due_date;
        
                $data['book_return_date'] =  $this->book_return_date;
        
                $data['book_return_late_fine'] =  $this->book_return_late_fine;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['created_user_id'] =  $this->created_user_id;
        
                $data['updated_user_id'] =  $this->updated_user_id;
        
            return $data;
}


}


