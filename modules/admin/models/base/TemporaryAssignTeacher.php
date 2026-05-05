<?php


namespace app\modules\admin\models\base;

use DateTime;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "temporary_assign_teacher".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $teacher_detail_id
 * @property integer $teacher_timetable_id
 * @property integer $date
 * @property integer $day_id
 * @property integer $period
 * @property string $time_from
 * @property string $time_to
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $subject_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\TeacherDetails $teacherDetail
 */
class TemporaryAssignTeacher extends \yii\db\ActiveRecord
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
            'teacherDetail',
            'subject',
            'subjectTimetable'

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
            [['id', 'campus_id', 'teacher_detail_id', 'teacher_timetable_id', 'date', 'day_id', 'period', 'time_from', 'time_to', 'class_id', 'section_id', 'subject_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['id', 'campus_id', 'teacher_detail_id', 'teacher_timetable_id', 'date', 'day_id', 'period', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on', 'replaced_teacher_detail_id'], 'safe'],
            [['time_from', 'time_to'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temporary_assign_teacher';
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'teacher_detail_id' => Yii::t('app', 'Teacher Name'),
            'replaced_teacher_detail_id' => Yii::t('app', 'Replaced Teacher Detail ID'),
            'teacher_timetable_id' => Yii::t('app', 'Teacher Timetable ID'),
            'date' => Yii::t('app', 'Date'),
            'day_id' => Yii::t('app', 'Day '),
            'period' => Yii::t('app', 'Period'),
            'time_from' => Yii::t('app', 'Time From'),
            'time_to' => Yii::t('app', 'Time To'),
            'class_id' => Yii::t('app', 'Class'),
            'section_id' => Yii::t('app', 'Section'),
            'subject_id' => Yii::t('app', 'Subject'),
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
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
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
    public function getTeacherDetail()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_detail_id']);
    }
    public function getSubjectTimetable()
    {
        return $this->hasOne(\app\modules\admin\models\SubjectTimetable::className(), ['id' => 'teacher_timetable_id']);
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
     * @return \app\modules\admin\models\TemporaryAssignTeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TemporaryAssignTeacherQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['teacher_detail_id'] =  $this->teacher_detail_id;

        $data['replaced_teacher_detail_id'] =  $this->replaced_teacher_detail_id;

        $data['teacher_timetable_id'] =  $this->teacher_timetable_id;

        $data['date'] =  $this->date;

        $data['day_id'] =  $this->day_id;

        $data['period'] =  $this->period;

        $data['time_from'] =  $this->time_from;

        $data['time_to'] =  $this->time_to;

        $data['class_id'] =  $this->class_id;

        $data['section_id'] =  $this->section_id;

        $data['subject_id'] =  $this->subject_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }


    public function asTempAssignJson()
    {
        $data = [];
        $data['subject_timetable_id'] =  $this->teacher_timetable_id;

        $data['campus_id'] =  $this->campus_id;

        $data['day_id'] =  $this->day_id;

        $data['class_id'] =  $this->class_id;

        $data['class'] = $this->class->asJson();

        $data['section_id'] =  $this->section_id;

        $data['section'] =  $this->section->asJson();


        $data['subject_group_subject_id'] =  0;
        $data['subject'] = $this->subject->asJson();

        $data['teacher_detail_id'] =  $this->replaced_teacher_detail_id;



        $time_start = new DateTime($this->time_from);
        $time_start_a =  $time_start->format('g:i a');

        $time_to_end = new DateTime($this->time_to);
        $time_to_end_a =  $time_to_end->format('g:i a');


        $data['time_from'] =  $time_start_a;

        $data['time_to'] =  $time_to_end_a;


        $data['start_time'] =  NULL;

        $data['end_time'] =  NULL;



        $data['room_id'] =  $this->subjectTimetable->room_id;
        $data['class_room'] = $this->subjectTimetable->room->asJson();

        $data['academic_year_id'] =  $this->subjectTimetable->academic_year_id;

        $data['status'] =  $this->subjectTimetable->status;

        $data['period'] =  $this->subjectTimetable->period;


        $attendance_settings = AttendanceSettings::find()->where(['campus_id' => $this->subjectTimetable->campus_id])->andWhere(['status' => AttendanceSettings::STATUS_ACTIVE])->one();
        if (!empty($attendance_settings)) {
            $attendance_time_tables = AttendanceTimeTables::find()->where(['subject_timetable_id' => $this->subjectTimetable->id])->andWhere(['status' => AttendanceTimeTables::STATUS_ACTIVE])->one();

            if (!empty($attendance_time_tables)) {
                $data['take_attendance'] = true;
            } else {
                $data['take_attendance'] = false;
            }
        } else {
            $data['take_attendance'] = true;
        }



        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
