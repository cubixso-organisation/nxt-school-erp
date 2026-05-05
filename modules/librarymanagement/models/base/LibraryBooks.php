<?php


namespace app\modules\librarymanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "library_books".
 *
 * @property integer $id
 * @property string $book_title
 * @property string $description
 * @property integer $book_number
 * @property string $isbn_number
 * @property string $publisher
 * @property string $author
 * @property string $subject
 * @property integer $rack_number
 * @property integer $qty
 * @property integer $available
 * @property string $book_price
 * @property integer $campus_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\librarymanagement\models\IssueBooks[] $issueBooks
 * @property \app\modules\librarymanagement\models\LibraryRacks $rackNumber
 */
class LibraryBooks extends \yii\db\ActiveRecord
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
            'rackNumber',
            'campus'
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
            [['description'], 'string'],
            [['rack_number', 'qty', 'available', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['book_price'], 'number'],
            [['book_title', 'publisher', 'author', 'created_on', 'updated_on'], 'string', 'max' => 255],
            [['book_number', 'status', 'campus_id'], 'safe'],
            [['isbn_number'], 'string', 'max' => 20],
            [['subject'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library_books';
    }
    public static $statusOptions = [
        0 => 'Inactive',
        1 => 'Active',
        // Add more status options as needed
    ];

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Available',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Available</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Out of Stock</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
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
            'book_title' => Yii::t('app', 'Book Title'),
            'description' => Yii::t('app', 'Description'),
            'book_number' => Yii::t('app', 'Book Number'),
            'isbn_number' => Yii::t('app', 'Isbn Number'),
            'publisher' => Yii::t('app', 'Publisher'),
            'author' => Yii::t('app', 'Author'),
            'subject' => Yii::t('app', 'Subject'),
            'rack_number' => Yii::t('app', 'Rack Number'),
            'qty' => Yii::t('app', 'Qty'),
            'available' => Yii::t('app', 'Available'),
            'book_price' => Yii::t('app', 'Book Price'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'status' => Yii::t('app', 'Status'),
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
        return $this->hasMany(\app\modules\librarymanagement\models\IssueBooks::className(), ['serial_no' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRackNumber()
    {
        return $this->hasOne(\app\modules\librarymanagement\models\LibraryRacks::className(), ['id' => 'rack_number']);
    }
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
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
     * @return \app\modules\librarymanagement\models\LibraryBooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\librarymanagement\models\LibraryBooksQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['book_title'] =  $this->book_title;

        $data['description'] =  $this->description;

        $data['book_number'] =  $this->book_number;

        $data['isbn_number'] =  $this->isbn_number;

        $data['publisher'] =  $this->publisher;

        $data['author'] =  $this->author;

        $data['subject'] =  $this->subject;

        $data['rack_number'] =  $this->rack_number;

        $data['qty'] =  $this->qty;

        $data['available'] =  $this->available;

        $data['book_price'] =  $this->book_price;

        $data['campus_id'] =  $this->campus_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
