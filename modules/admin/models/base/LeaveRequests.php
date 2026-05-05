<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "leave_requests".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $leave_type_id
 * @property string $from_date
 * @property string $to_date
 * @property string $leave_reason
 * @property integer $class_teacher_id
 * @property string $document
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\TeacherDetails $classTeacher
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\LeaveTypes $leaveType
 */
class LeaveRequests extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'classTeacher',
            'createUser',
            'updateUser',
            'student',
            'leaveType'
        ];
    }

    const STATUS_ACCEPTED = 1;
    const STATUS_REJECT = 2;
    const STATUS_PENDING = 3;

 
    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'leave_type_id', 'from_date', 'to_date', 'leave_reason', 'class_teacher_id'], 'required'],
            [['student_id', 'leave_type_id', 'class_teacher_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['from_date', 'to_date', 'created_on', 'updated_on'], 'safe'],
            [['leave_reason'], 'string'],
            [['document'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave_requests';
    }

    public function getStateOptions()
    {
        return [

         

            self::STATUS_ACCEPTED => 'Accept',
            self::STATUS_REJECT => 'Reject',

            self::STATUS_PENDING => 'Pending',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACCEPTED) {
            return '<span class="badge badge-success">Accept</span>';
        } elseif ($this->status == self::STATUS_REJECT) {
            return '<span class="badge badge-default">Reject</span>';
        }elseif ($this->status == self::STATUS_PENDING) {
            return '<span class="badge badge-default">Pending</span>';
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
            'leave_type_id' => Yii::t('app', 'Leave Type ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'leave_reason' => Yii::t('app', 'Leave Reason'),
            'class_teacher_id' => Yii::t('app', 'Class Teacher ID'),
            'document' => Yii::t('app', 'Document'),
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
    public function getClassTeacher()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'class_teacher_id']);
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(\app\modules\admin\models\LeaveTypes::className(), ['id' => 'leave_type_id']);
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
     * @return \app\modules\admin\models\LeaveRequestsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\LeaveRequestsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['leave_requests_id'] =  $this->id;
        
                $data['student_id'] =  $this->student_id; 

                $data['student_name'] =  $this->student->student_name; 
                $data['student_profile_photo'] =  $this->student->profile_photo; 
                $data['role_number'] =  $this->student->rool_number; 

                $data['student_class'] =  $this->student->studentClass->title; 
                $data['student_section'] =  $this->student->section->section_name; 



        
                $data['leave_type_id'] =  $this->leave_type_id;
                $data['leaveType'] = $this->leaveType->asJson();
        
                $data['from_date'] =  $this->from_date;
        
                $data['to_date'] =  $this->to_date;
        
                $data['leave_reason'] =  $this->leave_reason;
        
                $data['class_teacher_id'] =  $this->class_teacher_id;
        
                $data['document'] =  $this->document;

                $data['rejection_reason'] =  $this->rejection_reason;

        
                $data['leave_requests_status'] =  $this->status;
                
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


