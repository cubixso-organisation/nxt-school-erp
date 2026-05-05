<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "question".
 *
 * @property integer $id
 * @property integer $assessment_id
 * @property integer $campus_id
 * @property string $question_text
 * @property integer $type
 * @property integer $marks
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class Question extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            ''
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;



    const TYPE_MCQ = 1;
    const TYPE_TRUE_FALSE = 2;
    const TYPE_TEXT = 3;
    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'assessment_id', 'campus_id', 'question_text', 'marks', 'status'], 'required'],
            [['id', 'assessment_id', 'campus_id', 'type', 'marks', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['question_text'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
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
            'assessment_id' => 'Assessment ID',
            'campus_id' => 'Campus ID',
            'question_text' => 'Question Text',
            'type' => 'Type',
            'marks' => 'Marks',
            'status' => 'Status',
            'created_at' => 'Created On',
            'updated_at' => 'Updated On',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
        ];
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
     * @return \app\modules\admin\models\QuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\QuestionQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['assessment_id'] =  $this->assessment_id;

        $data['campus_id'] =  $this->campus_id;

        $data['question_text'] =  $this->question_text;

        $data['type'] =  $this->type;

        $data['marks'] =  $this->marks;

        $data['status'] =  $this->status;

        $data['created_at'] =  $this->created_at;

        $data['updated_at'] =  $this->updated_at;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
