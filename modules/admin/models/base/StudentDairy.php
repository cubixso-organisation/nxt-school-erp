<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "student_dairy".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $teacher_details_id
 * @property integer $subject_timetable_id
 * @property integer $academic_year_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $subject_id
 * @property string $dairy
 * @property string $remarks
 * @property string $submission_date
 * @property string $document
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\SubjectTimetable $subjectTimetable
 * @property \app\modules\admin\models\Subjects $subject
 * @property \app\modules\admin\models\StudentHasDairy[] $studentHasDairies
 */
class StudentDairy extends \yii\db\ActiveRecord
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
            'academicYear',
            'class',
            'section',
            'updateUser',
            'createUser',
            'teacherDetails',
            'subjectTimetable',
            'subject',
            'studentHasDairies'
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
            [['campus_id', 'teacher_details_id', 'subject_timetable_id', 'academic_year_id', 'class_id', 'section_id', 'subject_id', 'dairy', 'remarks', 'submission_date'], 'required'],
            [['campus_id', 'teacher_details_id', 'subject_timetable_id', 'academic_year_id', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['dairy', 'remarks'], 'string'],
            [['submission_date', 'created_on', 'updated_on'], 'safe'],
            [['document'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_dairy';
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
        }elseif ($this->status == self::STATUS_DELETE) {
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'teacher_details_id' => Yii::t('app', 'Teacher Details ID'),
            'subject_timetable_id' => Yii::t('app', 'Subject Timetable ID'),
            'academic_year_id' => Yii::t('app', 'Academic Year ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'dairy' => Yii::t('app', 'Dairy'),
            'remarks' => Yii::t('app', 'Remarks'),
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
    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
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
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
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
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasDairies()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasDairy::className(), ['student_dairy_id' => 'id']);
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
     * @return \app\modules\admin\models\StudentDairyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentDairyQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['teacher_details_id'] =  $this->teacher_details_id;
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['class_id'] =  $this->class_id;
        
                $data['section_id'] =  $this->section_id;
        
                $data['subject_id'] =  $this->subject_id;
        
                $data['dairy'] =  $this->dairy;
        
                $data['remarks'] =  $this->remarks;
        
                $data['submission_date'] =  $this->submission_date;
        
                $data['document'] =  $this->document;
        
                $data['status'] =  $this->status;

                $student_has_dairy = StudentHasDairy::find()->where(['student_dairy_id'=>$this->id])->all();
                if(!empty($student_has_dairy)){
                    foreach($student_has_dairy as $student_has_dairy_data){
                        $data['student_details'][]  = $student_has_dairy_data->student->asJsonDairy($student_has_dairy_data->id);
                    }

                }else{
                    $data['student_details'][] =  '';

                }

                $data['file_type'] =  $this->file_type;

        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}
 



 



public function asJsonDairyList(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['teacher_details_id'] =  $this->teacher_details_id;
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['class_id'] =  $this->class_id;
        
                $data['section_id'] =  $this->section_id;
        
                $data['subject_id'] =  $this->subject_id;
        
                $data['dairy'] =  $this->dairy;
        
                $data['remarks'] =  $this->remarks;
        
                $data['submission_date'] =  $this->submission_date;
        
                $data['document'] =  $this->document;
        
                $data['status'] =  $this->status;
                $data['file_type'] =  $this->file_type;

                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}
 

public function asJsonParent(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['teacher_details'] =  $this->teacherDetails->asJsonCommon();
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['class_id'] =  $this->class_id;

                $data['class'] =  $this->class->title;

                
                $data['section_id'] =  $this->section_id;
                $data['section'] =  $this->section->section_name;

                $data['subject_id'] =  $this->subject_id;

                $data['subject'] = $this->subject->subject_name;

        
                $data['dairy'] =  $this->dairy;
        
                $data['remarks'] =  $this->remarks;
        
                $data['submission_date'] =  $this->submission_date;
        
                $data['document'] =  $this->document;
        
                $data['status'] =  $this->status;
                $data['file_type'] =  $this->file_type;

        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}





public function asJsonClassWiseDairy(){
    $data = [] ; 
               $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['teacher_details'] =  $this->teacherDetails->asJsonCommon();
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['class_id'] =  $this->class_id;

                $data['class'] =  $this->class->title;

                
                $data['section_id'] =  $this->section_id;
                $data['section'] =  $this->section->section_name;

                $data['subject_id'] =  $this->subject_id;

                $data['subject'] = $this->subject->subject_name;

        
                $data['dairy'] =  $this->dairy;
        
                $data['remarks'] =  $this->remarks;
        
                $data['submission_date'] =  $this->submission_date;
        
                $data['document'] =  $this->document;
        
                $data['status'] =  $this->status;
                $data['file_type'] =  $this->file_type;

        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}





public function asJsonStudentHasDairy(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['teacher_details_id'] =  $this->teacher_details_id;
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['class_id'] =  $this->class_id;
        
                $data['section_id'] =  $this->section_id;
        
                $data['subject_id'] =  $this->subject_id;
        
                $data['dairy'] =  $this->dairy;
        
                $data['remarks'] =  $this->remarks;
        
                $data['submission_date'] =  $this->submission_date;
        
                $data['document'] =  $this->document;
        
                $data['status'] =  $this->status;

                $data['file_type'] =  $this->file_type;

        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
                if(!empty($this->studentHasDairies)){
                    foreach($this->studentHasDairies as $studentHasDairiesData){
                        $data['student_has_dairies'] = $studentHasDairiesData->asJson();
                    }
                }
        
            return $data;
}


}


