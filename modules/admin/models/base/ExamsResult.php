<?php


namespace app\modules\admin\models\base;

use app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "exams_result".
 *
 * @property integer $exams_result_id
 * @property integer $campus_id
 * @property integer $exam_id
 * @property integer $academic_year_id
 * @property integer $student_id
 * @property integer $class_id
 * @property integer $section_id
 * @property string $marks_sheet
 * @property double $percentage_or_gpa
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentClass $calss
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\Exams $exam
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\AcademicYears $academicYear
 */
class ExamsResult extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $displayed_value;




    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'class',
            'section',
            'campus',
            'student',
            'exam',
            'createUser',
            'updateUser',
            'academicYear',
            'subject',
            'marksDevisionResult'
        ];
    }

    const MARKS_UPDATED = 1;
    const Marks_not_Updated = 0;


    const PRESENT = 1;
    const ABSENT = 2;
    const NOT_MARKED = 3;


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
            [['campus_id', 'exam_id', 'academic_year_id', 'student_id', 'class_id', 'section_id', 'marks_sheet', 'percentage_or_gpa', 'marks_type'], 'required'],
            [['campus_id', 'exam_id', 'academic_year_id', 'student_id', 'class_id', 'section_id', 'status', 'create_user_id', 'update_user_id', 'marks_type'], 'integer'],
            [['percentage_or_gpa'], 'number'],
            [['created_on', 'updated_on'], 'safe'],
            [['marks_sheet'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exams_result';
    }




    public function getMarksTypeOptions()
    {
        return [

            self::marks_type_grade => 'Grade',
            self::marks_type_gpa => 'GPA',


        ];
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




    public function getMarksTypeBadges()
    {

        if ($this->marks_type == self::marks_type_grade) {
            return '<span class="badge badge-success">Grade</span>';
        } elseif ($this->marks_type == self::marks_type_gpa) {
            return '<span class="badge badge-default">CGPA</span>';
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
            'exams_result_id' => Yii::t('app', 'Exams Result'),
            'campus_id' => Yii::t('app', 'Campus'),
            'exam_id' => Yii::t('app', 'Exam '),
            'academic_year_id' => Yii::t('app', 'Academic Year '),
            'student_id' => Yii::t('app', 'Student '),
            'class_id' => Yii::t('app', 'CLass'),
            'section_id' => Yii::t('app', 'Section '),
            'subject_id' => Yii::t('app', 'Subject '),
            'marks_sheet' => Yii::t('app', 'Marks Sheet'),
            'percentage_or_gpa' => Yii::t('app', 'Percentage Or Gpa'),
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
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }


    public function getMarksDevisionResult()
    {
        return $this->hasMany(ScheduledExamMarksDevisionResults::className(), ['exam_result_id' => 'exams_result_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExam()
    {
        return $this->hasOne(\app\modules\admin\models\Exams::className(), ['id' => 'exam_id']);
    }


    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
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
    public function getSubjects($type)
    {

        $dat = [];
        $subjectName = [];
        $mod = [];
        $subjectGroupSection = SubjectGroupsClassSections::find()->where(['class_sections_id' => $type])->one();
        if (!empty($subjectGroupSection)) {

            $subjectGroupSubjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subjectGroupSection->subject_group_id])->all();
            if (!empty($subjectGroupSubjects)) {
                foreach ($subjectGroupSubjects as $subjectGroupSubject) {
                    $dat[] = ['id' => $subjectGroupSubject->subject_id, 'name' => $subjectGroupSubject->subject->subject_name];
                }
            } else {
                $dat = [];
            }
        } else {
            $dat = [];
        }




        return ['output' => $dat];
    }


    /**
     * @inheritdoc
     * @return \app\modules\admin\models\ExamsResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\ExamsResultQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['exams_result_id'] =  $this->exams_result_id;

        $data['campus_id'] =  $this->campus_id;

        $data['exam_id'] =  $this->exam_id;
        $data['exam'] = !empty($this->name_of_exam) ? $this->name_of_exam : '';

        $data['academic_year_id'] =  $this->academic_year_id;
        $data['academicYear'] =  !empty($this->academicYear->title) ? $this->academicYear->title : '';

        $data['student_id'] =  $this->student_id;

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['marks_sheet'] =  $this->marks_sheet;

        $data['percentage_or_gpa'] =  $this->percentage_or_gpa;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }



    public function asJsonsStudentProfile()
    {
        $data = [];
        $data['exams_result_id'] =  $this->exams_result_id;

        $data['campus_id'] =  $this->campus_id;
        $data['academic_tear'] =  $this->academicYear->title;

        $data['exam_id'] =  $this->exam_id;
        $data['exam'] =  $this->exam->name_of_exam;
        $data['marks_type'] =  $this->exam->marks_type;


        $data['marks_sheet'] =  $this->marks_sheet;

        $data['percentage_or_gpa'] =  $this->percentage_or_gpa;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }


    public function asStudentListJson()
    {
        $data = [];
        $data['exams_result_id'] =  $this->exams_result_id;

        $data['campus_id'] =  $this->campus_id;

        $data['exam_id'] =  $this->exam_id;

        $data['exam_scheduled_id'] =  $this->exam_scheduled_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['student']['id'] =  $this->student_id;
        $data['student']['user_id'] =  $this->student->user_id ?? "";
        $data['student']['student_name'] =  $this->student->student_name ?? "";
        $data['student']['profile_photo'] =  $this->student->profile_photo ?? "";
        $data['student']['rool_number'] =  $this->student->rool_number ?? "";

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['marks_sheet'] =  isset($this->marks_sheet) ? $this->marks_sheet : Null;

        $data['user_id'] =  $this->user_id;

        $data['subject_id'] =  $this->subject_id;

        $data['total_marks'] =  $this->total_marks;

        $data['min_marks'] =  $this->min_marks;

        $data['attandance'] =  $this->attandance;

        $data['marks_scored'] =  $this->marks_scored;

        $data['pecentage'] =  $this->pecentage;

        $data['grade'] =  $this->grade;

        $data['cgpa'] =  $this->cgpa;

        $data['percentage_or_gpa'] =  $this->percentage_or_gpa;

        $data['marks_type'] =  $this->marks_type;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
