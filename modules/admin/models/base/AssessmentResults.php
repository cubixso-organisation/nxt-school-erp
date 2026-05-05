<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "assessment_results".
 *
 * @property integer $id
 * @property integer $assessment_id
 * @property integer $student_id
 * @property double $total_marks
 * @property double $marks_scored
 * @property string $start_time
 * @property string $end_time
 * @property integer $last_attempt_question_id
 * @property integer $test_completed
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\OnlineAssessment $assessment
 * @property \app\modules\admin\models\StudentDetails $student
 */
class AssessmentResults extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'assessment',
            'student'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    const TEST_COMPLETED = 1;
    const TEST_NOT_COMPLETED = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assessment_id', 'student_id', 'total_marks', 'marks_scored', 'start_time', 'end_time', 'last_attempt_question_id', 'test_completed', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['assessment_id', 'student_id', 'last_attempt_question_id', 'test_completed', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_marks', 'marks_scored'], 'number'],
            [['start_time', 'end_time', 'created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assessment_results';
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
            'assessment_id' => Yii::t('app', 'Assessment ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'total_marks' => Yii::t('app', 'Total Marks'),
            'marks_scored' => Yii::t('app', 'Marks Scored'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'last_attempt_question_id' => Yii::t('app', 'Last Attempt Question ID'),
            'test_completed' => Yii::t('app', 'Test Completed'),
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
    public function getAssessment()
    {
        return $this->hasOne(\app\modules\admin\models\OnlineAssessment::className(), ['id' => 'assessment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
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
     * @return \app\modules\admin\models\AssessmentResultsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\AssessmentResultsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['assessment_id'] =  $this->assessment_id;

        $data['student_id'] =  $this->student_id;

        $data['total_marks'] =  $this->total_marks;

        $data['marks_scored'] =  $this->marks_scored;

        $data['start_time'] =  $this->start_time;

        $data['end_time'] =  $this->end_time;

        $data['last_attempt_question_id'] =  $this->last_attempt_question_id;

        $data['test_completed'] =  $this->test_completed;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
