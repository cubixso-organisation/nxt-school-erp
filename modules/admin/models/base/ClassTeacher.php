<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\StudentAssessment;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "class_teacher".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $teacher_details_id
 * @property integer $academic_year_id
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\AcademicYears $academicYear
 */
class ClassTeacher extends \yii\db\ActiveRecord
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
            'createUser',
            'updateUser',
            'teacherDetails',
            'section',
            'academicYear'
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
            [['class_id', 'section_id', 'teacher_details_id'], 'required'],
            [['class_id', 'section_id', 'teacher_details_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_teacher';
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
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'teacher_details_id' => Yii::t('app', 'Teacher Details'),
            'academic_year_id' => Yii::t('app', 'Academic Year'),
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
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
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
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
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

public function getAcademicId(){
    $academic_year_id = !empty($this->teacherDetails->campus->academic_year)?$this->teacherDetails->campus->academic_year:'';
    return $academic_year_id;
}

    /**
     * @inheritdoc
     * @return \app\modules\admin\models\ClassTeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\ClassTeacherQuery(get_called_class());
    }
public function asJson(){
    $data = [] ;  
            $data['id'] =  $this->id;
        
                $data['class_id'] =  $this->class_id;
                $data['class'] = $this->class->title.' - '.$this->section->section_name;
                $data['section_id'] =  $this->section_id;
                $data['section'] =  $this->section->section_name;
                $date = date('Y-m-d');
                $day_id = date('l', strtotime($date));

                $data['day_id'] =  $day_id;

                $data['teacher_details_id'] =  $this->teacher_details_id;

              $academic_year_id = $this->getAcademicId();
         
                $subject_timetable = SubjectTimetable::find()->where(['day_id'=>$day_id])->andWhere(['class_id'=>$this->class_id])
                ->andWhere(['section_id'=>$this->section_id])
                ->andWhere(['academic_year_id'=>$academic_year_id])
                ->andWhere(['teacher_details_id'=>$this->teacher_details_id])
                ->one();
                if(!empty($subject_timetable)){
                    $data['time_from'] = $subject_timetable->time_from;
                    $data['time_to'] =  $subject_timetable->time_to;
                    $data['subject_timetable_id'] = $subject_timetable->id;

                }else{
                    $data['time_from'] = "";
                    $data['time_to'] = "";
                    $data['subject_timetable_id'] ='';
                }


                
        
                $data['academic_year_id'] =  $academic_year_id;
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}
 

 

public function asJsonDairy($date){
    
                $data = [] ;  
                $data['id'] =  $this->id;
                $data['class_id'] =  $this->class_id;
                $data['class'] = $this->class->title.' - '.$this->section->section_name;
                $data['section_id'] =  $this->section_id;
                $data['section'] =  $this->section->section_name;
                $day_id = date('l', strtotime($date));
                $data['day_id'] =  $day_id;

                $data['teacher_details_id'] =  $this->teacher_details_id;
                $academic_year_id = $this->getAcademicId();



                $subject_timetable = SubjectTimetable::find()->where(['day_id'=>$day_id])->andWhere(['class_id'=>$this->class_id])
                ->andWhere(['section_id'=>$this->section_id])
                ->andWhere(['academic_year_id'=>$academic_year_id])
                ->andWhere(['teacher_details_id'=>$this->teacher_details_id])
                ->one();

                if(!empty($subject_timetable)){
                    $data['time_from'] = $subject_timetable->time_from;
                    $data['time_to'] =  $subject_timetable->time_to;
                    $data['subject_timetable_id'] = $subject_timetable->id;
                    $data['subject'] = $subject_timetable->subject->subject_name;
                    $student_dairy = StudentDairy::find()
                    ->where(['teacher_details_id'=>$this->teacher_details_id])
                    ->andWhere(['subject_timetable_id'=>$subject_timetable->id])
                    ->andWhere(['academic_year_id'=>$academic_year_id])
                    ->andWhere(['section_id'=>$this->section_id])
                    ->andWhere(['created_on'=>$date])
                    ->one();
                if(!empty($student_dairy)){
                    $data['dairy_details'] = $student_dairy->asJsonDairyList();
                        $data['edit']= true;
                        }else{
                            $data['edit']= false;
                        }


                }else{
                    $data['time_from'] = "";
                    $data['time_to'] = "";
                    $data['subject_timetable_id'] ='';
                    $data['subject'] = '';
                    $data['edit']= false;

                }
 
          

 

        
                $data['academic_year_id'] = $this->getAcademicId();
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}








public function asJsonAssignment($date){
    
    $data = [] ;  
    $data['id'] =  $this->id;
    $data['class_id'] =  $this->class_id;
    $data['class'] = $this->class->title.' - '.$this->section->section_name;
    $data['section_id'] =  $this->section_id;
    $data['section'] =  $this->section->section_name;
    $day_id = date('l', strtotime($date));
    $data['day_id'] =  $day_id;

    $data['teacher_details_id'] =  $this->teacher_details_id;

    $academic_year_id = $this->getAcademicId();

    $subject_timetable = SubjectTimetable::find()->where(['day_id'=>$day_id])->andWhere(['class_id'=>$this->class_id])
    ->andWhere(['section_id'=>$this->section_id])
    ->andWhere(['academic_year_id'=>$academic_year_id])
    ->andWhere(['teacher_details_id'=>$this->teacher_details_id])
    ->one();


    if(!empty($subject_timetable)){
        $data['time_from'] = $subject_timetable->time_from;
        $data['time_to'] =  $subject_timetable->time_to;
        $data['subject_timetable_id'] = $subject_timetable->id;
        $data['subject'] = $subject_timetable->subject->subject_name;


        $StudentAssessment = StudentAssessment::find()
        ->where(['teacher_details_id'=>$this->teacher_details_id])
        ->andWhere(['subject_timetable_id'=>$subject_timetable->id])
        ->andWhere(['academic_year_id'=>$academic_year_id])
        ->andWhere(['section_id'=>$this->section_id])
        ->andWhere(['created_on'=>$date])
        ->one();

    if(!empty($StudentAssessment)){
        $data['StudentAssessmentDetails'] = $StudentAssessment->asJson();
            $data['edit']= true;
            }else{
                $data['edit']= false;
            }


    }else{
        $data['time_from'] = "";
        $data['time_to'] = "";
        $data['subject_timetable_id'] ='';
        $data['subject'] = '';
        $data['edit']= false;

    }




    

    $data['academic_year_id'] =  $academic_year_id;

    $data['status'] =  $this->status;

    $data['create_user_id'] =  $this->create_user_id;

    $data['update_user_id'] =  $this->update_user_id;

    $data['created_on'] =  $this->created_on;

    $data['updated_on'] =  $this->updated_on;

return $data;
}






}


