<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_class_attendance".
 * 
 * @property integer $id
 * @property integer $student_id
 * @property integer $teacher_id
 * @property integer $subject_timetable_id
 * @property integer $academic_year_id
 * @property integer $subject_group_id
 * @property integer $subject_id
 * @property string $date
 * @property string $mode
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\TeacherDetails $teacher
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\SubjectTimetable $subjectTimetable
 * @property \app\modules\admin\models\SubjectGroups $subjectGroup
 * @property \app\modules\admin\models\Subjects $subject
 */
class StudentClassAttendance extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $section_id;
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {

        return [
            'academicYear',
            'student',
            'teacher',
            'updateUser',
            'createUser',
            'subjectTimetable',
            'subjectGroup',
            'subject'
        ];
    }



    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 2;
    const STATUS_LEAVE = 3;
    const STATUS_UNMARKED = 4;
    const MANUAL_MODE =1;
    const FACE_MODE =2;


    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
    public $class_id; // This can represent class title in your logic
    // public $student_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'teacher_id', 'subject_timetable_id', 'academic_year_id', 'subject_group_id', 'subject_id', 'date'], 'required'],
            [['student_id', 'teacher_id', 'subject_timetable_id', 'academic_year_id', 'subject_group_id', 'subject_id', 'status', 'mode','create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'section_id', 'updated_on', 'class_id', 'mode','student_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_class_attendance';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_PRESENT) {
            return '<span class="badge badge-success">Present</span>';
        } elseif ($this->status == self::STATUS_ABSENT) {
            return '<span class="badge badge-danger">Absent</span>';
        }
    }
    public function getModeOptions()
    {
        return [

            self::MANUAL_MODE => 'Manual',
            self::FACE_MODE => 'Face',

        ];
    }
    public function getModeOptionsBadges()
    {

        if ($this->mode == self::MANUAL_MODE) {
            return '<span class="badge badge-success">Manual</span>';
        } elseif ($this->mode == self::FACE_MODE) {
            return '<span class="badge badge-danger">Face</span>';
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
            'student_id' => Yii::t('app', 'Student ID'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'subject_timetable_id' => Yii::t('app', 'Subject Timetable ID'),
            'academic_year_id' => Yii::t('app', 'Academic Year ID'),
            'subject_group_id' => Yii::t('app', 'Subject Group ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'date' => Yii::t('app', 'Date'),
            'mode' => Yii::t('app', 'mode'),
            'status' => Yii::t('app', 'Status'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_id']);
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
    public function getSubjectTimetable()
    {
        return $this->hasOne(\app\modules\admin\models\SubjectTimetable::className(), ['id' => 'subject_timetable_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectGroup()
    {
        return $this->hasOne(\app\modules\admin\models\SubjectGroups::className(), ['id' => 'subject_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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
     * @return \app\modules\admin\models\StudentClassAttendanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentClassAttendanceQuery(get_called_class());
    }


    public function asJson()
    {
        $data = [];
        $data['student_class_attendance_id'] =  $this->id;

        $data['student_id'] =  $this->student_id;

        $data['teacher_id'] =  $this->teacher_id;

        $data['teacher'] = $this->teacher->name ?? "";

        $data['subject_timetable_id'] =  $this->subject_timetable_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['subject_group_id'] =  $this->subject_group_id;

        $data['subject_id'] =  $this->subject_id;

        $data['subject'] = $this->subject->subject_name;

        $data['date'] =  $this->date;
        $data['mode'] =  $this->mode;
        $data['period'] =  $this->period;

        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }




    public function asJsonDailyAttendance()
    {
        $data = [];
        $data['student_class_attendance_id'] =  $this->id;
        // $model = StudentClassAttendance::findOne($this->id);
        // var_dump($model);
        // exit;
        $data['student_details'] = isset($this->student) && method_exists($this->student, 'asJsonStudentClassAttendanceTeacher') 
    ? $this->student->asJsonStudentClassAttendanceTeacher() 
    : '';


        $data['teacher_id'] =  $this->teacher_id;

        $data['subject_timetable_id'] =  $this->subject_timetable_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['subject_group_id'] =  $this->subject_group_id;

        $data['subject_id'] =  $this->subject_id;

        $data['date'] =  $this->date;
        $data['period'] =  $this->period;

        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }
}
