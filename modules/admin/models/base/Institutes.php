<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes as ModelsInstitutes;
use app\modules\admin\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\di\Instance;
 
/**
 * This is the base model class for table "institutes".
 *
 * @property integer $id
 * @property string $title
 * @property string $registration_number
 * @property integer $user_id
 * @property integer $subscription_type
 * @property integer $activation_modules
 * @property string $registration_document
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $district_id
 * @property string $pincode
 * @property string $address 
 * @property double $lat
 * @property double $lng
 * @property string $coordinates
 * @property string $name_of_the_authorized
 * @property string $designation_of_the_authorized
 * @property string $contact_number_of_the_authorized
 * @property string $name_of_the_contact
 * @property string $designation_of_the_contact
 * @property string $contact_number_of_the_contact
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\AgentStudentJoin[] $agentStudentJoins
 * @property \app\modules\admin\models\AssignFeeToStudent[] $assignFeeToStudents
 * @property \app\modules\admin\models\BusDetails[] $busDetails
 * @property \app\modules\admin\models\BusRoute[] $busRoutes
 * @property \app\modules\admin\models\BusStatus[] $busStatuses
 * @property \app\modules\admin\models\Campus[] $campuses
 * @property \app\modules\admin\models\CampusHasUsers[] $campusHasUsers
 * @property \app\modules\admin\models\CampusWebSettings[] $campusWebSettings
 * @property \app\modules\admin\models\ClassSections[] $classSections
 * @property \app\modules\admin\models\Designation[] $designations
 * @property \app\modules\admin\models\DriverHasBus[] $driverHasBuses
 * @property \app\modules\admin\models\EducationalInstitutionTypes[] $educationalInstitutionTypes
 * @property \app\modules\admin\models\EmployeeDetails[] $employeeDetails
 * @property \app\modules\admin\models\FeeStructures[] $feeStructures
 * @property \app\modules\admin\models\FeesTyps[] $feesTyps
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\ParentDetails[] $parentDetails
 * @property \app\modules\admin\models\PayFees[] $payFees
 * @property \app\modules\admin\models\PaymentDetails[] $paymentDetails
 * @property \app\modules\admin\models\SpecialCourses[] $specialCourses
 * @property \app\modules\admin\models\StudentAttendanceBus[] $studentAttendanceBuses
 * @property \app\modules\admin\models\StudentClass[] $studentClasses
 * @property \app\modules\admin\models\StudentDetails[] $studentDetails
 * @property \app\modules\admin\models\StudentHasBus[] $studentHasBuses
 * @property \app\modules\admin\models\StudentHasParent[] $studentHasParents
 * @property \app\modules\admin\models\StudentSpecialCourses[] $studentSpecialCourses
 */
