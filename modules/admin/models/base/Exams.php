<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "exams".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $name_of_exam
 * @property integer $marks_type
 * @property integer $total_percentage_or_gpa
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 */
class Exams extends \yii\db\ActiveRecord
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
            'createUser',
            'updateUser'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
    const marks_type_percentage = 1;
    const marks_type_gpa = 2; 
    const exam_type_cbse = 1;
    const exam_type_general = 2; 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'name_of_exam',  'total_percentage_or_gpa'], 'required'],
            [['campus_id', 'marks_type','exam_type', 'total_percentage_or_gpa', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name_of_exam'], 'string', 'max' => 256],
            ['marks_type', 'validateMarksType'],

        ];
    }
 

    public function validateMarksType($attribute, $params, $validator)
{
    if ($this->marks_type == self::marks_type_percentage) {
        if ($this->total_percentage_or_gpa > 100) {
            $this->addError($attribute, 'Total percentage should not exceed 100.');
        }
    } elseif ($this->marks_type == self::marks_type_gpa) {
        if ($this->total_percentage_or_gpa > 10) {
            $this->addError($attribute, 'Total GPA should not exceed 10.');
        }
    }
}
public function validateExamType($attribute, $params, $validator)
{
    if ($this->exam_type == self::exam_type_cbse) {
        if ($this->total_percentage_or_gpa > 100) {
            $this->addError($attribute, 'cbse');
        }
    } elseif ($this->exam_type == self::exam_type_general) {
        if ($this->total_percentage_or_gpa > 10) {
            $this->addError($attribute, 'general');
        }
    }
}


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exams';
    }

    public function getMarksTypeOptions()
    {
        return [

            self::marks_type_percentage => 'Percentage',
            self::marks_type_gpa => 'GPA',
  

        ];
    }

    public function getExamTypeOptions()
    {
        return [

            self::exam_type_cbse => 'CBSE',
            self::exam_type_general => 'General',
  

        ];
    }
    

    public function getMarksTypeBadges()
    {

        if ($this->marks_type == self::marks_type_percentage) {
            return '<span class="badge badge-success">Percentage</span>';
        } elseif ($this->marks_type == self::marks_type_gpa) {
            return '<span class="badge badge-default">GPA</span>';
        }

    }
    public function getExamTypeBadges()
    {

        if ($this->marks_type == self::exam_type_cbse) {
            return '<span class="badge badge-success">CBSE</span>';
        } elseif ($this->marks_type == self::exam_type_general) {
            return '<span class="badge badge-default">General</span>';
        }

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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'name_of_exam' => Yii::t('app', 'Name Of Exam'),
            'marks_type' => Yii::t('app', 'Marks Type'),
            'exam_type' => Yii::t('app', 'Exam Type'),
            'total_percentage_or_gpa' => Yii::t('app', 'Total Percentage Or Gpa'),
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
     * @return \app\modules\admin\models\ExamsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\ExamsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['name_of_exam'] =  $this->name_of_exam;
        
                $data['marks_type'] =  $this->marks_type;

                $data['exam_type'] =  $this->exam_type;
        
                $data['total_percentage_or_gpa'] =  $this->total_percentage_or_gpa;
        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}


}


