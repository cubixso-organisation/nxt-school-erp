<?php

namespace app\modules\admin\models\base;

use app\models\User;
use app\modules\admin\models\Campus;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "employee_details".
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $campus_id
 * @property integer $designation_id
 * @property string $employ_name
 * @property string $profile_picture
 * @property string $employee_id
 * @property integer $age
 * @property string $gender
 * @property integer $blood_group_id
 * @property string $phone_number
 * @property string $email
 * @property string $license_number
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\BusStatus[] $busStatuses
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\Designation $designation
 * @property \app\modules\admin\models\BloodGroups $bloodGroup
 */
class EmployeeDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $role;
    public $bus_id;
    public $is_driver;
    public $is_agent;
    public $admissions;

 
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'busStatuses',
            'user',
            'updateUser',
            'createUser',
            'campus',
            'designation',
            'bloodGroup'
        ];
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;



    public const IS_MALE  = 'Male';
    public const IS_FEMALE = 'Female';


    public const show_parent_number_yes =1;
    public const show_parent_number_no =0;

    public const ROLE_BUS_COORDINATOR = 'BusCoOrdinator';
    public const ROLE_AGENT = 'Agent';
    public const ROLE_ACCOUNTANT = 'Accountant';
    public const ROLE_BUS_DRIVER= 'BusDriver';
    public const ROLE_MANAGEMENT = 'Management';
    public const ROLE_CAMPUS_ADMIN = 'CampusAdmin';

    public const agent_type_manual_payment=1;
    public const agent_type_payment_gate_way=2;








    public function getRoles()
    {
        $getCampusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $campus = Campus::find()->where(['id'=>$getCampusId])->one();
        $institute_id =  $campus->institute_id;
        $institutes = Institutes::find()->where(['id'=>$institute_id])->one();
        if ($institutes->subscription_type==Institutes::subscription_type_group_of_institutions) {
            return [
                self::ROLE_AGENT  => 'Agent',
                self::ROLE_BUS_DRIVER  => 'Bus Driver',

        ];
        } else {
            return [
                self::ROLE_BUS_COORDINATOR  => 'Bus Co Ordinator',
                self::ROLE_AGENT  => 'Agent',
                self::ROLE_BUS_DRIVER  => 'Bus Driver',

        ];
        }
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



    public function getGender()
    {
        return [

            self::IS_MALE => 'Male',
            self::IS_FEMALE => 'Female',

        ];
    }







    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'campus_id', 'designation_id', 'employ_name','user_role','employee_id','phone_number', 'age', 'gender', 'blood_group_id'], 'required'],
            [['user_id', 'campus_id', 'designation_id', 'age','bus_id','blood_group_id','academic_year_id','create_user_id', 'update_user_id','agent_type'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['employ_name', 'profile_picture', 'employee_id','is_driver','is_agent','id_proof', 'email', 'license_number','qr_code_file'], 'string', 'max' => 255],


            [['phone_number'], 'string', 'max' => 10],
            ['phone_number', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
            [['aadhar_number'], 'string', 'max' => 12],
            ['aadhar_number', 'match', 'pattern' => '/^[0-9]{12}$/'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\admin\models\base\EmployeeDetails', 'message' => 'This email address has already been taken.'],



            [['gender'], 'string', 'max' => 11],
            [['phone_number'], 'unique', 'targetAttribute' => ['phone_number', 'user_role']],
      

            [['id_proof'], 'validateIdProof', 'on' =>'create'],
            [['license_number'], 'validateLicenseNumber', 'on' =>'create'],
            [['bus_id'], 'validateBusId', 'on' =>'create'],

        ];
    }



    public function validateBusId($attribute, $params)
    {
        if (isset($this->is_driver) && $this->is_driver==1) {
            if (empty($this->bus_id)) {
                $this->addError($attribute, 'Bus Id Required');
            }
        }
    }



    public function validateMobileNumber($attribute, $params)
    {
        $phone_number= EmployeeDetails::find()
        ->joinWith('user')
        ->where(['phone_number'=>$this->phone_number])
         ->one();
        if (!empty($phone_number)) {
            $this->addError($attribute, 'Phone Number Already exist');
        }
    }


    public function validateMobileNumberUpdate($attribute, $params)
    {
        $phone_number= EmployeeDetails::find()
        ->joinWith('user')
        ->where(['phone_number'=>$this->phone_number])
         ->one();
        if(empty($phone_number)) {

        } else {
            if($phone_number['phone_number']==$this->phone_number) {

            } else {
                $this->addError($attribute, 'Phone Number Already exist');

            }


        }
    }






    public function validateIdProof($attribute, $params)
    {
        if (isset($this->is_agent) && $this->is_agent==1) {
            if(empty($this->id_proof)) {
                $this->addError($attribute, 'id Proof Required');

            }


        }
    }




    public function validateLicenseNumber($attribute, $params)
    {
        if (isset($this->is_driver) && $this->is_driver==1) {
            if (!empty($this->license_number)) {
                $license_number= EmployeeDetails::find()
                ->where(['license_number'=>$this->license_number])
                ->one();
                if (!empty($license_number)) {
                    $this->addError($attribute, 'license Number Already exist');
                }
            } else {
                $this->addError($attribute, 'license Number Required');
            }
        }
    }



    public function getAgentTypePaymentType()
    {
        return [

            self::agent_type_manual_payment => 'Manual Payment',
            self::agent_type_payment_gate_way => 'Payment Gateway',

        ];
    }



    public function getAgentTypePaymentBinges()
    {
        if ($this->agent_type == self::agent_type_manual_payment) {
            return '<span class="badge badge-success">Manual Payment</span>';
        } elseif ($this->agent_type == self::agent_type_payment_gate_way) {
            return '<span class="badge badge-default">Payment Gateway</span>';
        }
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_details';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Inactive</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
              'campus_id' => Yii::t('app', 'School Or College'),
            'designation_id' => Yii::t('app', 'Designation ID'),
            'employ_name' => Yii::t('app', 'Agent Name'),
            'profile_picture' => Yii::t('app', 'Profile Picture'),
            'employee_id' => Yii::t('app', 'Employee ID'),
            'age' => Yii::t('app', 'Age'),
            'gender' => Yii::t('app', 'Gender'),
            'blood_group_id' => Yii::t('app', 'Blood Group '),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'license_number' => Yii::t('app', 'License Number'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusStatuses()
    {
        return $this->hasMany(\app\modules\admin\models\BusStatus::className(), ['driver_id' => 'id']);
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
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(\app\modules\admin\models\Designation::className(), ['id' => 'designation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroup()
    {
        return $this->hasOne(\app\modules\admin\models\BloodGroups::className(), ['id' => 'blood_group_id']);
    }




    /**
     * @inheritdoc
     * @return \app\modules\admin\models\EmployeeDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\EmployeeDetailsQuery(get_called_class());
    }
public function asJson()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['user_id'] =  $this->user_id;

    $data['campus_id'] =  $this->campus_id;

    $data['designation_id'] =  $this->designation->asJson();
    $data['employ_name'] =  $this->employ_name;

    $data['employee_id'] =  $this->employee_id;

    $data['age'] =  $this->age;

    $data['gender'] =  $this->gender;

    $data['blood_group'] =  $this->bloodGroup->asJson();

    $data['phone_number'] =  $this->phone_number;

    $data['email'] =  $this->email;

    $data['profile_picture'] =  $this->profile_picture;


    $data['created_on'] =  $this->created_on;

    $data['updated_on'] =  $this->updated_on;

    $data['create_user_id'] =  $this->create_user_id;

    $data['update_user_id'] =  $this->update_user_id;

    $data['license_number'] =  $this->license_number;




    $getUserRole = User::find()->where(['id'=>$this->user_id])->one();
    if ($getUserRole['user_role']==User::ROLE_BUS_DRIVER) {
        $driver_has_bus = DriverHasBus::find()->where(['driver_id'=>$this->user_id])->one();
        $bus_id = $driver_has_bus['bus_id'];
        $bus_details = BusDetails::find()->where(['id'=>$bus_id])->one();
        if (!empty($bus_details)) {
            $data['bus_details'] =  $bus_details->asJson();
        } else {
            $data['bus_details'] =  '';
        }
    }


    return $data;
}



public function asJsonEmp()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['user_id'] =  $this->user_id;

    $data['campus_id'] =  $this->campus_id;

    $data['designation_id'] =  $this->designation->asJson();
     
    $data['employ_name'] =  $this->employ_name;

    $data['profile_picture'] =  $this->profile_picture;


    $data['employee_id'] =  $this->employee_id;

    $data['age'] =  $this->age;

    $data['gender'] =  $this->gender;


    $data['phone_number'] =  $this->phone_number;

    $data['email'] =  $this->email;

    return $data;
}



public function asJsonBusDriver()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['user_id'] =  $this->user_id;

    $data['campus_id'] =  $this->campus_id;

    $data['designation_id'] =  $this->designation->asJson();
     
    $data['employ_name'] =  $this->employ_name;

    $data['profile_picture'] =  $this->profile_picture;


    $data['employee_id'] =  $this->employee_id;

    $data['age'] =  $this->age;

    $data['gender'] =  $this->gender;


    $data['phone_number'] =  $this->phone_number;

    $data['email'] =  $this->email;

    return $data;
}



}
