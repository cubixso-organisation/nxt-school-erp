<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "tutorix_subjects".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $subject_id
 * @property string $name
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\TutorixLectures[] $tutorixLectures
 * @property \app\modules\admin\models\TutorixSections[] $tutorixSections
 * @property \app\modules\admin\models\TutorixSubjects $class
 * @property \app\modules\admin\models\TutorixSubjects[] $tutorixSubjects
 */
class TutorixSubjects extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'tutorixLectures',
            'tutorixSections',
            'class',
            'tutorixSubjects'
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
            [['class_id', 'subject_id', 'name', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['class_id', 'subject_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tutorix_subjects';
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
            'class_id' => Yii::t('app', 'Class ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'name' => Yii::t('app', 'Name'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutorixLectures()
    {
        return $this->hasMany(\app\modules\admin\models\TutorixLectures::className(), ['subject_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutorixSections()
    {
        return $this->hasMany(\app\modules\admin\models\TutorixSections::className(), ['subject_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\TutorixSubjects::className(), ['id' => 'class_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutorixSubjects()
    {
        return $this->hasMany(\app\modules\admin\models\TutorixSubjects::className(), ['class_id' => 'id']);
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
     * @return \app\modules\admin\models\TutorixSubjectsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TutorixSubjectsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['class_id'] =  $this->class_id;
        
                $data['subject_id'] =  $this->subject_id;
        
                $data['name'] =  $this->name;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


