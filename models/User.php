<?php

namespace app\models;

use app\traits\models\WithStatus;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use app\modules\admin\models\WebSetting;
use app\modules\admin\models\AuthSession;
use app\modules\admin\models\Campus;
use app\modules\admin\models\CampusHasUsers;
use app\modules\admin\models\District;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\State;
use yii\di\Instance;
use app\forms\LoginForm;
use app\modules\admin\models\base\ParentDetails;
use app\modules\admin\models\Roles;
use app\modules\admin\models\UserHasModules;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\TeacherAttenddence;
use app\modules\hostelmanagement\models\WardenAttandance;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property-read string fullName
 * @property-read string shortName
 */
class User extends ActiveRecord implements IdentityInterface
{
    use WithStatus;
    public $confirm_password;
    public $newPassword;
    public $rememberMe = false;
    public $module_id;
    public $password; // This is for the password input in forms
    public $passwordRepeat; // This is for confirming the password
    public $phone_number;

    public const STATUS_ACTIVE = 10;
    public const STATUS_BLOCKED = 0;
    public const STATUS_INACTIVE = 9;


    //const ROLE_USER = 'User';


    public const ROLE_SUBADMIN = 'Subadmin';
    public const ROLE_VENDOR = 'Vendor';
    public const ROLE_MANAGER = 'Manager';
    public const ROLE_USER = 'User';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_BUS_COORDINATOR = 'BusCoOrdinator';
    public const ROLE_AGENT = 'Agent';
    public const ROLE_ACCOUNTANT = 'Accountant';
    public const ROLE_BUS_DRIVER = 'BusDriver';
    public const ROLE_PARENT = 'Parent';
    public const ROLE_MANAGEMENT = 'Management';
    public const ROLE_STUDENT = 'Student';
    public const ROLE_CAMPUS_ADMIN = 'CampusAdmin';
    public const ROLE_INSTITUTE_ADMIN = 'InstituteAdmin';
    public const role_key_person = 'keyPerson';
    public const role_campus_sub_admin = 'campusSubAdmin';
    public const role_teacher = 'teacher';
    public const role_principal = 'Principal';
    public const ROLE_WARDEN = 'Warden';
    public const ROLE_LIBRARIAN = 'Librarian';
    public const ROLE_CHEF_WARDEN = 'ChiefWarden';
    public const ROLE_STAFF = 'Staff';
    //sub admin ->
    // fee admin ,bus admin, admission admin,campus admin,account,


    public const SIGNUP_TYPE_SOCIAL_MEDIA = 1;
    public const SIGNUP_TYPE_MOBILE = 2;
    public const SIGNUP_TYPE_SITE = 0;

    public const login_type_institutes = 1;
    public const login_type_campus = 2;

    public const module_manage_campus = 1;
    public const module_student_management = 2;
    public const module_bus_management = 3;
    public const module_payment = 4;
    public const module_agent = 5;
    public const module_fee_structure = 6;
    public const module_fee_assign = 7;
    public const module_fee_payments = 8;

    const LIBRARIAN_INACTIVE = 9;
    const LIBRARIAN_ACTIVE = 10;
    const LIBRARIAN_DELETE = 0;

    const IS_CHIEF_WARDEN = 'ChefWarden';
    const IS_WARDEN = 'Warden';



    public function getActionModeOptions()
    {
        return [

            self::module_manage_campus => 'module manage campus',
            self::module_student_management => 'module student management',
            self::module_bus_management => 'module bus management',
            self::module_payment => 'module payment',
            self::module_agent => 'module agent',
            self::module_fee_structure => 'module fee structure',
            self::module_fee_assign => 'module fee assign',
            self::module_fee_payments => 'module fee payments'

        ];
    }



