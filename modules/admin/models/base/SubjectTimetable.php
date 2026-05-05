<?php


namespace app\modules\admin\models\base;

use app\modules\admin\models\AttendanceTimeTables;
use app\modules\admin\models\ClassRooms;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\TimetableErrorReports;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\base\Model;

/**
 * This is the base model class for table "subject_timetable".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $day_id
 * @property integer $class_id
 * @property integer $section_id
 * @property integer $subject_group_subject_id 
 * @property integer $teacher_details_id
 * @property string $time_from
 * @property string $time_to
 * @property string $start_time
 * @property string $end_time
 * @property integer $room_id
 * @property integer $academic_year_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentClassAttendance[] $studentClassAttendances
 * @property \app\modules\admin\models\StudentClass $class
 * @property \app\modules\admin\models\SubjectGroupSubjects $subjectGroupSubject
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\AcademicYears $academicYear
 * @property \app\modules\admin\models\ClassRooms $room
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\Campus $campus
 */
class SubjectTimetable extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;



    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'studentClassAttendances',
            'class',
            'subjectGroupSubject',
            'section',
            'academicYear',
            'room',
            'teacherDetails',
            'updateUser',
            'createUser',
            'campus',
            'subject',
            'attendanceTimeTables'
        ];
    }



    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;




    //Week Days
    const  Monday = 1;
    const  Tuesday = 2;
    const  Wednesday = 3;
    const  Thursday = 4;
    const  Friday = 5;
    const  Saturday = 6;
    const  Sunday = 7;

    const  DAY_Monday = 'Monday';
    const  DAY_Tuesday = 'Tuesday';
    const  DAY_Wednesday = 'Wednesday';
    const  DAY_Thursday = 'Thursday';
    const  DAY_Friday = 'Friday';
    const  DAY_Saturday = 'Saturday';
    const  DAY_Sunday = 'Sunday';



    public function getDaysOptions()
    {
        return [
            self::Monday => 'Monday',
            self::Tuesday => 'Tuesday',
            self::Wednesday => 'Wednesday',
            self::Thursday => 'Thursday',
            self::Friday => 'Friday',
            self::Saturday => 'Saturday',
            self::Sunday => 'Sunday',
        ];
    }
    public function getDaysWiseOptions()
    {
        return [
            self::DAY_Monday => 'Monday',
            self::DAY_Tuesday => 'Tuesday',
            self::DAY_Wednesday => 'Wednesday',
            self::DAY_Thursday => 'Thursday',
            self::DAY_Friday => 'Friday',
            self::DAY_Saturday => 'Saturday',
            self::DAY_Sunday => 'Sunday',
        ];
    }


    public function getDaysOptionsId()
    {
        return [

            self::Monday => 1,
            self::Tuesday => 2,
            self::Wednesday => 3,
            self::Thursday => 4,
            self::Friday => 5,
            self::Saturday => 6,
            self::Sunday => 7,


        ];
    }



    public function getDaysOptionsWithOutBadges()
    {

        if ($this->day_id == self::Monday) {
            return 'Monday';
        } elseif ($this->day_id == self::Tuesday) {
            return 'Tuesday';
        } elseif ($this->day_id == self::Wednesday) {
            return 'Wednesday';
        } elseif ($this->day_id == self::Thursday) {
            return 'Thursday';
        } elseif ($this->day_id == self::Friday) {
            return 'Friday';
        } elseif ($this->day_id == self::Saturday) {
            return 'Saturday';
        } elseif ($this->day_id == self::Sunday) {
            return 'Sunday';
        }
    }




    public function getDaysOptionsBadges()
    {

        if ($this->day_id == self::Monday) {
            return '<span class="badge badge-primary">Monday</span>';
        } elseif ($this->day_id == self::Tuesday) {
            return '<span class="badge badge-success">Tuesday</span>';
        } elseif ($this->day_id == self::Wednesday) {
            return '<span class="badge badge-warning">Wednesday</span>';
        } elseif ($this->day_id == self::Thursday) {
            return '<span class="badge badge-info">Thursday</span>';
        } elseif ($this->day_id == self::Friday) {
            return '<span class="badge badge-secondary">Friday</span>';
        } elseif ($this->day_id == self::Saturday) {
            return '<span class="badge badge-success">Saturday</span>';
        } elseif ($this->day_id == self::Sunday) {
            return '<span class="badge badge-danger">Sunday</span>';
        }
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendanceTimeTables()
    {
        return $this->hasMany(\app\modules\admin\models\AttendanceTimeTables::className(), ['subject_timetable_id' => 'id']);
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'day_id', 'class_id', 'section_id', 'subject_group_subject_id', 'teacher_details_id', 'time_from', 'time_to', 'start_time', 'room_id'], 'required'],
            [['campus_id', 'class_id', 'section_id', 'subject_group_subject_id', 'teacher_details_id', 'room_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['time_from', 'time_to', 'start_time', 'end_time', 'created_on', 'updated_on'], 'safe'],
            [['day_id'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject_timetable';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Inactive</span>';
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
            'campus_id' => Yii::t('app', 'Campus '),
            'day_id' => Yii::t('app', 'Day '),
            'class_id' => Yii::t('app', 'Class '),
            'section_id' => Yii::t('app', 'Section '),
            'subject_group_subject_id' => Yii::t('app', 'Subject Group Subject '),
            'teacher_details_id' => Yii::t('app', 'Teacher Details '),
            'time_from' => Yii::t('app', 'Time From'),
            'time_to' => Yii::t('app', 'Time To'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'room_id' => Yii::t('app', 'Room '),
            'academic_year_id' => Yii::t('app', 'Academic Year '),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User '),
            'update_user_id' => Yii::t('app', 'Update User '),
        ];
    }


    public static function getClassDetailsWithId($id)
    {
        $student_class = StudentClass::find()->where(['id' => $id])->one();
        return $student_class->title;
    }


    public static function getSectionDetailsWithId($id)
    {
        $ClassSections = ClassSections::find()->where(['id' => $id])->one();
        return $ClassSections->section_name;
    }

    public static function getSubjectDetailsWithId($id)
    {
        $subjects = Subjects::find()->where(['id' => $id])->one();
        return $subjects->subject_name;
    }



    public static function getTeacherDetailsWithId($id)
    {
        $teacher_details = TeacherDetails::find()->where(['id' => $id])->one();
        return $teacher_details->name;
    }


    public static function getRoomDetailsWithId($id)
    {
        $class_rooms = ClassRooms::find()->where(['id' => $id])->one();
        return $class_rooms->class_room_title;
    }


    public static function getErrorTimeTableValues($str, $subject_timetable_with_time_from_update_data)
    {
        $error[$str]['teacher'] = SubjectTimetable::getTeacherDetailsWithId($subject_timetable_with_time_from_update_data->teacher_details_id);
        $error[$str]['class'] = SubjectTimetable::getClassDetailsWithId($subject_timetable_with_time_from_update_data->class_id);
        $error[$str]['section'] = SubjectTimetable::getSectionDetailsWithId($subject_timetable_with_time_from_update_data->section_id);
        $error[$str]['subject'] = SubjectTimetable::getSubjectDetailsWithId($subject_timetable_with_time_from_update_data->subject_id);
        $error[$str]['room'] = SubjectTimetable::getRoomDetailsWithId($subject_timetable_with_time_from_update_data->room_id);
        $error[$str]['time_from'] = $subject_timetable_with_time_from_update_data->time_from;
        $error[$str]['time_to'] = $subject_timetable_with_time_from_update_data->time_to;
        return $error;
    }





    public static function updateSubjectTimeTable($campus_id, $id, $day_id, $class_id, $section_id, $academic_year_id, $teacher_details_id)
    {
        $update_subject_time_table_data = SubjectTimetable::find()->where(['id' => $id])->one();
        if (!empty($update_subject_time_table_data)) {
            if ($update_subject_time_table_data->campus_id == $campus_id) {
                if ($update_subject_time_table_data->day_id == $day_id) {
                    if ($update_subject_time_table_data->class_id == $class_id) {
                        if ($update_subject_time_table_data->section_id == $section_id) {
                            if ($update_subject_time_table_data->academic_year_id == $academic_year_id) {
                                if ($update_subject_time_table_data->teacher_details_id == $teacher_details_id) {
                                    if ($update_subject_time_table_data->status == SubjectTimetable::STATUS_DELETE) {
                                        $update_subject_time_table_data->status = SubjectTimetable::STATUS_ACTIVE;
                                        $update_subject_time_table_data->save(false);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public static function subjectTimeTableErrorReport($insert_error, $update_error)
    {
        $insert_error = array_filter($insert_error);
        $update_error = array_filter($update_error);

        foreach ($insert_error as $subject_time_table_id => $insert_error_data) {
            foreach ($insert_error_data as $error_type => $insert_error_data_insert) {
                $timetable_error_reports = new TimetableErrorReports();
                $timetable_error_reports->subject_timetable_id  = $subject_time_table_id;

                $timetable_error_reports->class  = $insert_error_data_insert['class'];
                $timetable_error_reports->room  = $insert_error_data_insert['room'];
                $timetable_error_reports->section  = $insert_error_data_insert['section'];
                $timetable_error_reports->subject  = $insert_error_data_insert['subject'];
                $timetable_error_reports->teacher  = $insert_error_data_insert['teacher'];
                $timetable_error_reports->time_from  = $insert_error_data_insert['time_from'];
                $timetable_error_reports->time_to  = $insert_error_data_insert['time_to'];
                $timetable_error_reports->error_type  = $error_type;
                $timetable_error_reports->status  = TimetableErrorReports::STATUS_ACTIVE;
                $timetable_error_reports->save(false);
            }
        }

        foreach ($update_error as $subject_time_table_id_update => $update_error_data) {
            foreach ($update_error_data as $error_type_update => $update_error_update) {
                $timetable_error_reports = new TimetableErrorReports();
                $timetable_error_reports->subject_timetable_id  = $subject_time_table_id_update;
                $timetable_error_reports->class  = $update_error_update['class'];
                $timetable_error_reports->room  = $update_error_update['room'];
                $timetable_error_reports->section  = $update_error_update['section'];
                $timetable_error_reports->subject  = $update_error_update['subject'];
                $timetable_error_reports->teacher  = $update_error_update['teacher'];
                $timetable_error_reports->time_from  = $update_error_update['time_from'];
                $timetable_error_reports->time_to  = $update_error_update['time_to'];
                $timetable_error_reports->error_type  = $error_type_update;
                $timetable_error_reports->status  = TimetableErrorReports::STATUS_ACTIVE;
                $timetable_error_reports->save(false);
            }
        }
    }

    public static function getTimeOfDay($time)
    {

        $currentTime = date($time); // Get the current time in the format '00:44:00'
        list($hour, $minute, $second) = explode(':', $currentTime);
        if ($hour >= 5 && $hour < 12) {
            $greeting = "<b style='color:green'> Morning</b>";
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = "<b style='color:red'> Afternoon</b>";
        } else {
            $greeting = "<b style='color:orange'> Evening</b>";
        }

        return $greeting;
    }




    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClassAttendances()
    {
        return $this->hasMany(\app\modules\admin\models\StudentClassAttendance::className(), ['subject_timetable_id' => 'id']);
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
    public function getSubjectGroupSubject()
    {
        return $this->hasOne(\app\modules\admin\models\SubjectGroupSubjects::className(), ['id' => 'subject_group_subject_id']);
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
    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(\app\modules\admin\models\ClassRooms::className(), ['id' => 'room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
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
                'value' => date('Y-m-d H:i:s'),
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
     * @return \app\modules\admin\models\SubjectTimetableQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\SubjectTimetableQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['subject_timetable_id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['day_id'] =  $this->day_id;

        $data['class_id'] =  $this->class_id;

        $data['class'] = $this->class->asJson();

        $data['section_id'] =  $this->section_id;

        $data['section'] =  $this->section->asJson();


        $data['subject_group_subject_id'] =  $this->subject_group_subject_id;
        $data['subject'] = $this->subjectGroupSubject->asJson();

        $data['teacher_details_id'] =  $this->teacher_details_id;



        $time_start = new DateTime($this->time_from);
        $time_start_a =  $time_start->format('g:i a');

        $time_to_end = new DateTime($this->time_to);
        $time_to_end_a =  $time_to_end->format('g:i a');


        $data['time_from'] =  $time_start_a;

        $data['time_to'] =  $time_to_end_a;







        $data['start_time'] =  $this->start_time;

        $data['end_time'] =  $this->end_time;



        $data['room_id'] =  $this->room_id;
        $data['class_room'] = $this->room->asJson();

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['status'] =  $this->status;

        $data['period'] =  $this->period;


        $attendance_settings = AttendanceSettings::find()->where(['campus_id' => $this->campus_id])->andWhere(['status' => AttendanceSettings::STATUS_ACTIVE])->one();
        if (!empty($attendance_settings)) {
            $attendance_time_tables = AttendanceTimeTables::find()->where(['subject_timetable_id' => $this->id])->andWhere(['status' => AttendanceTimeTables::STATUS_ACTIVE])->one();

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




    public function asJsonDairy($date)
    {
        $data = [];
        $data['subject_timetable_id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['day_id'] =  $this->day_id;

        $data['class_id'] =  $this->class_id;

        $data['class'] = $this->class->title . ' ' . $this->section->section_name;

        $data['section_id'] =  $this->section_id;

        $data['section'] = $this->section->section_name;

        $data['subject_group_subject_id'] =  $this->subject_group_subject_id;
        $data['subject'] = $this->subjectGroupSubject->subject->subject_name;

        $data['teacher_details_id'] =  $this->teacher_details_id;




        $time_start = new DateTime($this->time_from);
        $time_start_a =  $time_start->format('g:i a');

        $time_to_end = new DateTime($this->time_to);
        $time_to_end_a =  $time_to_end->format('g:i a');


        $data['time_from'] =  $time_start_a;

        $data['time_to'] =  $time_to_end_a;



        $student_dairy = StudentDairy::find()
            ->where(['teacher_details_id' => $this->teacher_details_id])
            ->andWhere(['subject_timetable_id' => $this->id])
            ->andWhere(['academic_year_id' => $this->academic_year_id])
            ->andWhere(['section_id' => $this->section_id])
            ->andWhere(['created_on' => $date])
            ->one();
        if (!empty($student_dairy)) {
            $data['dairy_details'] = $student_dairy->asJsonDairyList();
            $data['edit'] = true;
        } else {
            $data['edit'] = false;
        }







        $data['room_id'] =  $this->room_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['status'] =  $this->status;

        $data['period'] =  $this->period;


        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }




    public function asJsonAssignment($date)
    {
        $data = [];
        $data['subject_timetable_id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['day_id'] =  $this->day_id;

        $data['class_id'] =  $this->class_id;

        $data['class'] = $this->class->title . ' ' . $this->section->section_name;

        $data['section_id'] =  $this->section_id;

        $data['section'] = $this->section->section_name;

        $data['subject_group_subject_id'] =  $this->subject_group_subject_id;
        $data['subject'] = $this->subjectGroupSubject->subject->subject_name;

        $data['teacher_details_id'] =  $this->teacher_details_id;




        $time_start = new DateTime($this->time_from);
        $time_start_a =  $time_start->format('g:i a');

        $time_to_end = new DateTime($this->time_to);
        $time_to_end_a =  $time_to_end->format('g:i a');


        $data['time_from'] =  $time_start_a;

        $data['time_to'] =  $time_to_end_a;



        $student_assessment = StudentAssessment::find()
            ->where(['teacher_details_id' => $this->teacher_details_id])
            ->andWhere(['subject_timetable_id' => $this->id])
            // ->andWhere(['academic_year_id' => $this->academic_year_id])
            ->andWhere(['section_id' => $this->section_id])
            ->andWhere(['created_on' => $date])
            ->one();

            // var_dump($this->section_id);
            // var_dump($this->id);
            // var_dump($this->teacher_details_id);
            // var_dump($date);
            // exit;
        if (!empty($student_assessment)) {
            $data['StudentAssessmentDetails'] = $student_assessment->asJsonList();
            $data['edit'] = true;
        } else {
            $data['StudentAssessmentDetails'] = [];
            $data['edit'] = false;
        }







        $data['room_id'] =  $this->room_id;

        $data['academic_year_id'] =  $this->academic_year_id;

        $data['status'] =  $this->status;

        $data['period'] =  $this->period;


        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
