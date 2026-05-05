<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;
/**
 * This is the base model class for table "admission_enquirie".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $student_name
 * @property string $parent_name
 * @property integer $contact_no
 * @property string $next_class
 * @property string $previous_class
 * @property string $dob
 * @property string $address
 * @property string $email
 * @property string $message
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 */
class AdmissionEnquirie extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'campus'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;
    const STATUS_ACCEPTED = 3;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_name', 'parent_name', 'contact_no', 'next_class', 'dob', 'status','email'], 'required'],
            [['campus_id', 'contact_no', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['dob', 'created_on', 'updated_on'], 'safe'],
            [['address','message'], 'string'],
            [['student_name', 'next_class', 'previous_class','email'], 'string', 'max' => 199],
            [['parent_name'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admission_enquirie';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Contacted',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',
            self::STATUS_ACCEPTED => 'Completed',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Contacted</span>';
        }elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }elseif ($this->status == self::STATUS_ACCEPTED) {
            return '<span class="badge badge-danger">Completed</span>';
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'student_name' => Yii::t('app', 'Student Name'),
            'parent_name' => Yii::t('app', 'Parent Name'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'next_class' => Yii::t('app', 'Next Class'),
            'previous_class' => Yii::t('app', 'Previous Class'),
            'dob' => Yii::t('app', 'Dob'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
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
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\admin\models\AdmissionEnquirieQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\AdmissionEnquirieQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['student_name'] =  $this->student_name;
        
                $data['parent_name'] =  $this->parent_name;
        
                $data['contact_no'] =  $this->contact_no;
        
                $data['next_class'] =  $this->next_class;
        
                $data['previous_class'] =  $this->previous_class;
        
                $data['dob'] =  $this->dob;
        
                $data['address'] =  $this->address;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


