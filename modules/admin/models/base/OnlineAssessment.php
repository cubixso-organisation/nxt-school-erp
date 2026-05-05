<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "online_assessment".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $subject_id
 * @property integer $academic_year_id
 * @property integer $section_id
 * @property string $title
 * @property integer $duration
 * @property integer $total_marks
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\Subjects $subject
 * @property \app\modules\admin\models\AcademicYears $academicYear
 */
class OnlineAssessment extends \yii\db\ActiveRecord
{


    public $academic_year_id;
    public $section_id;
    public $created_at;
    public $updated_at;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'subject',
            'academicYear'
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
            [['campus_id', 'subject_id', 'academic_year_id', 'section_id', 'title', 'duration', 'total_marks'], 'required'],
            [['id', 'campus_id', 'subject_id', 'academic_year_id', 'section_id', 'duration', 'total_marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 199]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'online_assessment';
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
            'id' => 'ID',
            'campus_id' => 'Campus ID',
            'subject_id' => 'Subject ID',
            'academic_year_id' => 'Academic Year ID',
            'section_id' => 'Section ID',
            'title' => 'Title',
            'duration' => 'Duration',
            'total_marks' => 'Total Marks',
            'status' => 'Status',
            'created_at' => 'Created On',
            'updated_at' => 'Updated On',
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
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
     * @return \app\modules\admin\models\OnlineAssessmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\OnlineAssessmentQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] = $this->id;

        $data['campus_id'] = $this->campus_id;

        $data['subject_id'] = $this->subject_id;

        $data['academic_year_id'] = $this->academic_year_id;

        $data['section_id'] = $this->section_id;

        $data['title'] = $this->title;

        $data['duration'] = $this->duration;

        $data['total_marks'] = $this->total_marks;

        $data['status'] = $this->status;

        $data['created_at'] = $this->created_at;

        $data['updated_at'] = $this->updated_at;

        $data['create_user_id'] = $this->create_user_id;

        $data['update_user_id'] = $this->update_user_id;

        return $data;
    }
}
