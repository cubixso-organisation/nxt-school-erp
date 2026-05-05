<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "teacher_attenddence".
 *
 * @property integer $id
 * @property integer $teacher_details_id
 * @property string $teacher_present_date_and_time
 * @property string $date
 * @property double $lat
 * @property double $lng
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $updated_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updatedUser
 */
class TeacherAttenddence extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'teacherDetails',
            'createUser',
            'updatedUser'
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
            [['teacher_details_id', 'teacher_present_date_and_time', 'date', 'lat', 'lng', 'checkout_lat', 'checkout_lng'], 'required'],
            [['teacher_details_id', 'status', 'create_user_id', 'updated_user_id'], 'integer'],
            [['teacher_present_date_and_time', 'checkout_date_time','date', 'created_on', 'updated_on'], 'safe'],
            [['lat', 'lng','checkout_lat', 'checkout_lng'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_attenddence';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Absent',
            self::STATUS_ACTIVE => 'Present',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Present</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Absent</span>';
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
            'teacher_details_id' => Yii::t('app', 'Teacher Details ID'),
            'teacher_present_date_and_time' => Yii::t('app', 'Teacher Present Date And Time'),
            'date' => Yii::t('app', 'Date'),
            'lat' => Yii::t('app', 'Checkin-Lat'),
            'lng' => Yii::t('app', 'Checkin-Lng'),
            'checkout_date_time' => Yii::t('app', 'Teacher Checkout Date And Time'),
            'checkout_lat' => Yii::t('app', 'CheckOut-Lat'),
            'checkout_lng' => Yii::t('app', 'CheckOut-Lang'),
            'status' => Yii::t('app', 'Status'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'updated_user_id']);
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
                'value' => date('Y-m-d H:i:s'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\admin\models\TeacherAttenddenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TeacherAttenddenceQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['teacher_details_id'] =  $this->teacher_details_id;
        
                $data['teacher_present_date_and_time'] =  $this->teacher_present_date_and_time;
        
                $data['date'] =  $this->date;
        
                $data['lat'] =  $this->lat;
        
                $data['lng'] =  $this->lng;

                $data['checkout_date_time'] =  $this->checkout_date_time;

                $data['checkout_lat'] =  $this->checkout_lat;
                
                $data['checkout_lng'] =  $this->checkout_lng;
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['updated_user_id'] =  $this->updated_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}


}


