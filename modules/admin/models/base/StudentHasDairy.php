<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "student_has_dairy".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $student_dairy_id
 * @property integer $is_read
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\StudentDairy $studentDairy
 */
class StudentHasDairy extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /** 
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'student',
            'createUser',
            'updateUser',
            'studentDairy'
        ];
    }

    const STATUS_COMPLETED = 1;
    const STATUS_PENDING = 2;

    const is_read_yes = 1;
    const is_read_no = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'student_dairy_id'], 'required'],
            [['student_id', 'student_dairy_id', 'is_read', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_has_dairy';
    }

    public function getStateOptions()
    {

    
        return [

            self::STATUS_COMPLETED => 'Inactive',
            self::STATUS_PENDING => 'Active',
         

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_COMPLETED) {
            return '<span class="badge badge-success">Completed</span>';
        } elseif ($this->status == self::STATUS_PENDING) {
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
            'student_dairy_id' => Yii::t('app', 'Student Dairy ID'),
            'is_read' => Yii::t('app', 'Is Read'),
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
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
    public function getStudentDairy()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDairy::className(), ['id' => 'student_dairy_id']);
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
     * @return \app\modules\admin\models\StudentHasDairyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentHasDairyQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
                $data['student_has_dairy_id'] =  $this->id;
                $data['student_details'] =  $this->student->asJsonStudentDetails();
        
                $data['student_dairy_id'] =  $this->student_dairy_id;
        
                $data['is_read'] =  $this->is_read;
        
                $data['date'] =  $this->date;

                $data['student_dairy_status'] =  $this->status;

                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


public function asJsonDairyList(){
    $data = [] ; 
                $data['student_has_dairy_id'] =  $this->id;
        
                $data['student_dairy_id'] =  $this->student_dairy_id;
        
                $data['is_read'] =  $this->is_read;
        
                $data['date'] =  $this->date;

                $data['student_dairy_status'] =  $this->status;

                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}




  

public function asJsonInParent(){
    $data = [] ; 
                $data['student_has_dairy_id'] =  $this->id;
                $data['student_details'] =  $this->student->asJsonStudentDetails();
                $data['student_dairy_details'] = $this->studentDairy->asJsonParent();
                $data['student_dairy_id'] =  $this->student_dairy_id;
        
                $data['is_read'] =  $this->is_read;
        
                $data['date'] =  $this->date;
                
                $data['student_dairy_status'] =  $this->status;

                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}
 


}


