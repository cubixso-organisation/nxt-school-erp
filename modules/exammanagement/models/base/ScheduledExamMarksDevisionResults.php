<?php


namespace app\modules\exammanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "scheduled_exam_marks_devision_results".
 *
 * @property integer $id
 * @property integer $exam_result_id
 * @property integer $student_id
 * @property integer $marks_devision_id
 * @property integer $exam_schedule_id
 * @property integer $scheduled_exam_devision_id
 * @property double $marks_scored
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\exammanagement\models\Exams $examResult
 * @property \app\modules\exammanagement\models\ScheduledExamMarksDevision $scheduledExamDevision
 * @property \app\modules\exammanagement\models\ExamSchedules $examSchedule
 * @property \app\modules\exammanagement\models\MarksDivition $marksDevision
 * @property \app\modules\exammanagement\models\StudentDetails $student
 */
class ScheduledExamMarksDevisionResults extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'examResult',
            'scheduledExamDevision',
            'examSchedule',
            'marksDevision',
            'student'
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
            [['exam_result_id', 'student_id', 'marks_devision_id', 'exam_schedule_id', 'scheduled_exam_devision_id', 'marks_scored', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['exam_result_id', 'student_id', 'marks_devision_id', 'exam_schedule_id', 'scheduled_exam_devision_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['marks_scored'], 'number'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduled_exam_marks_devision_results';
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
            'exam_result_id' => Yii::t('app', 'Exam Result ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'marks_devision_id' => Yii::t('app', 'Marks Devision ID'),
            'exam_schedule_id' => Yii::t('app', 'Exam Schedule ID'),
            'scheduled_exam_devision_id' => Yii::t('app', 'Scheduled Exam Devision ID'),
            'marks_scored' => Yii::t('app', 'Marks Scored'),
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
    public function getExamResult()
    {
        return $this->hasOne(\app\modules\admin\models\Exams::className(), ['id' => 'exam_result_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheduledExamDevision()
    {
        return $this->hasOne(\app\modules\exammanagement\models\ScheduledExamMarksDevision::className(), ['id' => 'scheduled_exam_devision_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamSchedule()
    {
        return $this->hasOne(\app\modules\exammanagement\models\ExamSchedules::className(), ['id' => 'exam_schedule_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarksDevision()
    {
        return $this->hasOne(\app\modules\exammanagement\models\MarksDivition::className(), ['id' => 'marks_devision_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
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
     * @return \app\modules\exammanagement\models\ScheduledExamMarksDevisionResultsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\ScheduledExamMarksDevisionResultsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['exam_result_id'] =  $this->exam_result_id;
        
                $data['student_id'] =  $this->student_id;
        
                $data['marks_devision_id'] =  $this->marks_devision_id;
        
                $data['exam_schedule_id'] =  $this->exam_schedule_id;
        
                $data['scheduled_exam_devision_id'] =  $this->scheduled_exam_devision_id;
        
                $data['marks_scored'] =  $this->marks_scored;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


