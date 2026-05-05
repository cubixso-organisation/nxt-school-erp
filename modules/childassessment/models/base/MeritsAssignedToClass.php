<?php


namespace app\modules\childassessment\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "merits_assigned_to_class".
 *
 * @property integer $id
 * @property integer $academic_year_id
 * @property integer $campus_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $merit_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\childassessment\models\Campus $campus
 * @property \app\modules\childassessment\models\StudentClass $class
 * @property \app\modules\childassessment\models\ClassSections $section
 * @property \app\modules\childassessment\models\ChildMerit $merit
 * @property \app\modules\childassessment\models\AcademicYears $academicYear
 */
class MeritsAssignedToClass extends \yii\db\ActiveRecord
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
            'class',
            'section',
            'merit',
            'academicYear',
            'exam'
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
            [['academic_year_id', 'campus_id', 'class_id', 'section_id', 'merit_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['academic_year_id', 'campus_id', 'class_id', 'section_id', 'merit_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'merits_assigned_to_class';
    }

    public static function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public  function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="btn btn-inverse-success btn-sm btn-rounded btn-icon">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="btn btn-inverse-warning btn-sm btn-rounded btn-icon">In Active</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="btn btn-inverse-danger btn-sm btn-rounded btn-icon">Deleted</span>';
        }
    }

    public static function getFeatureOptions()
    {
        return [

            self::IS_FEATURED => 'Is Featured',
            self::IS_NOT_FEATURED => 'Not Featured',

        ];
    }

    public function getFeatureOptionsBadges()
    {
        if ($this->is_featured == self::IS_FEATURED) {
            return '<span class="btn btn-inverse-primary btn-rounded btn-icon">Featured</span>';
        } elseif ($this->is_featured == self::IS_NOT_FEATURED) {
            return '<span class="btn btn-inverse-danger btn-rounded btn-icon">Not Featured</span>';
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academic_year_id' => 'Academic Year',
            'campus_id' => 'Campus',
            'class_id' => 'Class',
            'section_id' => 'Section',
            'merit_id' => 'Merit',
            'status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
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
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
    }
    public function getExam()
    {
        return $this->hasOne(\app\modules\admin\models\Exams::className(), ['id' => 'exan_id']);
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
    public function getMerit()
    {
        return $this->hasOne(\app\modules\childassessment\models\ChildMerit::className(), ['id' => 'merit_id']);
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
     * @return \app\models\MeritsAssignedToClassQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\MeritsAssignedToClassQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['class_id'] =  $this->class_id;
        $data['class_name'] =  $this->class->title;


        $data['section_id'] =  $this->section_id;

        $data['section_name'] =  $this->section->section_name;

        $data['merit_details'][] =  $this->merit->asjson();

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
    public function asJsonForChildMerits()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['class_id'] =  $this->class_id;
        $data['class_name'] =  $this->class->title;


        $data['section_id'] =  $this->section_id;

        $data['section_name'] =  $this->section->section_name;
        $data['exam_id'] =  $this->exam_id;



        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;
        $merit_Details = MeritsAssignedToClass::find()->where(['campus_id' => $this->campus_id])->andwhere(['class_id' => $this->class_id])->andwhere(['section_id' => $this->section_id])->andWhere(['exam_id' => $this->exam_id])->groupBy('id')->all();

        $data['merits'] = [];

        if (!empty($merit_Details)) {
            foreach ($merit_Details as $record) {
                $merit_data = [
                    'id' => $record->merit->id,
                    'campus_id' => $record->merit->campus_id,
                    'name' => $record->merit->name,
                    'exam_id' => $record->exam_id,
                    'description' => $record->merit->description,
                    'max_marks' => $record->merit->max_marks, // Assuming 'id' is the primary key of StudentMeritMarks
                ];
                $data['merit_details'][] = $merit_data;
            }
        }

        return $data;
    }
}
