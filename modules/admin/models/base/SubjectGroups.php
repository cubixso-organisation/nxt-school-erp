<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\SubjectGroupSubjects;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "subject_groups".
 *
 * @property integer $id 
 * @property integer $campus_id
 * @property string $subject_group_name
 * @property string $description
 * @property integer $academic_year_id
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\SubjectGroupSubjects[] $subjectGroupSubjects
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\SubjectGroupsClassSections[] $subjectGroupsClassSections
 */
class SubjectGroups extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
public $student_class;
public $class_sections_id;
public $subject_id; 
public $class_sections_val;
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'subjectGroupSubjects',
            'campus',
            'createUser', 
            'updateUser', 
            'academicYear',
            'subjectGroupsClassSections'
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
            [['campus_id', 'subject_group_name', 'academic_year_id'], 'required'],
            [['campus_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_on', 'updated_on','class_sections_id','subject_id'], 'safe'],
            [['subject_group_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject_groups';
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'subject_group_name' => Yii::t('app', 'Subject Group Name'),
            'class_sections_id' => Yii::t('app', 'Class Sections'),
            'description' => Yii::t('app', 'Description'),
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
    public function getSubjectGroupSubjects()
    {

        return SubjectGroupSubjects::find()->where(['subject_group_id'=>$this->id])->andWhere(['status'=>SubjectGroupSubjects::STATUS_ACTIVE])->all();
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
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectGroupsClassSections()
    {
        return $this->hasMany(\app\modules\admin\models\SubjectGroupsClassSections::className(), ['subject_group_id' => 'id']);
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
     * @return \app\modules\admin\models\SubjectGroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\SubjectGroupsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['subject_group_name'] =  $this->subject_group_name;
        
                $data['description'] =  $this->description;
        
                $data['academic_year_id'] =  $this->academic_year_id;
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}

 
}


