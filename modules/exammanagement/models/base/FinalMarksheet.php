<?php


namespace app\modules\exammanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "final_marksheet".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $student_user_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $campus_id
 * @property integer $session_id
 * @property string $marksheet_url
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\exammanagement\models\StudentDetails $student
 * @property \app\modules\exammanagement\models\Campus $campus
 * @property \app\modules\exammanagement\models\User $studentUser
 * @property \app\modules\exammanagement\models\AcademicYears $session
 * @property \app\modules\exammanagement\models\StudentClass $class
 * @property \app\modules\exammanagement\models\ClassSections $section
 */
class FinalMarksheet extends \yii\db\ActiveRecord
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
            'campus',
            'studentUser',
            'session',
            'class',
            'section'
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
            [['student_id', 'student_user_id', 'class_id', 'section_id', 'campus_id', 'session_id', 'marksheet_url', 'status', 'create_user_id', 'update_user_id', 'created_on', 'updated_on'], 'required'],
            [['student_id', 'student_user_id', 'class_id', 'section_id', 'campus_id', 'session_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['marksheet_url'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_marksheet';
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
            'student_id' => Yii::t('app', 'Student'),
            'student_user_id' => Yii::t('app', 'Student User'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'campus_id' => Yii::t('app', 'Campus'),
            'session_id' => Yii::t('app', 'Session'),
            'marksheet_url' => Yii::t('app', 'Marksheet Url'),
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
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
    public function getStudentUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'student_user_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'session_id']);
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
     * @return \app\modules\exammanagement\models\FinalMarksheetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\FinalMarksheetQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['student_id'] =  $this->student_id;
                $data['student_name'] =  $this->student->student_name??"";
        
                $data['student_user_id'] =  $this->student_user_id;
        
                $data['class_id'] =  $this->class->title;
        
                $data['section_id'] =  $this->section->section_name;
        
        
                $data['session_id'] =  $this->session->title;
        
                $data['marksheet_url'] =  $this->marksheet_url;
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}


}


