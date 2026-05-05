<?php


namespace app\modules\librarymanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "issue_books".
 *
 * @property integer $id
 * @property string $library_id
 * @property integer $book_id
 * @property integer $author
 * @property integer $subject_code
 * @property integer $serial_no
 * @property string $due_date
 * @property string $note
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\librarymanagement\models\LibraryBooks $book
 * @property \app\modules\librarymanagement\models\LibraryBooks $author0
 * @property \app\modules\librarymanagement\models\LibraryBooks $subjectCode
 * @property \app\modules\librarymanagement\models\LibraryBooks $serialNo
 */
class IssueBooks extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'book',
            'author0',
            'subjectCode',
            'serialNo',
            'libraryMember'
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
            [['library_id', 'library_member_id', 'book_id', 'author', 'subject_code', 'serial_no', 'issued_date', 'due_date','status'], 'required'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['book_id', 'library_member_id', 'author', 'subject_code', 'serial_no', 'due_date', 'issued_date','returned_date', 'updated_on','librarian_user_id'], 'safe'],
            [['library_id'], 'string', 'max' => 20],
            [['note', 'created_on'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue_books';
    }



    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Issued',
            // self::STATUS_INACTIVE => 'In Active',
            self::STATUS_DELETE => 'Returned',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Issued</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-default">Returned</span>';
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
    public function getReturnDate()
    {

        if ($this->status == self::STATUS_DELETE) {
            $timestamp = strtotime($this->updated_on);

            // Format the timestamp using Yii2's formatter
            $formattedDate = Yii::$app->formatter->asDate($timestamp, 'php:Y-m-d');
            return $formattedDate;
        } else {
            return ('Not Returned');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'library_id' => Yii::t('app', 'Library Card No'),
            'library_member_id' => Yii::t('app', 'Member ID'),
            'book_id' => Yii::t('app', 'Book'),
            'status' => Yii::t('app', 'Issue Status'),
            'author' => Yii::t('app', 'Author'),
            'subject_code' => Yii::t('app', 'Subject'),
            'serial_no' => Yii::t('app', 'Book Serial No'),
            'issued_date' => Yii::t('app', 'Book Issue Date'),
            'due_date' => Yii::t('app', 'Book Due Date'),
            'librarian_user_id' => Yii::t('app', 'Issued By'),
            'returned_date' => Yii::t('app', 'Book Return Date'),
            'note' => Yii::t('app', 'Note'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryBooks::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryBooks::className(), ['id' => 'author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectCode()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryBooks::className(), ['id' => 'subject_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSerialNo()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryBooks::className(), ['id' => 'serial_no']);
    }
    public function getLibraryMember()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryMembers::className(), ['id' => 'library_member_id']);
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
     * @return \app\modules\librarymanagement\models\IssueBooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\librarymanagement\models\IssueBooksQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['library_id'] =  $this->library_id;

        $data['book_id'] =  $this->book_id;

        $data['book_title'] =  $this->book->book_title;

        $data['status'] =  $this->status;

        $data['author'] =  $this->author;

        $data['subject_code'] =  $this->subject_code;

        $data['serial_no'] =  $this->serial_no;

        $data['issued_date'] =  $this->issued_date;

        $data['expected_due_date'] =  $this->due_date;

        $data['Book_return_date'] =  $this->returned_date??'';

        $data['note'] =  $this->note;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
