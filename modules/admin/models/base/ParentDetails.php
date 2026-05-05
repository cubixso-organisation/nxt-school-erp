<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "parent_details".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name_of_the_father
 * @property string $name_of_the_mother
 * @property string $current_address
 * @property string $permanent_address
 * @property string $contact_number
 * @property string $father_education_qualification
 * @property string $mother_education_qualification
 * @property string $father_aadhaar_number
 * @property string $mother_aadhaar_number
 * @property string $father_occupation
 * @property string $mother_occupation
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\ParentHasCampus[] $parentHasCampuses
 * @property \app\modules\admin\models\StudentDetails[] $studentDetails
 */
class ParentDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

 
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'user',
            'updateUser',
            'createUser',
            'parentHasCampuses',
            'studentDetails'
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
            [['user_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name_of_the_father', 'name_of_the_mother', 'contact_number', 'father_education_qualification', 'mother_education_qualification', 'father_aadhaar_number', 'mother_aadhaar_number'], 'required'],
            [['current_address', 'permanent_address', 'father_education_qualification', 'mother_education_qualification'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['name_of_the_father', 'name_of_the_mother', 'father_occupation', 'mother_occupation','blood_group_father','blood_group_mother','profile_image'], 'string', 'max' => 255],
            [['father_aadhaar_number', 'mother_aadhaar_number'], 'string', 'max' => 20],
            [['contact_number'], 'unique'],

            [['contact_number'], 'string', 'max' => 10],
            ['contact_number', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],



            [['father_aadhaar_number','mother_aadhaar_number'], 'string', 'max' => 12],
            ['father_aadhaar_number', 'match', 'pattern' => '/^[0-9]{12}$/'],
            ['mother_aadhaar_number', 'match', 'pattern' => '/^[0-9]{12}$/'],


        ];
    } 

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parent_details';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Inactive</span>';
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
    


    public static function getBalanceAmount($student_id,$class_id,$section_id,$pay_fees_id){
        
        $paid = (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id);

        $pay_fees = PayFees::find()->where(['id'=>$pay_fees_id])->one();
        
        $fee_structures_id = $pay_fees->fee_structures_id;

        $fee_structures = FeeStructures::find()->where(['id'=>$fee_structures_id])->one();
        $fees_cut = $pay_fees->fees_cut;
        $fee = $fee_structures->fee;
        $studentPayAmount = $fee-$fees_cut;
         $balance =  $studentPayAmount-$paid;
         return  $balance;
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name_of_the_father' => Yii::t('app', "Father's Name"),
            'name_of_the_mother' => Yii::t('app', "Mother's Name"),
            'current_address' => Yii::t('app', 'Current Address'),
            'permanent_address' => Yii::t('app', 'Permanent Address'),
            'contact_number' => Yii::t('app', 'Contact Number'),
            'father_education_qualification' => Yii::t('app', 'Father Education Qualification'),
            'mother_education_qualification' => Yii::t('app', 'Mother Education Qualification'),
            'father_aadhaar_number' => Yii::t('app', 'Father Aadhaar Number'),
            'mother_aadhaar_number' => Yii::t('app', 'Mother Aadhaar Number'),
            'father_occupation' => Yii::t('app', 'Father Occupation'),
            'mother_occupation' => Yii::t('app', 'Mother Occupation'),
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
    public function getUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'user_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
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
    public function getParentHasCampuses()
    {
        return $this->hasMany(\app\modules\admin\models\ParentHasCampus::className(), ['patient_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\StudentDetails::className(), ['parent_id' => 'id']);
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
                'value' =>date('Y-m-d H:i:s'),
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
     * @return \app\modules\admin\models\ParentDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\ParentDetailsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['user_id'] =  $this->user_id;

                $data['username'] =  !empty($this->user->username)?$this->user->username:'';

                $data['profile_image'] =$this->profile_image;

                $data['email'] = '';

             
                $data['name_of_the_father'] =  $this->name_of_the_father;
        
                $data['name_of_the_mother'] =  $this->name_of_the_mother;
        
                $data['current_address'] =  $this->current_address;
        
                $data['permanent_address'] =  $this->permanent_address;
        
                $data['contact_number'] =  $this->contact_number;

                $data['father_date_of_birth'] =  $this->father_date_of_birth;


                $data['mother_date_of_birth'] =  $this->mother_date_of_birth;

                $data['father_age'] =  '';

                $data['mother_age'] =  '';

 

                if(!empty($this->name_of_the_father)){
                    $data['blood_group'] =  '';

                }else{
                    $data['blood_group'] =  '';

                }



        
                $data['father_education_qualification'] =  $this->father_education_qualification;
        
                $data['mother_education_qualification'] =  $this->mother_education_qualification;
        
                $data['father_aadhaar_number'] =  $this->father_aadhaar_number;
        
                $data['mother_aadhaar_number'] =  $this->mother_aadhaar_number;
        
                $data['father_occupation'] =  $this->father_occupation;
        
                $data['mother_occupation'] =  $this->mother_occupation;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


