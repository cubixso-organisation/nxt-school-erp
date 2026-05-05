<?php

namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_details_agent_lead".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $agent_id
 * @property string $profile_photo
 * @property string $student_name
 * @property string $gender
 * @property string $date_of_birth
 * @property string $name_of_the_parent
 * @property string $phone_number
 * @property integer $verified_phone
 * @property string $previous_school_name
 * @property string $previous_school_address
 * @property integer $student_class_id
 * @property integer $special_courses_id
 * @property integer $section_id
 * @property string $academic_year
 * @property integer $hostal_is_required
 * @property integer $bus_transport_required
 * @property integer $status
 * @property string $created_on 
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\User $agent
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\StudentClass $studentClass
 * @property \app\modules\admin\models\ClassSections $section
 */
class StudentDetailsAgentLead extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $amount;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'agentStudentJoins',
            'campus',
            'agent',
            'updateUser',
            'createUser',
            'studentClass',
            'section',
            'specialCourses'
        ];
    }

    public $admissions;
    public $payment_status;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;

    public const status_admission_ok = 3;
    public const status_admission_not_ok = 4;


    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    public const verified_phone_yes = 1;
    public const verified_phone_no = 0;

    public const hostel_is_required_yes = 1;
    public const hostel_is_required_no = 0;


    public const bus_transport_required_yes = 1;
    public const bus_transport_required_no = 0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'agent_id', 'student_name', 'gender', 'date_of_birth', 'name_of_the_parent', 'phone_number', 'student_class_id', 'hostal_is_required', 'bus_transport_required'], 'required'],
            [['id', 'campus_id', 'agent_id', 'student_class_id', 'special_courses_id', 'section_id', 'hostal_is_required', 'bus_transport_required', 'status', 'create_user_id', 'update_user_id', 'gender'], 'integer'],
            [['previous_school_name'], 'string'],
            [['created_on', 'updated_on','previous_student_class'], 'safe'],
            [['profile_photo', 'student_name', 'date_of_birth', 'name_of_the_parent', 'phone_number', 'previous_school_address', 'academic_year'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 10],
            [['verified_phone'], 'string', 'max' => 1],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_details_agent_lead';
    }




    public function getStateOptions()
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',
            self::status_admission_ok => 'Admission Ok',
            self::status_admission_not_ok => 'Admission Not',


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
        } elseif ($this->status == self::status_admission_ok) {
            return '<span class="badge badge-danger">Admission Ok</span>';
        } elseif ($this->status == self::status_admission_not_ok) {
            return '<span class="badge badge-danger">Admission Not Done</span>';
        }
    }



    public function getStatePhoneNumberVerified()
    {
        if ($this->verified_phone == self::verified_phone_yes) {
            return '<span class="badge badge-success">Yes</span>';
        } elseif ($this->verified_phone == self::verified_phone_no) {
            return '<span class="badge badge-danger">No</span>';
        }
    }



    public function getStateHostel()
    {
        if ($this->hostal_is_required == self::hostel_is_required_yes) {
            return '<span class="badge badge-success">Yes</span>';
        } elseif ($this->hostal_is_required == self::hostel_is_required_no) {
            return '<span class="badge badge-danger">No</span>';
        }
    }



    public function getStateTransport()
    {
        if ($this->bus_transport_required == self::bus_transport_required_yes) {
            return '<span class="badge badge-success">Yes</span>';
        } elseif ($this->bus_transport_required == self::bus_transport_required_no) {
            return '<span class="badge badge-danger">No</span>';
        }
    }








    public function getGender()
    {
        return [

            self::IS_FEATURED => 'Male',
            self::IS_NOT_FEATURED => 'Female',

        ];
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
            'agent_id' => Yii::t('app', 'Agent'),
            'profile_photo' => Yii::t('app', 'Profile Photo'),
            'student_name' => Yii::t('app', 'Student Name'),
            'gender' => Yii::t('app', 'Gender'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'name_of_the_parent' => Yii::t('app', 'Name Of The Parent'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'verified_phone' => Yii::t('app', 'Verified Phone'),
            'previous_school_name' => Yii::t('app', 'Previous School Name'),
            'previous_school_address' => Yii::t('app', 'Previous School Address'),
            'student_class_id' => Yii::t('app', 'Student Class ID'),
            'special_courses_id' => Yii::t('app', 'Special Courses ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'academic_year' => Yii::t('app', 'Academic Year'),
            'hostal_is_required' => Yii::t('app', 'Hostal Is Required'),
            'bus_transport_required' => Yii::t('app', 'Bus Transport Required'),
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
    public function getAgentStudentJoins()
    {
        return $this->hasOne(\app\modules\admin\models\AgentStudentJoin::className(), ['student_id' => 'id']);
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
    public function getSpecialCourses()
    {
        return $this->hasOne(\app\modules\admin\models\SpecialCourses::className(), ['id' => 'special_courses_id']);
    }




    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'agent_id']);
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
    public function getStudentClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'student_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
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
     * @return \app\modules\admin\models\StudentDetailsAgentLeadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentDetailsAgentLeadQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['student_id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['agent_id'] =  $this->agent_id;

        $data['profile_photo'] =  $this->profile_photo;

        $data['student_name'] =  $this->student_name;

        $data['gender'] =  $this->gender;

        $data['date_of_birth'] =  $this->date_of_birth;

        $data['name_of_the_parent'] =  $this->name_of_the_parent;

        $data['phone_number'] =  $this->phone_number;

        $data['verified_phone'] =  $this->verified_phone;

        $data['previous_school_name'] =  $this->previous_school_name;

        $data['previous_school_address'] =  $this->previous_school_address;

        $data['previous_student_class'] = $this->previous_student_class;

        // var_dump($this->studentClass);exit;
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class']['id'] =  "";
            $data['student_class']['campus_id'] =  "";

            $data['student_class']['title'] =  "";

            $data['student_class']['status'] =  "";

            $data['student_class']['created_on'] =  "";

            $data['student_class']['updated_on'] =  "";

            $data['student_class']['create_user_id'] =  "";

            $data['student_class']['update_user_id'] =  "";
        }


        $data['special_courses_id'] =  $this->special_courses_id;


        $data['payment_details'] =  $this->agentStudentJoins->asJson();



        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }

        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $this->id])->one();
        if (!empty($agent_student_join)) {
            $data['amount'] = $agent_student_join->amount;
        } else {
            $data['amount'] = 0;
        }




        $data['academic_year'] =  $this->academic_year;

        $data['hostal_is_required'] =  $this->hostal_is_required;

        $data['bus_transport_required'] =  $this->bus_transport_required;

        $data['status'] =  $this->status;
        $data['created_on'] =  $this->created_on;




        return $data;
    }
}
