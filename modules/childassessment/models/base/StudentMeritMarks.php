<?php


namespace app\modules\childassessment\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_merit_marks".
 *
 * @property integer $id
 * @property integer $child_merit_id
 * @property integer $student_details_id
 * @property integer $teacher_details_id
 * @property integer $marks_scored
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\childassessment\models\ChildMerit $childMerit
 * @property \app\modules\childassessment\models\StudentDetails $studentDetails
 * @property \app\modules\childassessment\models\User $createdUser
 * @property \app\modules\childassessment\models\User $updatedUser
 * @property \app\modules\childassessment\models\TeacherDetails $teacherDetails
 */
class StudentMeritMarks extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'childMerit',
            'studentDetails',
            'createdUser',
            'updatedUser',
            'teacherDetails'
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
            [['campus_id', 'child_merit_id', 'student_details_id', 'teacher_details_id', 'marks_scored', 'max_marks', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_merit_marks';
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
            'child_merit_id' => Yii::t('app', 'Child Merit type'),
            'student_details_id' => Yii::t('app', 'Name of Student'),
            'teacher_details_id' => Yii::t('app', 'Updated by(Teacher)'),
            'marks_scored' => Yii::t('app', 'Marks Scored'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildMerit()
    {
        return $this->hasOne(\app\modules\childassessment\models\ChildMerit::className(), ['id' => 'child_merit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetails()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_details_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'created_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'updated_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
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
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\childassessment\models\StudentMeritMarksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\childassessment\models\StudentMeritMarksQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['child_merit_id'] =  $this->child_merit_id;

        $data['student_details_id'] =  $this->student_details_id;

        $data['teacher_details_id'] =  $this->teacher_details_id;

        $data['marks_scored'] =  $this->marks_scored;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
    public function asJsonForStudentMerit()
    {
        $data = [];
        $data['student_details'] = $this->studentDetails->asJsonForStudentMerit();
        $stu_avg = StudentMeritMarks::find()->where(['student_details_id' => $this->student_details_id])->all();

        if (!empty($stu_avg)) {
            foreach ($stu_avg as $record) {
                $merit_data = [
                    'merit_id' => $record->id,
                    'marks_scored' => $record->marks_scored,
                    'remarks' => $record->remarks,
                ];

                $child_merit = ChildMerit::find()->where(['id' => $record->child_merit_id])->one();
                if ($child_merit !== null) {
                    $merit_data['name'] = $child_merit->name;
                } else {
                    $merit_data['name'] = null;
                }
                $merit_data['max_marks'] = $child_merit->max_marks;
                $data['merits'][]    = $merit_data;
            }
        }

        return $data;
    }
}