    public function getActionModeOptionsSave()
    {
        return [

            self::module_manage_campus => 1,
            self::module_student_management => 2,
            self::module_bus_management => 3,
            self::module_payment => 4,
            self::module_agent => 5,
            self::module_fee_structure => 6,
            self::module_fee_assign => 7,
            self::module_fee_payments => 8


        ];
    }
    public static function getWardenOptions()
    {
        return [
            self::IS_CHIEF_WARDEN => 'ChiefWarden',
            self::IS_WARDEN => 'Warden',

        ];
    }
    public function getStateOptions()
    {
        return [

            self::LIBRARIAN_ACTIVE => 'Active',
            self::LIBRARIAN_INACTIVE => 'In Active',
            self::LIBRARIAN_DELETE => 'Deleted',

        ];
    }




    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{user}}';
    }


    public function relationNames()
    {
        return [
            'activationModules',
            'agentStudentJoins',
            'auths',
            'bloodGroups',
            'busDetails',
            'busRoutes',
            'busStatuses',
            'campuses',
            'campusHasUsers',
            'campusWebSettings',
            'classSections',
            'countries',
            'designations',
            'districts',
            'driverHasBuses',
            'employeeDetails',
            'fcmNotifications',
            'feeStructures',
            'feesTyps',
            'institutes',
            'payFees',
            'paymentDetails',
            'specialCourses',
            'states',
            'studentAttendanceBuses',
            'studentClasses',
            'studentDetails',
            'studentDetailsAgentLeads',
            'studentHasBuses',
            'studentHasParents',
            'studentSpecialCourses',
            'webSettings'
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivationModules()
    {
        return $this->hasMany(\app\modules\admin\models\ActivationModules::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgentStudentJoins()
    {
        return $this->hasMany(\app\modules\admin\models\AgentStudentJoin::className(), ['agent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(\app\modules\admin\models\Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroups()
    {
        return $this->hasMany(\app\modules\admin\models\BloodGroups::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusDetails()
    {
        return $this->hasMany(\app\modules\admin\models\BusDetails::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusRoutes()
    {
        return $this->hasMany(\app\modules\admin\models\BusRoute::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusStatuses()
    {
        return $this->hasMany(\app\modules\admin\models\BusStatus::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampuses()
    {
        return $this->hasMany(\app\modules\admin\models\Campus::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampusHasUsers()
    {
        return $this->hasMany(\app\modules\admin\models\CampusHasUsers::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampusWebSettings()
    {
        return $this->hasMany(\app\modules\admin\models\CampusWebSettings::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassSections()
    {
        return $this->hasMany(\app\modules\admin\models\ClassSections::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(\app\modules\admin\models\Country::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesignations()
    {
        return $this->hasMany(\app\modules\admin\models\Designation::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(\app\modules\admin\models\District::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\DriverHasBus::className(), ['driver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDetails()
    {
        return $this->hasMany(\app\modules\admin\models\EmployeeDetails::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFcmNotifications()
    {
        return $this->hasMany(\app\modules\admin\models\FcmNotification::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeeStructures()
    {
        return $this->hasMany(\app\modules\admin\models\FeeStructures::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeesTyps()
    {
        return $this->hasMany(\app\modules\admin\models\FeesTyps::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitutes()
    {
        return $this->hasMany(\app\modules\admin\models\Institutes::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayFees()
    {
        return $this->hasMany(\app\modules\admin\models\PayFees::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\PaymentDetails::className(), ['create_user_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(\app\modules\admin\models\ParentHasCampus::className(), ['user_id' => 'id']);
    }

    public function getParentDetail()
    {
        return $this->hasOne(\app\modules\admin\models\ParentDetails::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialCourses()
    {
        return $this->hasMany(\app\modules\admin\models\SpecialCourses::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(\app\modules\admin\models\State::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAttendanceBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentAttendanceBus::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClasses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentClass::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\StudentDetails::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetailsAgentLeads()
    {
        return $this->hasMany(\app\modules\admin\models\StudentDetailsAgentLead::className(), ['create_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasBus::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasParents()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasParent::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentSpecialCourses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentSpecialCourses::className(), ['update_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebSettings()
    {
        return $this->hasMany(\app\modules\admin\models\WebSetting::className(), ['updated_user_id' => 'id']);
    }

    public static function getCampusId()
    {
        $campus = Campus::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->one();

        if (!empty($campus)) {
            return $campus->id;
        } else if (!empty(\Yii::$app->user->identity->campus_id)) {
            return \Yii::$app->user->identity->campus_id;
        }
    }

    public static function getTeacherCampus($user_id)
    {
        $campus = TeacherDetails::find()
            ->where(['user_id' => $user_id])
            ->one();
        if (!empty($campus)) {
            return $campus->campus_id;
        }
    }

    public static function getChiefWardenCampus($user_id = '')
    {
        $user = User::find()
            ->where(['id' => $user_id])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])
            ->one();


        // exit;
        if (!empty($user)) {
            return $user->campus_id;
        }
    }
    public static function getUserCampusId($user_id = '')
    {
        if (!empty($user_id)) {
            $campus = User::find()
                ->where(['id' => $user_id])
                ->one();
        } else {
            $campus = User::find()
                ->where(['id' => \Yii::$app->user->identity->id])
                ->one();
        }


        if (!empty($campus)) {
            return $campus->campus_id;
        }
    }
    public static function getUserId()
    {
        $campus = User::find()
            ->where(['id' => \Yii::$app->user->identity->id])
            ->one();
        if (!empty($campus)) {
            return $campus->id;
        }
    }

    public static function getAllCampusId()
    {

        $check = User::find()
            ->where(['id' => \Yii::$app->user->identity->id])->andWhere(['user_role' => User::ROLE_LIBRARIAN])
            ->one();
        if (!empty($check)) {
            return $check->campus_id;
        } else {
            $campus = Campus::find()
                ->where(['user_id' => \Yii::$app->user->identity->id])
                ->one();
            if (!empty($campus)) {
                return $campus->id;
            }
        }
    }
    public function shadowLogin($id, $type)
    {
        if ($type == self::login_type_institutes) {
            $institutes = Institutes::find()->where(['id' => $id])->one();
            $user_id  = $institutes->user_id;
            $user = User::find()->where(['id' => $user_id])->one();
            $username = $user->username;
            if (empty(Yii::$app->session->get('loginUserFrom'))) {
                Yii::$app->session->set('loginUserFrom', Yii::$app->user->identity->id);
            }

            Yii::$app->session->set('loginFromInstitute', $institutes->id);
            Yii::$app->session->set('type', $type);
            // Yii::$app->user->logout();
            return Yii::$app->user->login((new LoginForm())->getUser($username), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } elseif ($type == self::login_type_campus) {
            $campus = Campus::find()->where(['id' => $id])->one();
            $user_id  = $campus->user_id;
            $user = User::find()->where(['id' => $user_id])->one();
            $username = $user->username;
            //check campus as Institutes
            $institutes = Institutes::find()->where(['id' => $campus->institute_id])->one();
            if (!empty($institutes)) {
            }
            if (empty(Yii::$app->session->get('loginUserFrom'))) {
                Yii::$app->session->set('loginUserFrom', Yii::$app->user->identity->id);
            }
            Yii::$app->session->set('loginFromInstitute', $campus->institute_id);
            Yii::$app->session->set('type', $type);

            // Yii::$app->user->logout();
            return Yii::$app->user->login((new LoginForm())->getUser($username), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
    }

    public function backToAdmin($user_id)
    {
        $user = User::find()->where(['id' => $user_id])->one();
        $username = $user->username;
        return Yii::$app->user->login((new LoginForm())->getUser($username), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }






    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED]],
            [[
                'first_name',
                'last_name',
                'username',
                'user_role',
                'oauth_client_user_id',
                'oauth_client',
                'profile_image',
                'access_token',
                'device_token',
                'designation_name',
                'bg_color_preference',
                'button_color_preference'
            ], 'string'],
            [['contact_no'], 'integer', 'message' => 'Phone number must be a valid one.'],
            ['contact_no', 'string', 'length' => 10],
            ['phone_number', 'string', 'length' => 10],

            [['username'], 'required'],
            [['username'], 'unique'],
            [['contact_no'], 'required'],
            ['email', 'email'],
            // [['email'], 'unique'],
            [['contact_no'], 'integer'],
            [['referal_id', 'referal_code', 'device_type', 'noline_status', 'signup_type', 'city_id', 'bg_color_preference', 'button_color_preference'], 'safe'],

            ['contact_no', 'validateMobileNumber', 'on' => 'create'],


            [
                [

                    'email',
                    'first_name',


                ],
                'required',
                'on' => [

                    'add-user'
                ]
            ],
            [['first_name'], 'string', 'message' => 'Username cannot be blank.'],
            [
                'newPassword',
                'compare',
                'compareAttribute' => 'confirm_password',
                'on' => [
                    'changepassword'
                ]
            ],



        ];
    }




    public function validateMobileNumber($attribute, $params)
    {
        if (!empty($this->contact_no)) {
            $registration_number = User::find()
                ->where(['contact_no' => $this->contact_no])
                ->andWhere(['user_role' => $this->user_role])
                ->one();
            if (!empty($registration_number)) {
                $this->addError($attribute, 'Phone Number Already exist');
            }
        } else {
            $this->addError($attribute, 'Phone Number Required');
        }
    }









    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['changepassword'] = [
            'newPassword',
            'confirm_password',
            'password'
        ];
        $scenarios['add-user'] = [
            'email',
            'username',
            'first_name',
            'contact_no',
            'user_role',
            'password',
            'passwordRepeat',

            'referal_id',
            'referal_code',
            'access_token'
        ];
        $scenarios['update-profile'] = [
            //'email',
            'first_name',
            'contact_no',
            'phone_number',
            'profile_image',
            'email'

        ];

        $scenarios['facebook-login'] = [
            'email',
            'username',
            'oauth_client_user_id',
            'first_name',
            'oauth_client',
            'profile_image',
            'user_role',
            'status',
            'signup_type'
            //'access_token'

        ];
        $scenarios['phone-login'] = [
            'contact_no',
            'phone_number',
            'device_token',
            'device_type',
            'oauth_client',
            'oauth_client_user_id'
        ];
        $scenarios['rest-user'] = [
            'email',
            'contact_no',
            'phone_number'
        ];

        $scenarios['update-latlong'] = [
            'latitude',
            'longitude',


        ];




        return $scenarios;
    }
    /**
     * User full name
     * (as first/last name)
     *
     * @return string
     */
    public function getFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * User short name
     * (as first name, last name first letter)
     *
     * @return string
     */
    public function getShortName()
    {
        return trim($this->first_name . ' ' . ($this->last_name ? $this->last_name[0] . '.' : ''));
    }

    public function getChangeStatus()
    {
        return [
            self::STATUS_ACTIVE => 'ative',
            self::STATUS_INACTIVE => 'In active',
        ];
    }




    /**
     * List of user status aliases
     *
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            static::STATUS_ACTIVE  => 'Active',
            static::STATUS_BLOCKED => 'Blocked',
            static::STATUS_INACTIVE => 'Inactive',

        ];
    }
    public function stateBadges()
    {
        $states = $this->getStatusesList();
        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">' . $states[self::STATUS_ACTIVE] . '</span>';
        } elseif ($this->status == self::STATUS_BLOCKED) {
            return '<span class="badge badge-default">' . $states[self::STATUS_BLOCKED] . '</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">' . $states[self::STATUS_INACTIVE] . '</span>';
        }
    }

    public function getRoles()
    {
        if (User::isAdmin()) {
            return [

                self::ROLE_ADMIN => 'admin',
                self::ROLE_CAMPUS_ADMIN   => 'Campus Admin',
                self::ROLE_INSTITUTE_ADMIN   => 'Group Of Institute Admin',


            ];
        } elseif (User::isCampusAdmin()) {
            return [


                self::ROLE_CAMPUS_ADMIN  => 'Campus Admin',



            ];
        } elseif (User::isInstituteAdmin()) {
            return [


                self::ROLE_BUS_COORDINATOR  => 'Bus CoOrdinator',



            ];
        }
    }

    public function designations()
    {
        return [
            self::ROLE_AGENT  => 'Agent',
            self::ROLE_BUS_DRIVER  => 'Bus Driver',
            self::ROLE_BUS_COORDINATOR  => 'Bus CoOrdinator',
            self::role_teacher => 'Teacher'
        ];
    }


    public function userHasCampusAccess($user_id)
    {
        $campus_has_users = CampusHasUsers::find()->where(['user_id' => $user_id])->andWhere(['status' => CampusHasUsers::STATUS_ACTIVE])->one();
        if (!empty($campus_has_users)) {
            return true;
        } else {
            return false;
        }
    }

    public function userHasCampusAccessSendOtp($contact_number)
    {
        $campus_has_users = EmployeeDetails::find()->joinWith('user as u')->where(['u.contact_no' => $contact_number])->one();

        if (!empty($campus_has_users)) {
            return true;
        } else {
            return false;
        }
    }



    public function userHasCampus($user_id)
    {
        $campus_has_users = CampusHasUsers::find()->where(['user_id' => $user_id])->andWhere(['status' => CampusHasUsers::STATUS_ACTIVE])->one();
        if (!empty($campus_has_users)) {
            return $campus_has_users;
        } else {
            return;
        }
    }



    public function getUserDataBySubscriptionType($subscription_type)
    {
        if ($subscription_type == Institutes::subscription_type_individual_institution) {
            $users = Campus::find()->all();
            foreach ($users as $usersData) {
                $users_id[] = $usersData->user_id;
            }

            $out = [];
            if (!empty($users_id)) {
                $data = User::find()
                    ->where(['user_role' => User::ROLE_CAMPUS_ADMIN])
                    ->andWhere(['status' => User::STATUS_ACTIVE])
                    ->andWhere(['NOT IN', 'id', $users_id])
                    ->asArray()
                    ->all();
                foreach ($data as $dat) {
                    $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
                }
            } else {
                $data = User::find()
                    ->where(['user_role' => User::ROLE_CAMPUS_ADMIN])
                    ->andWhere(['status' => User::STATUS_ACTIVE])

                    ->asArray()
                    ->all();
                foreach ($data as $dat) {
                    $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
                }
            }

            return $output = [
                'output' => $out
            ];
        } elseif ($subscription_type == Institutes::subscription_type_group_of_institutions) {
            $out = [];
            $users = Institutes::find()->all();
            foreach ($users as $usersData) {
                $users_id[] = $usersData->user_id;
            }


            if (!empty($users_id)) {
                $data = User::find()
                    ->where(['user_role' => User::ROLE_INSTITUTE_ADMIN])
                    ->andWhere(['status' => User::STATUS_ACTIVE])
                    ->andWhere(['NOT IN', 'id', $users_id])
                    ->asArray()
                    ->all();
                foreach ($data as $dat) {
                    $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
                }
            } else {
                $data = User::find()
                    ->where(['user_role' => User::ROLE_INSTITUTE_ADMIN])
                    ->andWhere(['status' => User::STATUS_ACTIVE])
                    ->asArray()
                    ->all();
                foreach ($data as $dat) {
                    $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
                }
            }



            return $output = [
                'output' => $out
            ];
        }
    }




    public function assignRole($role)
    {
        if (!Yii::$app->authManager->checkAccess($this->id, $role)) {
            $authRole = Yii::$app->authManager->getRole($role);
            Yii::$app->authManager->assign($authRole, $this->id);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'access_token' => $token
        ]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = User::find()
            ->where([
                'or',
                ['username' => $username],
                ['email' => $username],
            ])
            //->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
        // var_dump($user);exit;

        return $user;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = '3600';

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        /*if(Yii::$app->security->validatePassword($password, $this->password_hash)){
            return true;
        }else{ return false;}*/
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    //Check Auth

    public function GenerateRandString1($len, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $string = '';
        for ($i = 0; $i < $len; $i++) {
            $pos = rand(0, strlen($chars) - 1);
            $string .= $chars[$pos];
        }
        return $string;
    }


    /**
     * @return \yii\db\ActiveQuery
     */


    public function profileImage($profile_image, $user_name)
    {
        $image = str_replace('data:image/png;base64,', '', $profile_image);
        // $ext = explode(';',explode('data:image/',$_POST['image'])[1])[0];
        if (!empty($image)) {
            $ext = 'png';
            $image = str_replace(' ', '+', $image);

            // Decode the Base64 encoded Image
            $data1 = base64_decode($image);
            // Create Image path with Image name and Extension

            //	$image_name = \Yii::$app->urlManager->createAbsoluteUrl('uploads').'/'.$user_name.'_'.mt_rand().'.'.$ext;
            $image_name = $user_name . '_' . mt_rand() . '.' . $ext;
            $file = 'uploads/' . $image_name;
            // Save Image in the Image Directory
            $success = file_put_contents($file, $data1);
            if ($success === false) {
                $data['profile_image'] = 'Not saved';
            } else {
                return  \Yii::$app->urlManager->createAbsoluteUrl('uploads') . '/' . $image_name;
            }
        }
    }
    public function UserNotification($user_id, $title, $body, $type, $api_key)
    {
        // Custom Notification to Restaurant Owner
        //fesa partener anxion
        $setting = new WebSetting();
        //$api_key = $setting->getSettingBykey('farm_user');

        //$api_key = 'AAAA2c8MJKE:APA91bEq8lyBUfHlNaQ_3TERLsG1P-6oHb9mVlYZpZF9pkLQv-U8rN0WJfMS57h2fRMtLNTatDl2LA1ne4MDCAsoQ2upXI89VOF-i8Jf-rWXx9Ks3s93PdZQBzGiFPXs_15YshHlMhmQ';

        //var_dump($api_key); exit;
        $auth_sess = new \app\modules\admin\models\AuthSession();
        $device_token =  $auth_sess->getDeviceToken($user_id);
        //var_dump($user_id); exit;
        $title = $title;
        $body = $body;
        $type = $type;
        $msg = array(
            'title' =>  $title,
            'body' => $body,
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            'type' => $type,
            //'order_id' => $order_id
            // 'request_id' =>  $id,
        );
        $msg1 = array(
            'title' =>  $title,
            'body' => $body,
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            // 'request_id' =>  $id,
        );
        $fields = array(
            'to' => $device_token,
            'collapse_key' => 'type_a',
            // 'notification' => $msg1,
            'data' => $msg,

        );


        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //var_dump($result); exit;
        curl_close($ch);
        return $result;
    }

    public static function isDynamicUser()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        // $user = Roles::find()->where(['name' => \Yii::$app->user->identity->user_role])->andWhere(['campus_id' => (new User())->getCampusId()])->one();
        // if (!empty($user)) {
        //     return true;
        // }
        return \Yii::$app->user->identity->user_role == self::role_campus_sub_admin;
    }

    public static function isCampusAdmin()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_CAMPUS_ADMIN;
    }


    public static function isInstituteAdmin()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_INSTITUTE_ADMIN;
    }



    public static function isCampusSubAdmin()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::role_campus_sub_admin;
    }

    public static function isLibraryManager()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_LIBRARIAN;
    }







    public static function isAdmin()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_ADMIN;
    }



    public static function isSubAdmin()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_SUBADMIN;
    }



    public static function isChefWarden()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_CHEF_WARDEN;
    }
    public static function isManager()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_MANAGER;
    }



    public static function isUser()
    {
        if (empty(\Yii::$app->user->identity)) {
            return false;
        }
        return \Yii::$app->user->identity->user_role == self::ROLE_USER;
    }

    public function userHasModuleAccess($module_id)
    {
        $UserHasModules = UserHasModules::find()->joinWith('user')
            ->where(['user_has_modules.user_id' => Yii::$app->user->identity->id])
            ->andWhere(['user_has_modules.module_id' => $module_id])
            ->andWhere(['user_has_modules.status' => UserHasModules::STATUS_ACTIVE])
            ->one();
        if (!empty($UserHasModules)) {
            return true;
        } else {
            return false;
        }
    }

    public static function  getCampusesByUser($user_id, $campus_details = '')
    {

        $user  = User::find()->where(['id' => $user_id])->one();
        if (!empty($user)) {
            if (!empty($user->campus_id)) {
                if (!empty($campus_details)) {
                    return $campus = Campus::find()->where(['id' => $user->campus_id])->one();
                } else {
                    return $user->campus_id;
                }
            } else {
                $campus_has_users = CampusHasUsers::find()->where(['user_id' => $user_id])->one();
                if (!empty($campus_has_users)) {
                    if (!empty($campus_details)) {
                        return   $campus = Campus::find()->where(['id' => $campus_has_users->campus_id])->one();
                    } else {
                        return $campus_has_users->campus_id;
                    }
                } else {
                    $campus = Campus::find()->where(['user_id' => $user_id])->one();
                    if (!empty($campus)) {
                        if (!empty($campus_details)) {
                            return $campus;
                        } else {
                            return $campus->id;
                        }
                    } else {
                        return;
                    }
                }
            }
        }
    }


    public function getReferredUserscount($id)
    {
        $referred_user_count = User::find()->where([
            'referal_id' => $id
        ])->count();
        return  $referred_user_count;
    }

    public function asJson()
    {
        $data = [];
        $data['username'] = $this->first_name;
        $data['email'] = $this->email;
        $data['contact_no'] = $this->contact_no;
        $data['address'] = $this->address;
        $data['date_of_birth'] = $this->date_of_birth;
        $data['profile_image'] = $this->profile_image;
        $data['user_role'] = $this->user_role;
        $data['blood_group'] = $this->blood_group;
        $data['gender'] = $this->gender;
        $data['profile_image'] = $this->profile_image;
        $data['campus_id'] = $this->campus_id;
        $data['created_at'] = date('d-m-Y', $this->created_at);
        if ($this->user_role == User::ROLE_AGENT) {
            $employee_details = EmployeeDetails::find()->where(['user_id' => $this->id])->one();
            if (!empty($employee_details)) {
                $data['pay_agent_type'] = $employee_details->agent_type;
                $data['qr_code_file'] = $employee_details->qr_code_file;
            } else {
                $data['pay_agent_type'] = 0;
                $data['qr_code'] = null;
            }
        } else if ($this->user_role == User::role_teacher) {
            $teacher_details = TeacherDetails::find()->where(['user_id' => $this->id])->one();
            if (!empty($teacher_details)) {
                $data['class_teacher'] = true;
                $teacher_attenddence = TeacherAttenddence::find()->where(['teacher_details_id' => $teacher_details->id])->andWhere(['date' => date('Y-m-d')])->one();
                if (!empty($teacher_attenddence)) {
                    $data['login'] = true;
                } else {
                    $data['login'] = false;
                }

                $data['teacher_attenddence_day'] = !empty($teacher_attenddence) ? $teacher_attenddence->asJson() : null;
            } else {
                $data['class_teacher'] = false;
            }
        }



        return $data;
    }



    public function asJsonBusCoordinator()
    {
        $data = [];
        $data['username'] = $this->first_name;
        $data['email'] = $this->email;
        $data['contact_no'] = $this->contact_no;
        $data['address'] = $this->address;
        $data['date_of_birth'] = $this->date_of_birth;
        $data['profile_image'] = $this->profile_image;
        $data['user_role'] = $this->user_role;
        $data['blood_group'] = $this->blood_group;
        $data['gender'] = $this->gender;
        $data['profile_image'] = $this->profile_image;
        $data['employee_id'] = $this->id;

        $data['created_at'] = date('d-m-Y', $this->created_at);
        if ($this->user_role == User::ROLE_AGENT) {
            $employee_details = EmployeeDetails::find()->where(['user_id' => $this->id])->one();
            if (!empty($employee_details)) {
                $data['pay_agent_type'] = $employee_details->agent_type;
                $data['qr_code_file'] = $employee_details->qr_code_file;
            } else {
                $data['pay_agent_type'] = 0;
                $data['qr_code'] = null;
            }
        }



        return $data;
    }

    // public function asJsonForHostel($user_id){

    //     $data = [];

    //     $student_exists = StudentDetails::find()->where(['parent_id' => $user_id])->one();

    //     if(!empty($student_exists)){
    //         $hosteller_exists = Hostellers::find()->where(['student_id' => $student_exists['id']])->one();
    //         if($hosteller_exists){
    //             $data['hostel'] = true;
    //         }
    //     }else{
    //         $data['hostel'] = false;
    //     }

    // }

    public function asJsonForHostel()
    {
        $data = [];
        $data['id'] =  $this->id;

        if (!empty($this->student)) {
            $data['student_detail']['id'] =  $this->student_id;
            $data['student_detail']['name'] =  $this->student->student_name ?? "";
            $data['student_detail']['gender'] =  $this->gender ?? "";
            $data['student_detail']['rool_number'] =  $this->rool_number ?? "";
            $data['student_detail']['admission_number'] =  $this->admission_number ?? "";
            $data['student_detail']['date_of_birth'] =  $this->date_of_birth ?? "";
            $parentDetails = ParentDetails::find()->where(['id' => $this->student->parent_id])->one();
            $data['student_detail']['name_of_the_father'] =  $parentDetails->name_of_the_father ?? "";
            $data['student_detail']['contact_number'] =  $parentDetails->contact_number ?? "";
        } else {
            $data['student_detail'] = [];
        }



        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['hostel_name'] = $this->hostel->name;

        $data['hostel_type'] = $this->hostel->type_id;

        $data['joining_date'] =  $this->joining_date;

        $data['bill_date'] =  $this->bill_date;

        $data['next_bill_date'] =  $this->next_bill_date;

        $data['sty_type'] =  $this->sty_type;

        $data['advance_payment'] =  $this->advance_payment;

        $data['fees'] =  $this->fees;

        $data['room']['id'] =  $this->room_id;

        $data['room']['name'] =  $this->room->name_of_the_room;

        $data['room']['no_of_beds'] =  $this->room->no_of_beds;

        $data['address'] =  $this->address;

        $data['aadhar_number'] =  $this->aadhar_number;

        $data['photo'] =  $this->photo;

        $data['aadhar_front'] =  $this->aadhar_front;

        $data['aadhar_back'] =  $this->aadhar_back;

        $data['application_form_file'] =  $this->application_form_file;

        $data['leave_of_date'] =  $this->leave_of_date;

        $data['leave_month'] =  $this->leave_month;

        $data['is_all_items_checked'] =  $this->is_all_items_checked;

        $data['is_balance_amount_paid'] =  $this->is_balance_amount_paid;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }



    public function asWardenList()
    {
        $data = [];
        $data['id'] = $this->id;
        $data['username'] = $this->first_name;
        $data['email'] = $this->email;
        $data['contact_no'] = $this->contact_no;
        $data['address'] = $this->address;
        $data['date_of_birth'] = $this->date_of_birth;
        $data['profile_image'] = $this->id;
        $data['user_role'] = $this->user_role;
        $data['blood_group'] = $this->blood_group;
        $data['gender'] = $this->gender;
        $data['profile_image'] = $this->profile_image;
        $data['campus_id'] = $this->campus_id;

        $wardenAttandance = WardenAttandance::find()->where(['warden_id' => $this->id])->andWhere(['DATE(date)' => date('Y-m-d')])->one();
        if (!empty($wardenAttandance)) {
            $data['attandance']['id'] = $wardenAttandance->id;
            $data['attandance']['present_or_absent'] = $wardenAttandance->attandance;
        } else {
            $data['attandance']['id'] = 0;
            $data['attandance']['present_or_absent'] = WardenAttandance::NOT_MARKED;
        }


        return $data;
    }
}
