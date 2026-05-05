<?php

namespace app\modules\admin\models\base;

use app\models\User;
use app\modules\admin\models\ActivationModules;
use app\modules\admin\models\base\Institutes as BaseInstitutes;
use app\modules\admin\models\Campus as ModelsCampus;
use app\modules\admin\models\EducationalInstitutionTypes;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\StudentDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\di\Instance;

/** 
 * This is the base model class for table "campus".
 *
 * @property integer $id
 * @property integer $institute_id
 * @property string $name_of_the_educational_Institution
 * @property integer $educational_institution_type_id
 * @property integer $user_id
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $district_id
 * @property string $pincode
 * @property string $address
 * @property string $registration_number
 * @property string $registration_document
 * @property string $name_of_the_authorized
 * @property string $designation_of_the_authorized
 * @property string $contact_number_of_the_authorized
 * @property string $name_of_the_contact
 * @property string $designation_of_the_contact
 * @property string $contact_number_of_the_contact
 * @property string $email_id_of_the_authorized
 * @property string $aadhaar_of_the_authorized
 * @property integer $subscription_type
 * @property integer $activation_modules
 * @property double $lat
 * @property double $lng
 * @property string $city
 * @property string $school_logo
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *@property integer $radius
 * 
 * @property \app\modules\admin\models\AssignFeeToStudent[] $assignFeeToStudents
 * @property \app\modules\admin\models\BusDetails[] $busDetails
 * @property \app\modules\admin\models\BusRoute[] $busRoutes
 * @property \app\modules\admin\models\BusStatus[] $busStatuses
 * @property \app\modules\admin\models\District $district
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\Country $country
 * @property \app\modules\admin\models\Notification $state
 * @property \app\modules\admin\models\Institutes $institute
 * @property \app\modules\admin\models\EducationalInstitutionTypes $educationalInstitutionType
 * @property \app\modules\admin\models\District $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\CampusHasUsers[] $campusHasUsers
 * @property \app\modules\admin\models\CampusWebSettings[] $campusWebSettings
 * @property \app\modules\admin\models\ClassSections[] $classSections
 * @property \app\modules\admin\models\Designation[] $designations
 * @property \app\modules\admin\models\DriverHasBus[] $driverHasBuses
 * @property \app\modules\admin\models\EmployeeDetails[] $employeeDetails
 * @property \app\modules\admin\models\FeeStructures[] $feeStructures
 * @property \app\modules\admin\models\FeesTyps[] $feesTyps
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
class Campus extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'agentStudentJoins',
            'busDetails',
            'busRoutes',
            'busStatuses',
            'state',
            'country',
            'district',
            'updateUser',
            'createUser',
            'institute',
            'campusHasUsers',
            'campusWebSettings',
            'classSections',
            'designations',
            'driverHasBuses',
            'employeeDetails',
            'feeStructures',
            'feesTyps',
            'parentDetails',
            'payFees',
            'paymentDetails',
            'specialCourses',
            'studentAttendanceBuses',
            'studentClasses',
            'studentDetails',
            'studentHasBuses',
            'studentHasParents',
            'studentSpecialCourses',
            'academicYear',
            'educationalInstitutionType'
        ];
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;

    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    public const subscription_type_group_of_institutions = 1;
    public const subscription_type_individual_institution     = 2;

    public const activation_modules_fee_module = 1;
    public const activation_modules_bus_tracking = 2;
    public const activation_modules_bus_tracking_plus = 3;


    public const school = 1;
    public const jr_collage = 2;
    public const degree_collage = 3;
    public const engineering_collage  = 4;
    public const b_pharmacy = 5;







    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['institute_id',  'educational_institution_type_id', 'email_id_of_the_authorized', 'name_of_the_educational_Institution', 'user_id', 'country_id', 'state_id', 'district_id', 'address',   'name_of_the_authorized', 'designation_of_the_authorized', 'contact_number_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'contact_number_of_the_contact', 'lat', 'lng'], 'required'],
            [['institute_id', 'educational_institution_type_id', 'user_id', 'country_id', 'state_id', 'contact_number_of_the_contact', 'district_id', 'status', 'create_user_id', 'update_user_id', 'academic_year'], 'integer'],
            [['address'], 'string'],



            [['contact_number_of_the_authorized', 'contact_number_of_the_contact'], 'string', 'max' => 10],
            ['contact_number_of_the_authorized', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
            ['contact_number_of_the_contact', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
            [['aadhaar_of_the_authorized'], 'string', 'max' => 12],
            ['aadhaar_of_the_authorized', 'match', 'pattern' => '/^[0-9]{12}$/'],
            ['email_id_of_the_authorized', 'filter', 'filter' => 'trim'],
            ['email_id_of_the_authorized', 'required'],
            ['email_id_of_the_authorized', 'email'],
            // ['email_id_of_the_authorized', 'unique', 'targetClass' => 'app\modules\admin\models\base\Campus', 'message' => 'This email address has already been taken.'],

            [['pincode'], 'string', 'max' => 6],
            ['pincode', 'match', 'pattern' => '/^[0-9]{6}$/'],


            [['expiry_date', 'onboarding_date'], 'safe'],



            [['lat', 'lng'], 'number'],
            [['created_on', 'updated_on', 'admin_commision_percentage', 'registration_document', 'registration_number', 'campus_code',], 'safe'],
            [['name_of_the_educational_Institution', 'fee_receipt_content'], 'string', 'max' => 500],
            [['pincode', 'campus_code', 'registration_number', 'registration_document', 'name_of_the_authorized', 'designation_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'email_id_of_the_authorized', 'aadhaar_of_the_authorized', 'coordinates', 'radius', 'city', 'school_logo'], 'string', 'max' => 255],
            ['registration_number', 'validateRegistrationNumber', 'on' => 'create'],


            ['contact_number_of_the_authorized', 'safe'],



        ];
    }


    public function getInstitutesType()
    {
        return [

            self::school => 'School',
            self::jr_collage => 'Jr Collage',
            self::degree_collage => 'Degree Collage',
            self::engineering_collage => 'Engineering Collage',
            self::b_pharmacy => 'B Pharmacy'

        ];
    }



    public function validateRegistrationNumber($attribute, $params)
    {
        $registration_number = Campus::find()
            ->where(['registration_number' => $this->registration_number])
            ->one();
        if (!empty($registration_number)) {
            $this->addError($attribute, 'Registration Number Already exist');
        }
    }



    public function validateContactNumber($attribute, $params)
    {
        $contact_number_of_the_authorized = Campus::find()
            ->where(['contact_number_of_the_authorized' => $this->contact_number_of_the_authorized])
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
        return 'campus';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETE => 'Deleted',

        ];
    }



    public function getSubscriptionTypeOptions()
    {
        return [

            self::subscription_type_group_of_institutions  => 'Group of institutions',
            self::subscription_type_individual_institution => 'Individual Institution',


        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducationalInstitutionType()
    {
        return $this->hasOne(\app\modules\admin\models\EducationalInstitutionTypes::className(), ['id' => 'educational_institution_type_id']);
    }


    public function getSubscriptionTypeBadges()
    {
        if ($this->subscription_type == self::subscription_type_group_of_institutions) {
            return '<span class="badge badge-success">Group of institutions</span>';
        } elseif ($this->subscription_type == self::subscription_type_individual_institution) {
            return '<span class="badge badge-default">Individual Institution</span>';
        }
    }








    public function getActionModeOptions()
    {
        return [

            self::activation_modules_fee_module  => 'Fee Module',
            self::activation_modules_bus_tracking => 'Bus tracking plus((without google maps)',
            self::activation_modules_bus_tracking_plus => 'Bus Tracking (with google maps)',

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
        }
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

    public static function getCampusDashboardByCampusId($campus_id, $type)
    {
        switch ($type) {
            case "student_details":
                $student_details = StudentDetails::find()->where(['campus_id' => $campus_id])->count();
                return  !empty($student_details) ? $student_details : 0;
                break;
            case "total_bus_campus":
                $total_bus_campus = BusDetails::find()->where(['campus_id' => $campus_id])->count();
                return  !empty($total_bus_campus) ? $total_bus_campus : 0;
                break;

            case "total_agents":
                $total_agents = EmployeeDetails::find()
                    ->joinWith('user')
                    ->where(['user.user_role' => User::ROLE_AGENT])
                    ->andWhere(['employee_details.campus_id' => $campus_id])
                    ->count();
                return  !empty($total_agents) ? $total_agents : 0;
                break;

            case "total_agents_admissions":
                $total_agents_admissions = StudentDetailsAgentLead::find()->where(['campus_id' => $campus_id])->count();
                $data['total_drivers'] = EmployeeDetails::find()
                    ->joinWith('user')
                    ->where(['user.user_role' => User::ROLE_BUS_DRIVER])
                    ->andWhere(['employee_details.campus_id' => $campus_id])
                    ->count();
                return  !empty($total_agents_admissions) ? $total_agents_admissions : 0;

                break;
            case "payment_details_pending":
                $payment_details_pending = PaymentDetails::find()
                    ->where(['status' => PaymentDetails::status_pending])
                    ->andWhere(['campus_id' => $campus_id])
                    ->count();
                return  !empty($payment_details_pending) ? $payment_details_pending : 0;

                break;

            case "payment_details_failed":
                $payment_details_failed = PaymentDetails::find()
                    ->where(['status' => PaymentDetails::status_failed])
                    ->andWhere(['campus_id' => $campus_id])
                    ->count();
                return  !empty($payment_details_failed) ? $payment_details_failed : 0;

                break;
            case "total_parents":
                $total_parents = User::find()
                    ->where(['user_role' => User::ROLE_PARENT])
                    ->andWhere(['campus_id' => $campus_id])
                    ->count();

                return  !empty($total_parents) ? $total_parents : 0;


                break;
            case "total_fee_collection":
                $total_fee_collection = PaymentDetails::find()->where(['campus_id' => $campus_id])->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
                return  !empty($total_fee_collection) ? $total_fee_collection : 0;

                break;
            case "total_fee":
                $total_fee_collection = PaymentDetails::find()->where(['campus_id' => $campus_id])->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
                $fee_structures = PayFees::find()->joinWith('feeStructures')
                    ->andWhere(['pay_fees.campus_id' => $campus_id])
                    ->sum('fee_structures.fee');
                $pay_fees_fee_cut = PayFees::find()->where(['pay_fees.campus_id' => $campus_id])->sum('fees_cut');
                $total_fee = $fee_structures - $pay_fees_fee_cut;
                return  !empty($total_fee) ? $total_fee : 0;
                break;




            case "pending_fee":
                $total_fee_collection = PaymentDetails::find()->where(['campus_id' => $campus_id])->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
                $fee_structures = PayFees::find()->joinWith('feeStructures')
                    ->andWhere(['pay_fees.campus_id' => $campus_id])
                    ->sum('fee_structures.fee');
                $pay_fees_fee_cut = PayFees::find()->where(['pay_fees.campus_id' => $campus_id])->sum('fees_cut');
                $total_fee = $fee_structures - $pay_fees_fee_cut;
                $total_fee = $total_fee;
                $pending_fee = $total_fee - $total_fee_collection;
                return  !empty($pending_fee) ? $pending_fee : 0;

                break;

            case "no_of_classes":
                $no_of_classes = StudentClass::find()->where(['campus_id' => $campus_id])->andWhere(['is_agent' => null])->count();
                return  !empty($no_of_classes) ? $no_of_classes : 0;
                break;

            case "no_of_sections":
                $no_of_sections = ClassSections::find()->where(['campus_id' => $campus_id])->count();
                return  !empty($no_of_sections) ? $no_of_sections : 0;
                break;

            default:
                return 0;
        }
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'institute_id' => Yii::t('app', 'Institute ID'),
            'educational_institution_type_id' => Yii::t('app', 'Educational Institution Type ID'),
            'name_of_the_educational_Institution' => Yii::t('app', 'Name Of The Educational Institution'),
            'user_id' => Yii::t('app', 'User ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'pincode' => Yii::t('app', 'Pincode'),
            'address' => Yii::t('app', 'Address'),
            'campus_code' => Yii::t('app', 'Campus Code'),
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
            'radius' => Yii::t('app', 'Radius'),
            'city' => Yii::t('app', 'City'),
            'status' => Yii::t('app', 'Status'),
            'school_logo' => Yii::t('app', 'School Logo'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }

    public static function getCampusId()
    {
        $campus = Campus::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        if (!empty($campus)) {
            return $campus['id'];
        } else {
            return Null;
        }
    }


    public static function getCampusName()
    {
        $campus = Campus::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        return $campus['name_of_the_educational_Institution'] ?? "";
    }



    public function getInstituteHasCampusIds()
    {
        $campus_id = [];
        $Institute = Institutes::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        $campus = Campus::find()->where(['institute_id' => $Institute['id']])->all();
        foreach ($campus as $campus_data) {
            $campus_id[] =  $campus_data->id;
        }
        return $campus_id;
    }

    public function getCampusByStudentId($student_id = '')
    {
        if (!empty($student_id)) {
            $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
            if (!empty($student_details)) {
                $campus_id = $student_details->campus_id;
                return $campus_id;
            } else {
                return;
            }
        } else {
            return;
        }
    }
    function getCampusByParent($userId)
    {
        // Find the parent ID from the ParentDetails table
        $parentDetails = ParentDetails::findOne(['user_id' => $userId]);

        if (!$parentDetails) {
            return null; // Return null if no parent details are found
        }

        // Get the parent ID
        $parentId = $parentDetails->id;

        // Fetch campus IDs from ParentHasCamps table
        $campusIds = ParentHasCampus::find()
            ->select('campus_id')
            ->where(['patient_id' => $parentId])
            ->column(); // Get an array of campus IDs

        return $campusIds;
    }

    public function getCurrentSession($campus_id)
    {
        $campus = Campus::find()->where(['id' => $campus_id])->one();

        if (empty($campus->academic_year)) {
            return false;
        } else {
            return $campus->academic_year;
        }
    }






    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year']);
    }





    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgentStudentJoins()
    {
        return $this->hasMany(\app\modules\admin\models\AgentStudentJoin::className(), ['campus_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusDetails()
    {
        return $this->hasMany(\app\modules\admin\models\BusDetails::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusRoutes()
    {
        return $this->hasMany(\app\modules\admin\models\BusRoute::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusStatuses()
    {
        return $this->hasMany(\app\modules\admin\models\BusStatus::className(), ['campus_id' => 'id']);
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
    public function getCountry()
    {
        return $this->hasOne(\app\modules\admin\models\Country::className(), ['id' => 'country_id']);
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
    public function getInstitute()
    {
        return $this->hasOne(\app\modules\admin\models\Institutes::className(), ['id' => 'institute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampusHasUsers()
    {
        return $this->hasMany(\app\modules\admin\models\CampusHasUsers::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampusWebSettings()
    {
        return $this->hasMany(\app\modules\admin\models\CampusWebSettings::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassSections()
    {
        return $this->hasMany(\app\modules\admin\models\ClassSections::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignations()
    {
        return $this->hasMany(\app\modules\admin\models\Designation::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\DriverHasBus::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDetails()
    {
        return $this->hasMany(\app\modules\admin\models\EmployeeDetails::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeeStructures()
    {
        return $this->hasMany(\app\modules\admin\models\FeeStructures::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeesTyps()
    {
        return $this->hasMany(\app\modules\admin\models\FeesTyps::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\ParentDetails::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayFees()
    {
        return $this->hasMany(\app\modules\admin\models\PayFees::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\PaymentDetails::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialCourses()
    {
        return $this->hasMany(\app\modules\admin\models\SpecialCourses::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAttendanceBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentAttendanceBus::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClasses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentClass::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\StudentDetails::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasBus::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasParents()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasParent::className(), ['campus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentSpecialCourses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentSpecialCourses::className(), ['campus_id' => 'id']);
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
                'updatedByAttribute' => 'update_user_id',
            ],
        ];
    }


    /**
     * @inheritdoc
     * @return \app\modules\admin\models\CampusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\CampusQuery(get_called_class());
    }






    public function getNameOfTheEducationalInstitution($campus_id)
    {


        $campus_of_educational_types = Campus::find()->where(['id' => $campus_id])->one();

        $data = EducationalInstitutionTypes::find()
            ->where(['id' => $campus_of_educational_types['educational_institution_type_id']])
            ->andWhere(['status' => EducationalInstitutionTypes::STATUS_ACTIVE])
            ->asArray()
            ->all();
    }


    public function getCampusData($institute_id)
    {
        $out = [];
        $data = Campus::find()
            ->where(['institute_id' => $institute_id])
            // ->andWhere(['status'=>Campus::STATUS_ACTIVE])

            ->asArray()
            ->all();
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['name_of_the_educational_Institution']];
        }
        return $output = [
            'output' => $out
        ];
    }


    public function checkModuleActivationSTatus($module)
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $campus = Campus::find()->where(['id' => $campusId])->one();
        if (!empty($campus)) {
            $institute_id  = $campus->institute_id;
            $institutes = ActivationModules::find()->where(['institute_id' => $institute_id])
                ->andWhere(['activation_modules.activation_modules' => $module])->one();
            if (!empty($institutes)) {
                return 'ok';
            } else {
                return 'no';
            }
        } else {
            return 'no';
        }
    }


    public function checkIndividualCampus()
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $campus = Campus::find()->where(['id' => $campusId])->one();
        if (!empty($campus)) {
            $institutes = Institutes::find()->where(['id' => $campus->institute_id])->andWhere(['subscription_type' => Institutes::subscription_type_individual_institution])->one();
            if (!empty($institutes)) {
                return true;
            } else {
                return false;
            }
        }
    }





    public static function getCampusDashBoardCards($campuses_data_id)
    {

        $html = '


 



     
    <div class="row">


    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Students</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'student_details') . '</h3>
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/students.png">
                        </div>
                    </div>
                </div>
          
        </div>
    </div>



    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>No Of Classes</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'no_of_classes') . '</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-classes.png">

                        </div>
                    </div>
                </div>
          
        </div>
    </div>



    


    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>No Of Sections</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'no_of_sections') . '</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                       
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-section.png">
                        </div>
                    </div>
                </div>
          </div>
    </div>






    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Parents</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_parents') . '</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">


                        </div>
                    </div>
                </div>
                </div>
                </div>


            </div>




    <div class="row">
    <div class="col-lg-12 col-12">
    <h3>Bus Management</h3>
    </div>



        <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Bus</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_bus_campus') . '</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-bus.png">


                        </div>
                    </div>
                </div>
                </div>
                </div>





            <div class="col-xl-3 col-sm-6 col-12 d-flex">   
            <div class="card bg-comman w-100">
                    <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Drivers</h6>
                                    <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_drivers') . '</h3>
                                    
                                    </div>
                                <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-driver.png">


                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
             


                </div>


    <div class="row">

     <div class="col-lg-12 col-12">
    <h3>Agent Management</h3>
    </div>




    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Agents</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_agents') . '</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/agent.png">
                        

                        </div>
                    </div>
                </div>
                </div>
                </div>




                <div class="col-xl-3 col-sm-6 col-12 d-flex">   
                <div class="card bg-comman w-100">
                        <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Total Agent Admissions</h6>
                                        <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_agents_admissions') . '</h3>
                                        
                                        </div>
                                    <div class="db-icon avatar-img rounded-circle">
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/agent-addmission.png">


                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>






                            
                <div class="col-xl-3 col-sm-6 col-12 d-flex">   
                <div class="card bg-comman w-100">
                        <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Pending Fee Requests</h6>
                                        <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'payment_details_pending') . '</h3>
                                        
                                        </div>
                                    <div class="db-icon avatar-img rounded-circle">
                                    <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/pending-request.png">


                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>




                            <div class="col-xl-3 col-sm-6 col-12 d-flex">   
                            <div class="card bg-comman w-100">
                                    <div class="card-body">
                                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                                <div class="db-info">
                                                    <h6>rejected Payment requests</h6>
                                                    <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'payment_details_failed') . '</h3>
                                                    
                                                    </div>
                                                <div class="db-icon avatar-img rounded-circle">
                                    
                                    <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/rejected-payment-requests.png">


                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        </div>



</div>


    <div class="row">

    <div class="col-lg-12 col-12">
    <h3>Fee Management</h3>
    </div>




    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Fee</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_fee') . '/-</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">


                        </div>
                    </div>
                </div>
                </div>
                </div>







 



                
    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Fee Collection</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'total_fee_collection') . '/-</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee-collection.png">
                    
                        </div>
                    </div>
                </div>
                </div>
                </div>




                            
    <div class="col-xl-3 col-sm-6 col-12 d-flex">   
    <div class="card bg-comman w-100">
            <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Pending Fee</h6>
                            <h3>' . Campus::getCampusDashboardByCampusId($campuses_data_id, 'pending_fee') . '/-</h3>
                            
                            </div>
                        <div class="db-icon avatar-img rounded-circle">
                        
                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/pending-fee.png">

                        </div>
                    </div>
                </div>
                </div>
                </div>












    </div>
    ';
        return $html;
    }








    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['institute_id'] =  $this->institute_id;

        $data['name_of_the_educational_Institution'] =  $this->name_of_the_educational_Institution;

        $data['educational_institution_type_id'] =  $this->educational_institution_type_id;

        $data['user_id'] =  $this->user_id;

        $data['country_id'] =  $this->country_id;

        $data['state_id'] =  $this->state_id;

        $data['district_id'] =  $this->district_id;

        $data['pincode'] =  $this->pincode;

        $data['address'] =  $this->address;

        $data['registration_number'] =  $this->registration_number;

        $data['registration_document'] =  $this->registration_document;

        $data['name_of_the_authorized'] =  $this->name_of_the_authorized;

        $data['designation_of_the_authorized'] =  $this->designation_of_the_authorized;

        $data['contact_number_of_the_authorized'] =  $this->contact_number_of_the_authorized;

        $data['name_of_the_contact'] =  $this->name_of_the_contact;

        $data['designation_of_the_contact'] =  $this->designation_of_the_contact;

        $data['contact_number_of_the_contact'] =  $this->contact_number_of_the_contact;

        $data['email_id_of_the_authorized'] =  $this->email_id_of_the_authorized;

        $data['aadhaar_of_the_authorized'] =  $this->aadhaar_of_the_authorized;



        $data['lat'] =  $this->lat;

        $data['lng'] =  $this->lng;

        $data['city'] =  $this->city;

        $data['school_logo'] =  $this->school_logo;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
