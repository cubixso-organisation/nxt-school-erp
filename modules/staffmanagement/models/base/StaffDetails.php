<?php


namespace app\modules\staffmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "staff_details".
 *
 * @property integer $id
 * @property string $name
 * @property integer $campus_id
 * @property integer $designation_id
 * @property integer $payroll_id
 * @property string $contact_no
 * @property string $date_of_birth
 * @property string $gender
 * @property string $email
 * @property string $aadhar_card
 * @property string $pan_card
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\staffmanagement\models\Campus $campus
 * @property \app\modules\staffmanagement\models\StaffDesignations $designation
 */
class StaffDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'campus',
            'designation'
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
            [['name', 'campus_id', 'designation_id', 'contact_no', 'date_of_birth', 'gender', 'email', 'aadhar_card', 'pan_card', 'status', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'designation_id', 'payroll_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date_of_birth', 'created_on', 'updated_on'], 'safe'],
            [['name', 'contact_no', 'aadhar_card', 'pan_card'], 'string', 'max' => 255],
            [['gender', 'email'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_details';
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
            'name' => Yii::t('app', 'Name'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'designation_id' => Yii::t('app', 'Designation'),
            'payroll_id' => Yii::t('app', 'Payroll'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'email' => Yii::t('app', 'Email'),
            'aadhar_card' => Yii::t('app', 'Aadhar Card'),
            'pan_card' => Yii::t('app', 'Pan Card'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\StaffDesignations::className(), ['id' => 'designation_id']);
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
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\staffmanagement\models\StaffDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\staffmanagement\models\StaffDetailsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['name'] =  $this->name;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['designation_id'] =  $this->designation_id;
        
                $data['payroll_id'] =  $this->payroll_id;
        
                $data['contact_no'] =  $this->contact_no;
        
                $data['date_of_birth'] =  $this->date_of_birth;
        
                $data['gender'] =  $this->gender;
        
                $data['email'] =  $this->email;
        
                $data['aadhar_card'] =  $this->aadhar_card;
        
                $data['pan_card'] =  $this->pan_card;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