class Institutes extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $fee_receipt_content;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;




    public const subscription_type_group_of_institutions = 1;
    public const subscription_type_individual_institution	 = 2;


    public const activation_modules_fee_module = 1;
    public const activation_modules_bus_tracking = 2;
    public const activation_modules_bus_tracking_plus = 3;
    public const activation_modules_agent = 4;



    public const school = 1;
    public const jr_collage = 2;
    public const degree_collage = 3;
    public const engineering_collage  = 4;
    public const b_pharmacy = 5;




    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'activationModules',
            'campuses',
            'country',
            'state',
            'district',
            'updateUser',
            'createUser',
            'user'

        ];
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        if ($this->subscription_type==Institutes::subscription_type_individual_institution) {
            return [
                [['subscription_type', 'expiry_date','onboarding_date','activation_modules','email_id_of_the_authorized','name_of_the_educational_Institution', 'user_id', 'country_id', 'state_id', 'district_id', 'address', 'institute_code', 'registration_number', 'name_of_the_authorized', 'designation_of_the_authorized', 'contact_number_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'contact_number_of_the_contact', 'lat', 'lng'], 'required'],
                [['subscription_type','educational_institution_type_id', 'user_id', 'country_id', 'state_id', 'district_id', 'status', 'create_user_id', 'update_user_id','contact_number_of_the_contact'], 'integer'],
                [['address'], 'string'],
                [['expiry_date'], 'date', 'format' => 'php:Y-m-d'], // Validate date format
                [['lat', 'lng'], 'number'],
                [['created_on', 'updated_on'], 'safe'],

                [['aadhaar_of_the_authorized'], 'string', 'max' => 12],

                ['aadhaar_of_the_authorized', 'match', 'pattern' => '/^[0-9]{12}$/'],

                [['contact_number_of_the_authorized','contact_number_of_the_contact'], 'string', 'max' => 10],
                ['contact_number_of_the_authorized', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
                ['contact_number_of_the_contact', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],


        ['email_id_of_the_authorized', 'filter', 'filter' => 'trim'],
        ['email_id_of_the_authorized', 'required'],
        ['email_id_of_the_authorized', 'email'],
        ['email_id_of_the_authorized', 'unique', 'targetClass' => 'app\modules\admin\models\base\Institutes', 'message' => 'This email address has already been taken.'],
        [['pincode'], 'string', 'max' => 6],

        [['pincode'], 'string', 'max' => 6],
        ['pincode', 'match', 'pattern' => '/^[0-9]{6}$/'],





                [['name_of_the_educational_Institution'], 'string', 'max' => 500],
                [['pincode', 'institute_code', 'registration_number', 'registration_document', 'name_of_the_authorized', 'designation_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'aadhaar_of_the_authorized', 'coordinates', 'school_logo'], 'string', 'max' => 255],
                ['registration_number', 'validateRegistrationNumber', 'on' =>'create'],
                [['registration_document'], 'required', 'on' =>'create'],
                ['contact_number_of_the_authorized', 'validateContactNumber', 'on' =>'create'],
            ];
        } else {
            return [
                [['subscription_type', 'activation_modules','email_id_of_the_authorized','name_of_the_educational_Institution', 'user_id', 'country_id', 'state_id', 'district_id', 'address', 'institute_code', 'registration_number', 'name_of_the_authorized', 'designation_of_the_authorized', 'contact_number_of_the_authorized', 'lat', 'lng'], 'required'],
                [['subscription_type','educational_institution_type_id', 'user_id', 'country_id', 'state_id', 'district_id', 'status', 'create_user_id', 'update_user_id','contact_number_of_the_contact'], 'integer'],

                [['contact_number_of_the_authorized','contact_number_of_the_contact'], 'string', 'max' => 10],
                ['contact_number_of_the_authorized', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
                ['contact_number_of_the_contact', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],




        ['email_id_of_the_authorized', 'filter', 'filter' => 'trim'],
        ['email_id_of_the_authorized', 'required'],
        ['email_id_of_the_authorized', 'email'],
        ['email_id_of_the_authorized', 'unique', 'targetClass' => 'app\modules\admin\models\base\Institutes', 'message' => 'This email address has already been taken.'],

        [['aadhaar_of_the_authorized'], 'string', 'max' => 12],

            ['aadhaar_of_the_authorized', 'match', 'pattern' => '/^[0-9]{12}$/'],


            [['pincode'], 'string', 'max' => 6],

            ['pincode', 'match', 'pattern' => '/^[0-9]{6}$/'],




                [['address'], 'string'],
                [['lat', 'lng'], 'number'],
                [['created_on', 'updated_on'], 'safe'],
                [['name_of_the_educational_Institution'], 'string', 'max' => 500],
                [['pincode', 'institute_code', 'registration_number', 'registration_document', 'name_of_the_authorized', 'designation_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'aadhaar_of_the_authorized', 'coordinates', 'school_logo'], 'string', 'max' => 255],
                [['email_id_of_the_authorized'],'unique'],
                ['registration_number', 'validateRegistrationNumber', 'on' =>'create'],
                [['registration_document'], 'required', 'on' =>'create'],
                ['contact_number_of_the_authorized', 'validateContactNumber', 'on' =>'create'],
            ];
        }
    }




    public function validateRegistrationNumber($attribute, $params)
    {
        $registration_number= Institutes::find()
        ->where(['registration_number'=>$this->registration_number])
         ->one();
        if (!empty($registration_number)) {
            $this->addError($attribute, 'Registration Number Already exist');
        }
    }


public function validateContactNumber($attribute, $params)
{
    if ($this->subscription_type==Institutes::subscription_type_group_of_institutions) {
        $user_role = User::ROLE_INSTITUTE_ADMIN;
    } else {
        $user_role = User::ROLE_CAMPUS_ADMIN;
    }




    $contact_number_of_the_authorized= Institutes::find()
    ->where(['contact_number_of_the_authorized'=>$this->contact_number_of_the_authorized])
     ->one();
    if (!empty($contact_number_of_the_authorized)) {
        $this->addError($attribute, 'Authorize contact number already exists');
    } else {
    }
}



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'institutes';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subscription_type' => Yii::t('app', 'Subscription Type'),
            'activation_modules' => Yii::t('app', 'Activation Modules'),
            'educational_institution_type_id' => Yii::t('app', 'Educational Institution Type ID'),
            'name_of_the_educational_Institution' => Yii::t('app', 'Name Of The Educational Institution'),
            'user_id' => Yii::t('app', 'User ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'pincode' => Yii::t('app', 'Pincode'),
            'address' => Yii::t('app', 'Address'),
            'institute_code' => Yii::t('app', 'UDISC Code'),
            'registration_number' => Yii::t('app', 'Registration Number'),
            'registration_document' => Yii::t('app', 'Registration Document'),
            'name_of_the_authorized' => Yii::t('app', 'Name Of The Authorized'),
            'designation_of_the_authorized' => Yii::t('app', 'Designation Of The Authorized'),
            'contact_number_of_the_authorized' => Yii::t('app', 'Contact Number Of The Authorized'),
            'name_of_the_contact' => Yii::t('app', 'Name Of The Contact'),
            'designation_of_the_contact' => Yii::t('app', 'Designation Of The Contact'),
            'contact_number_of_the_contact' => Yii::t('app', 'Contact Number Of The Contact'),
            'email_id_of_the_authorized' => Yii::t('app', 'Email Id Of The Authorized'),
            'aadhaar_of_the_authorized' => Yii::t('app', 'Aadhaar Of The Authorized'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'coordinates' => Yii::t('app', 'Coordinates'),
            'status' => Yii::t('app', 'Status'),
            'school_logo' => Yii::t('app', 'School Logo'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }



    public function getSubscriptionTypeOptions()
    {
        return [

            self::subscription_type_group_of_institutions  => 'Group of institutions',
            self::subscription_type_individual_institution => 'Individual Institution',


        ];
    }


    public function getSubscriptionTypeBadges()
    {
        if ($this->subscription_type == self::subscription_type_group_of_institutions) {
            return '<span class="badge badge-success">Group of institutions</span>';
        } elseif ($this->subscription_type == self::subscription_type_individual_institution) {
            return '<span class="badge badge-success">Individual Institution</span>';
        }
    }

    public function getActivationModules()
    {
        return $this->hasMany(\app\modules\admin\models\ActivationModules::className(), ['institute_id' => 'id']);
    }



    public function getInstitutesType()
    {
        return [

            self::school => 'School',
            self::jr_collage => 'Jr Collage',
            self::degree_collage => 'Degree Collage',
            self::engineering_collage=>'Engineering Collage',
            self::b_pharmacy =>'B Pharmacy'

        ];
    }




    public function getActionModeOptions()
    {
        return [

            self::activation_modules_fee_module  => 'Fee Module',
            self::activation_modules_bus_tracking => 'Bus tracking plus((without google maps)',
            self::activation_modules_bus_tracking_plus => 'Bus Tracking (with google maps)',
            self::activation_modules_agent  => 'Agent',

        ];
    }



    public function getActionModeOptionsSave()
    {
        return [

            self::activation_modules_fee_module  => 1,
            self::activation_modules_bus_tracking => 2,
            self::activation_modules_bus_tracking_plus => 3,
            self::activation_modules_agent  => 4,



        ];
    }



    public function getActionModeBadges()
    {
        if ($this->activation_modules == self::activation_modules_fee_module) {
            return '<span class="badge badge-success">Fee Module</span>';
        } elseif ($this->activation_modules == self::activation_modules_bus_tracking) {
            return '<span class="badge badge-default">Bus tracking plus(without google maps)</span>';
        } elseif ($this->activation_modules == self::activation_modules_bus_tracking_plus) {
            return '<span class="badge badge-default">Bus Tracking (with google maps)</span>';
        } elseif ($this->activation_modules == self::activation_modules_agent) {
            return '<span class="badge badge-default">Agent</span>';
        }
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
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }
    }

    public static function getInstituteIdOfUser()
    {
        $institutes = Institutes::find()->where(['user_id'=>Yii::$app->user->identity->id])->andWhere(['status'=>Institutes::STATUS_ACTIVE])->one();
        if (!empty($institutes)) {
            return $institutes['id'];
        } else {
            return;
        }
    }





    public function getCampusByInstituteId()
    {
        $campus_id = [];
        $Institutes = self::getInstituteIdOfUser();
        $campus = Campus::find()->where(['institute_id'=>$Institutes])->all();
        if (!empty($campus)) {
            foreach ($campus as $campus_data) {
                $campus_id[] = $campus_data->id;
            }
            if (!empty($campus_id)) {
                return $campus_id;
            } else {
                return;
            }
        } else {
            return;
        }
    }











      /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampuses()
    {
        return $this->hasMany(\app\modules\admin\models\Campus::className(), ['institute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\app\modules\admin\models\Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(\app\modules\admin\models\State::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(\app\modules\admin\models\District::className(), ['id' => 'district_id']);
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
    public function getUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'user_id']);
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
     * @return \app\modules\admin\models\InstitutesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\InstitutesQuery(get_called_class());
    }
}
