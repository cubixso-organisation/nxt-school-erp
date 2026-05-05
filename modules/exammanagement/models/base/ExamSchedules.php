<?php


namespace app\modules\exammanagement\models\base;

use app\models\User;
use app\modules\admin\models\ClassSections;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use DateTimeZone;
/**
 * This is the base model class for table "exam_schedules".
 *
 * @property integer $id
 * @property integer $session_id
 * @property integer $campus_id
 * @property integer $exam_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $subject_id
 * @property double $max_marks
 * @property double $min_marks
 * @property string $exam_date
 * @property string $exam_duration
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id

 * 
 *
 * @property \app\modules\exammanagement\models\Campus $campus
 * @property \app\modules\exammanagement\models\AcademicYears $session
 * @property \app\modules\exammanagement\models\StudentClass $class
 * @property \app\modules\exammanagement\models\ClassSections $section
 * @property \app\modules\exammanagement\models\Exams $exam
 * @property \app\modules\exammanagement\models\Subjects $subject
 */
class ExamSchedules extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $marks_division = [];
    public $max_marks_devision = [];
    public $min_marks_devision = [];

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'session',
            'class',
            'section',
            'exam',
            'subject'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    const IS_CHECKED = 1;
    const IS_NOT_CHECKED = 2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'campus_id', 'exam_id', 'class_id', 'section_id', 'subject_id', 'max_marks', 'min_marks', 'exam_date','start_time','end_time', 'exam_duration', 'status',], 'required'],
            [['session_id',  'campus_id', 'exam_id', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['max_marks', 'min_marks'], 'number'],
            ['end_time', 'validateEndTime'], // Custom validation rule for end_time
            [['exam_date','start_time','end_time', 'exam_duration', 'created_on', 'updated_on', 'created_on', 'updated_on', 'create_user_id', 'update_user_id', 'room_no', 'marks_division','min_marks_devision','max_marks_devision'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_schedules';
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
            'session_id' => Yii::t('app', 'Session'),
            'campus_id' => Yii::t('app', 'Campus'),
            'exam_id' => Yii::t('app', 'Exam'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'subject_id' => Yii::t('app', 'Subject'),
            'max_marks' => Yii::t('app', 'Max Marks'),
            'min_marks' => Yii::t('app', 'Min Marks'),
            'exam_date' => Yii::t('app', 'Exam Date'),
            'exam_duration' => Yii::t('app', 'Exam Duration'),
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
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'class_id']);
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
    public function getExam()
    {
        return $this->hasOne(\app\modules\admin\models\Exams::className(), ['id' => 'exam_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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


    public function Section($type)
    {

        $dat = [];
        $subjectName = [];
        $mod = [];
        $sections = ClassSections::find()->where(['campus_id' => (new User())->getCampusId()])->andWhere(['student_class_id' => $type])->all();
        if (!empty($sections)) {


            foreach ($sections as $section) {
                $dat[] = ['id' => $section->id, 'name' => $section->section_name];
            }
        } else {
            $dat = [];
        }




        return ['output' => $dat];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Define the time zone if necessary
            $timeZone = new DateTimeZone('Asia/Kolkata'); // Replace with your desired time zone

            // Convert the start_time and end_time to DateTime objects with time zone
            $start = DateTime::createFromFormat('H:i', $this->start_time, $timeZone);
            $end = DateTime::createFromFormat('H:i', $this->end_time, $timeZone);

            // If the end time is before the start time, assume it's on the next day
            if ($end < $start) {
                $end->modify('+1 day');
            }

            // Calculate the duration in minutes
            // $duration = $end->diff($start);
            // $hours = $duration->h;
            // $minutes = $duration->i;

            // // Save the duration in "H:i" format (hours:minutes)
            // $this->exam_duration = sprintf('%02d:%02d', $hours, $minutes);

            // Save start_time and end_time in proper format
            // $this->start_time = $start->format('H:i');
            // $this->end_time = $end->format('H:i');

            return true;
        }
        return false;
    }
    public function validateEndTime($attribute, $params)
    {
        if (strtotime($this->end_time) <= strtotime($this->start_time)) {
            $this->addError($attribute, Yii::t('app', 'End time must be greater than start time.'));
        }
    }
    /**
     * @inheritdoc
     * @return \app\modules\exammanagement\models\ExamSchedulesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\ExamSchedulesQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['session']['id'] =  $this->session_id;
        $data['session']['title'] =  $this->session->title ?? "";

        $data['campus_id'] =  $this->campus_id;

        $data['exam']['id'] =  $this->exam_id;
        $data['exam']['name'] =  $this->exam->name_of_exam ?? "";

        $data['class']['id'] =  $this->class_id;
        $data['class']['name'] =  $this->class->title ?? "";

        $data['section']['id'] =  $this->section_id;
        $data['section']['name'] =  $this->section->section_name;

        $data['subject_id'] =  $this->subject_id;

        $data['max_marks'] =  $this->max_marks;

        $data['min_marks'] =  $this->min_marks;

        $data['exam_date'] =  $this->exam_date;

        $data['exam_duration'] =  $this->exam_duration;

        $data['status'] =  $this->status;



        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
