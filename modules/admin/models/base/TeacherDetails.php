<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\StudentDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "teacher_details".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $campus_id
 * @property string $name
 * @property string $profile_image
 * @property integer $class_id 
 * @property integer $section_id
 * @property string $id_number
 * @property string $date_of_birth
 * @property integer $academic_year_id
 * @property integer $gender
 * @property integer $blood_group_id
 * @property string $father_name
 * @property string $contact_number
 * @property string $email
 * @property string $address
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\ClassTeacher[] $classTeachers
 * @property \app\modules\admin\models\StudentClassAttendance[] $studentClassAttendances
 * @property \app\modules\admin\models\SubjectTimetable[] $subjectTimetables
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\BloodGroups $bloodGroup
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\TeacherHasStudents[] $teacherHasStudents
 */
class TeacherDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $fileImport;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'classTeachers',
            'studentClassAttendances',
            'subjectTimetables',
            'campus',
            'class',
            'user',
            'updateUser',
            'createUser',
            'academicYear',
            'bloodGroup',
            'section',
            'teacherHasStudents',
            'studentDairies',

        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


    const male = 1;
    const female = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'campus_id', 'class_id', 'section_id', 'academic_year_id', 'gender', 'blood_group_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['campus_id', 'name',  'id_number', 'status', 'date_of_birth', 'gender', 'blood_group_id', 'father_name', 'contact_number', 'email', 'address'], 'required'],
            [['date_of_birth', 'created_on', 'updated_on', 'fileImport'], 'safe'],
            [['address'], 'string'],
            [['name', 'profile_image', 'id_number', 'father_name', 'email'], 'string', 'max' => 255],
            [['contact_number'], 'string', 'max' => 10],
            ['contact_number', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\admin\models\base\TeacherDetails', 'message' => 'This email address has already been taken.'],
            ['contact_number', 'unique', 'targetClass' => 'app\modules\admin\models\base\TeacherDetails', 'message' => 'This contact number  has already been taken.'],
            [['profile_image'], 'required', 'on' => 'create'],



        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_details';
    }


    public function getGenderOptions()
    {
        return [

            self::male => 'Male',
            self::female => 'Female',

        ];
    }


    public function getGenderOptionsBadges()
    {

        if ($this->gender == self::male) {
            return '<span class="badge badge-success">Male</span>';
        } elseif ($this->gender == self::female) {
            return '<span class="badge badge-default">Female</span>';
        }
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
            'user_id' => Yii::t('app', 'User'),
            'campus_id' => Yii::t('app', 'Campus'),
            'name' => Yii::t('app', 'Name'),
            'profile_image' => Yii::t('app', 'Profile Image'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'id_number' => Yii::t('app', 'Id Number'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'academic_year_id' => Yii::t('app', 'Academic Year'),
            'gender' => Yii::t('app', 'Gender'),
            'blood_group_id' => Yii::t('app', 'Blood Group'),
            'father_name' => Yii::t('app', 'Father Name'),
            'contact_number' => Yii::t('app', 'Contact Number'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTeachers()
    {
        return $this->hasMany(\app\modules\admin\models\ClassTeacher::className(), ['teacher_details_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClassAttendances()
    {
        return $this->hasMany(\app\modules\admin\models\StudentClassAttendance::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectTimetables()
    {
        return $this->hasMany(\app\modules\admin\models\SubjectTimetable::className(), ['teacher_details_id' => 'id']);
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
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
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
    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroup()
    {
        return $this->hasOne(\app\modules\admin\models\BloodGroups::className(), ['id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherHasStudents()
    {
        return $this->hasMany(\app\modules\admin\models\TeacherHasStudents::className(), ['teacher_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDairies()
    {
        return $this->hasMany(\app\modules\admin\models\StudentDairy::className(), ['teacher_details_id' => 'id']);
    }


    public function getAcademicId()
    {
        $academic_year_id = !empty($this->campus->academic_year) ? $this->campus->academic_year : '';
        return $academic_year_id;
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
     * @return \app\modules\admin\models\TeacherDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TeacherDetailsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['user_id'] =  $this->user_id;

        $data['campus_id'] =  $this->campus_id;

        $data['campus_name'] = $this->campus->name_of_the_educational_Institution;

        $data['name'] =  $this->name;

        $data['profile_image'] =  $this->profile_image;

        $data['class_id'] =  $this->class_id;

        if (!empty($this->class_id)) {
            $data['class_details'] = $this->class->asJson();
        } else {
            $data['class_details'] = Null;
        }



        $data['section_id'] =  $this->section_id;
        if (!empty($this->section_id)) {
            $data['section_details'] = $this->section->asJson();
        } else {
            $data['section_details'] = '';
        }


        $data['id_number'] =  $this->id_number;

        $data['date_of_birth'] =  $this->date_of_birth;

        $data['academic_year_id'] = $this->getAcademicId();


        $data['academic_year'] = !empty($this->campus->academicYear->title) ? $this->campus->academicYear->title : 'Academic Year Not Set';

        $data['gender'] =  $this->gender;

        $data['blood_group_id'] =  !empty($this->bloodGroup->title) ? $this->bloodGroup->title : '';

        $data['father_name'] =  $this->father_name;

        $data['contact_number'] =  $this->contact_number;

        $data['email'] =  $this->email;

        $data['address'] =  $this->address;

        $data['designation'] =  '';

        $student_details_count = StudentDetails::find()->where(['student_class_id' => $this->class_id])->andWhere(['section_id' => $this->section_id])->andWhere(['academic_year_id' => $this->getAcademicId()])->andWhere(['<>', 'status', 3])->count();

        $data['student_details_count'] = !empty($student_details_count) ? $student_details_count : 0;
        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }




    public function asJsonCommon()
    {
        $data = [];
        $data['id'] =  $this->id;
        $data['campus_name'] = $this->campus->name_of_the_educational_Institution;
        $data['name'] =  $this->name;

        $data['profile_image'] =  $this->profile_image;

        $data['gender'] =  $this->gender;

        $data['contact_number'] =  $this->contact_number;

        $data['email'] =  $this->email;



        return $data;
    }
}
