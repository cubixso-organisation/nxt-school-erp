<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_answers".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $selected_option_id
 * @property string $answer_text
 * @property integer $marks_awarded
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class StudentAnswers extends \yii\db\ActiveRecord
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

    const STATUS_TEST_PENDING = 0;
    const STATUS_TEST_ATTEMPTED = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'question_id', 'status'], 'required'],
            [['id', 'question_id', 'selected_option_id', 'marks_awarded', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['answer_text'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_answers';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_TEST_PENDING => 'Not Attempted',
            self::STATUS_TEST_ATTEMPTED => 'Attempted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_TEST_PENDING) {
            return '<span class="badge badge-info">Not Attempted</span>';
        } elseif ($this->status == self::STATUS_TEST_ATTEMPTED) {
            return '<span class="badge badge-success">Attempted</span>';
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
            'question_id' => 'Question ID',
            'selected_option_id' => 'Selected Option ID',
            'answer_text' => 'Answer Text',
            'marks_awarded' => 'Marks Awarded',
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
     * @return \app\modules\admin\models\StudentAnswersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentAnswersQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['question_id'] =  $this->question_id;

        $data['selected_option_id'] =  $this->selected_option_id;

        $data['answer_text'] =  $this->answer_text;

        $data['marks_awarded'] =  $this->marks_awarded;

        $data['status'] =  $this->status;

        $data['created_at'] =  $this->created_at;

        $data['updated_at'] =  $this->updated_at;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
