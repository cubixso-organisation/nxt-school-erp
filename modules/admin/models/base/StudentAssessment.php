<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\StudentHasAssessment;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_assessment".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $teacher_details_id
 * @property integer $subject_timetable_id
 * @property integer $academic_year_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $subject_id
 * @property string $assessment
 * @property string $submission_date
 * @property string $document
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\Subjects $subject
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\StudentHasAssessment[] $studentHasAssessments
 */
class StudentAssessment extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'class',
            'section',
            'academicYear',
            'teacherDetails',
            'subject',
            'updateUser',
            'createUser',
            'studentHasAssessments'
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
            [['section_id',  'assessment'], 'required'],
            [['campus_id', 'teacher_details_id', 'subject_timetable_id', 'academic_year_id', 'class_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['assessment'], 'string'],
            [['submission_date', 'created_on', 'updated_on'], 'safe'],
            [['document'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_assessment';
    }

    public function getStateOptions()
    {
        return [];
    }
    public function getStateOptionsBadges()
    {

        // if ($this->status == self::STATUS_ACTIVE) {
        //     return '<span class="badge badge-success">Active</span>';
        // } elseif ($this->status == self::STATUS_INACTIVE) {
        //     return '<span class="badge badge-default">Inactive</span>';
        // }elseif ($this->status == self::STATUS_DELETE) {
        //     return '<span class="badge badge-danger">Deleted</span>';
        // }

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
            'teacher_details_id' => Yii::t('app', 'Teacher Details ID'),
            'subject_timetable_id' => Yii::t('app', 'Subject Timetable ID'),
            'academic_year_id' => Yii::t('app', 'Academic Year ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'assessment' => Yii::t('app', 'Assessment'),
            'submission_date' => Yii::t('app', 'Submission Date'),
            'document' => Yii::t('app', 'Document'),
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
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
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
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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
    public function getStudentHasAssessments()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasAssessment::className(), ['student_assessment_id' => 'id']);
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
     * @return \app\modules\admin\models\StudentAssessmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentAssessmentQuery(get_called_class());
    }




    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['teacher_details_id'] =  $this->teacher_details_id;

        $data['teacher_name'] =  $this->teacherDetails->name;

        $data['subject_timetable_id'] =  $this->subject_timetable_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['subject_id'] =  $this->subject_id;

        $data['subject_details'] = $this->subject->asJson();
        $data['subject_name'] = !empty($this->subject->subject_name) ? $this->subject->subject_name : '';

        $data['assessment'] =  $this->assessment;

        $data['submission_date'] =  $this->submission_date;

        $data['document'] =  $this->document;

        $student_has_assessment = StudentHasAssessment::find()->where(['student_assessment_id' => $this->id])->all();
        if (!empty($student_has_assessment)) {
            foreach ($student_has_assessment as $student_has_assessment_data) {
                $data['student_details'][] = $student_has_assessment_data->asJsonList();
            }
        } else {
            $data['student_details'][] =  '';
        }

        $data['file_type'] =  $this->file_type;


        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }



    public function asJsonClassWiseAssignment()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['teacher_details_id'] =  $this->teacher_details_id;

        $data['teacher_name'] =  $this->teacherDetails->name;

        $data['subject_timetable_id'] =  $this->subject_timetable_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['class_id'] =  $this->class_id;

        $data['class_details'] = $this->class->asJson();

        $data['section_id'] =  $this->section_id;

        $data['section_details'] =  $this->section->asJson();

        $data['subject_id'] =  $this->subject_id;

        $data['subject_details'] = $this->subject->asJson();

        $data['assessment'] =  $this->assessment;

        $data['submission_date'] =  $this->submission_date;

        $data['document'] =  $this->document;


        $data['file_type'] =  $this->file_type;


        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }




    public function asJsonList()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['teacher_details_id'] =  $this->teacher_details_id;

        $data['teacher_name'] =  $this->teacherDetails->name;

        $data['subject_timetable_id'] =  $this->subject_timetable_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['subject_id'] =  $this->subject_id;


        $data['assessment'] =  $this->assessment;

        $data['submission_date'] =  $this->submission_date;

        $data['document'] =  $this->document;

        $student_has_assessment = StudentHasAssessment::find()->where(['student_assessment_id' => $this->id])->all();
        if (!empty($student_has_assessment)) {
            foreach ($student_has_assessment as $student_has_assessment_data) {
                if(!empty($student_has_assessment_data->student_id)){

                    
                    $data['student_details'][] = $student_has_assessment_data->asJsonList();
                }
            }
        } else {
            $data['student_details'][] =  '';
        }

        $data['file_type'] =  $this->file_type;


        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }
}
