<?php


namespace app\modules\exammanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "grade_defination".
 *
 * @property integer $id
 * @property integer $grade_id
 * @property integer $section_id
 * @property integer $campus_id
 * @property double $max_marks
 * @property double $min_marks
 * @property double $grade
 * @property double $cgpa
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\exammanagement\models\Campus $campus
 * @property \app\modules\exammanagement\models\Campus $section
 * @property \app\modules\exammanagement\models\Grade $grade0
 */
class GradeDefination extends \yii\db\ActiveRecord
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
            'section',
            'grade0'
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
            [['grade_id', 'max_marks', 'min_marks', 'grade', 'cgpa',], 'required'],
            [['grade_id', 'section_id', 'campus_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['max_marks', 'min_marks',  'cgpa'], 'number'],
            [['created_on', 'updated_on', 'create_user_id', 'update_user_id', 'section_id', 'grade', 'campus_id',], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grade_defination';
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
        } elseif ($this->status == self::STATUS_DELETE) {
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
            'grade_id' => Yii::t('app', 'Grade ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'max_marks' => Yii::t('app', 'Max Marks'),
            'min_marks' => Yii::t('app', 'Min Marks'),
            'grade' => Yii::t('app', 'Grade'),
            'cgpa' => Yii::t('app', 'Cgpa'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
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
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrade0()
    {
        return $this->hasOne(\app\modules\exammanagement\models\Grade::className(), ['id' => 'grade_id']);
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
     * @return \app\modules\exammanagement\models\GradeDefinationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\GradeDefinationQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['grade_id'] =  $this->grade_id;

        $data['section_id'] =  $this->section_id;

        $data['campus_id'] =  $this->campus_id;

        $data['max_marks'] =  $this->max_marks;

        $data['min_marks'] =  $this->min_marks;

        $data['grade'] =  $this->grade;

        $data['cgpa'] =  $this->cgpa;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
