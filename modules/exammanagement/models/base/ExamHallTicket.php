<?php


namespace app\modules\exammanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "exam_hall_ticket".
 *
 * @property integer $id
 * @property integer $academic_year_id
 * @property integer $campus_id
 * @property integer $student_detail_id
 * @property string $hall_ticket_pdf
 * @property integer $admission_no
 * @property integer $status
 * @property integer $created_user_id
 * @property integer $updated_user_id
 * @property string $created_on
 * @property string $updated_on
 */
class ExamHallTicket extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            ''
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
            [['academic_year_id', 'campus_id', 'student_detail_id','student_user_id', 'admission_no', 'created_user_id', 'updated_user_id'], 'integer'],
            [['campus_id', 'student_detail_id', 'hall_ticket_pdf', 'admission_no', 'created_user_id', 'updated_user_id'], 'required'],
            [['created_on', 'updated_on','student_user_id'], 'safe'],
            [['hall_ticket_pdf'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_hall_ticket';
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
            'academic_year_id' => Yii::t('app', 'Academic Year ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'student_detail_id' => Yii::t('app', 'Student Detail ID'),
            'hall_ticket_pdf' => Yii::t('app', 'Hall Ticket Pdf'),
            'admission_no' => Yii::t('app', 'Admission No'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
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
     * @return \app\modules\exammanagement\models\ExamHallTicketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\ExamHallTicketQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['student_detail_id'] =  $this->student_detail_id;
        
                $data['hall_ticket_pdf'] =  $this->hall_ticket_pdf;
        
                $data['admission_no'] =  $this->admission_no;

                $data['student_user_id'] =  $this->student_user_id;
        
                $data['created_user_id'] =  $this->created_user_id;
        
                $data['updated_user_id'] =  $this->updated_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}


}


