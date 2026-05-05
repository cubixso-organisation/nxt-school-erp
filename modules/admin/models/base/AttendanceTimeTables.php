<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "attendance_time_tables".
 *
 * @property integer $id
 * @property integer $attendance_settings_id
 * @property integer $subject_timetable_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\AttendanceSettings $attendanceSettings
 * @property \app\modules\admin\models\SubjectTimetable $subjectTimetable
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 */
class AttendanceTimeTables extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

   
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'attendanceSettings',
            'subjectTimetable',
            'createUser',
            'updateUser'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


         //Week Days
         const  Monday= 'Monday';
         const  Tuesday= 'Tuesday';
         const  Wednesday= 'Wednesday';
         const  Thursday= 'Thursday';
         const  Friday= 'Friday';
         const  Saturday= 'Saturday';
         const  Sunday= 'Sunday';
         
  
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attendance_settings_id', 'subject_timetable_id','class_id','section_id','day_id'], 'required'],
            [['attendance_settings_id', 'subject_timetable_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on','day_id'], 'safe']
        ];
    }
 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attendance_time_tables';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'In Active',
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





    public function getDaysOptions(){
        return [
    
            self::Monday => 'Monday',
            self::Tuesday => 'Tuesday',
            self::Wednesday => 'Wednesday',
            self::Thursday => 'Thursday',
            self::Friday => 'Friday',
            self::Saturday => 'Saturday',
            self::Sunday => 'Sunday',
    
    
        ];
    }





    
    
    public function getDaysOptionsBadges()
    {
    
        if ($this->day_id == self::Monday) {
            return '<span class="badge badge-primary">Monday</span>';
        } elseif ($this->day_id == self::Tuesday) {
            return '<span class="badge badge-success">Tuesday</span>';
        }elseif ($this->day_id == self::Wednesday) {
            return '<span class="badge badge-warning">Wednesday</span>';
        }
        elseif ($this->day_id == self::Thursday) {
            return '<span class="badge badge-info">Thursday</span>';
        }
        elseif ($this->day_id == self::Friday) {
            return '<span class="badge badge-secondary">Friday</span>';
        }
        elseif ($this->day_id == self::Saturday) {
            return '<span class="badge badge-success">Saturday</span>';
        }
        elseif ($this->day_id == self::Sunday) {
            return '<span class="badge badge-danger">Sunday</span>';
        }
    
    
    }






    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attendance_settings_id' => Yii::t('app', 'Attendance Settings'),
            'subject_timetable_id' => Yii::t('app', 'Subject Timetable'),
            'status' => Yii::t('app', 'Status'),
            'day_id' => Yii::t('app', 'Day'),
            'section_id' => Yii::t('app', 'Section'),
            'class_id' => Yii::t('app', 'Class'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendanceSettings()
    {
        return $this->hasOne(\app\modules\admin\models\AttendanceSettings::className(), ['id' => 'attendance_settings_id']);
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
     * @return \app\modules\admin\models\AttendanceTimeTablesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\AttendanceTimeTablesQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['attendance_settings_id'] =  $this->attendance_settings_id;
        
                $data['subject_timetable_id'] =  $this->subject_timetable_id;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


