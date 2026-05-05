<?php


namespace app\modules\exammanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "exam_student_marksheet".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $student_id
 * @property integer $session_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $exam_id
 * @property double $total_marks
 * @property double $total_percentage
 * @property integer $marks_type
 * @property string $total_grade
 * @property double $total_cgpa
 * @property string $marksheet_url
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\exammanagement\models\StudentClass $class
 * @property \app\modules\exammanagement\models\ClassSections $section
 * @property \app\modules\exammanagement\models\User $user
 * @property \app\modules\exammanagement\models\StudentDetails $student
 * @property \app\modules\exammanagement\models\Exams $exam
 * @property \app\modules\exammanagement\models\AcademicYears $session
 */
class ExamStudentMarksheet extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'class',
            'section',
            'user',
            'student',
            'exam',
            'session', 'campus'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;



    const marks_type_grade = 1;
    const marks_type_gpa = 2;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'student_id', 'session_id', 'class_id', 'section_id', 'exam_id', 'total_marks', 'total_percentage', 'marks_type', 'total_grade', 'total_cgpa', 'marksheet_url', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['user_id', 'student_id', 'session_id', 'class_id', 'section_id', 'exam_id', 'marks_type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_marks', 'total_percentage', 'total_cgpa'], 'number'],
            [['marksheet_url'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['total_grade'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_student_marksheet';
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
    public function getMarksTypeOptions()
    {
        return [

            self::marks_type_grade => 'Grade',
            self::marks_type_gpa => 'GPA',


        ];
    }




    public function getMarksTypeBadges()
    {

        if ($this->marks_type == self::marks_type_grade) {
            return '<span class="badge badge-success">Grade</span>';
        } elseif ($this->marks_type == self::marks_type_gpa) {
            return '<span class="badge badge-default">CGPA</span>';
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
            'student_id' => Yii::t('app', 'Student Name'),
            'session_id' => Yii::t('app', 'Session'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'exam_id' => Yii::t('app', 'Exam'),
            'total_marks' => Yii::t('app', 'Total Marks'),
            'total_percentage' => Yii::t('app', 'Total Percentage'),
            'marks_type' => Yii::t('app', 'Marks Type'),
            'total_grade' => Yii::t('app', 'Total Grade'),
            'total_cgpa' => Yii::t('app', 'Total Cgpa'),
            'marksheet_url' => Yii::t('app', 'Marksheet Url'),
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
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
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
    public function getUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'user_id']);
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
    public function getExam()
    {
        return $this->hasOne(\app\modules\admin\models\Exams::className(), ['id' => 'exam_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'session_id']);
    }

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
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\exammanagement\models\ExamStudentMarksheetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\ExamStudentMarksheetQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['user_id'] =  $this->user_id;

        $data['student_id'] =  $this->student_id;

        $data['session_id'] =  $this->session_id;

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['exam_id'] =  $this->exam_id;

        $data['total_marks'] =  $this->total_marks;

        $data['total_percentage'] =  $this->total_percentage;

        $data['marks_type'] =  $this->marks_type;

        $data['total_grade'] =  $this->total_grade;

        $data['total_cgpa'] =  $this->total_cgpa;

        $data['marksheet_url'] =  $this->marksheet_url;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
