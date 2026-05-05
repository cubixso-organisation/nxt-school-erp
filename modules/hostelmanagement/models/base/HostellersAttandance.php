<?php


namespace app\modules\hostelmanagement\models\base;

use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\hostelmanagement\models\HostellersAttandance as ModelsHostellersAttandance;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "hostellers_attandance".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $hostel_id
 * @property integer $student_id
 * @property integer $room_id
 * @property integer $attandance
 * @property string $date
 * @property integer $attandance_by
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\hostelmanagement\models\Campus $campus
 * @property \app\modules\hostelmanagement\models\Hostels $hostel
 * @property \app\modules\hostelmanagement\models\Rooms $room
 */
class HostellersAttandance extends \yii\db\ActiveRecord
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
            'hostel',
            'room',
            'student',
            'attandanceBy'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const PRESENT = 1;
    const ABSENT = 2;
    const NOT_MARKED = 3;


    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'hostel_id', 'student_id', 'room_id', 'date', 'attandance_by', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'hostel_id', 'student_id', 'room_id', 'attandance', 'attandance_by', 'status', 'create_user_id', 'update_user_id','attendance_count_perday'], 'integer'],
            [['date', 'created_on', 'updated_on','attendance_count_perday'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hostellers_attandance';
    }

    public function getAttendanceOptions()
    {
        return [

            self::PRESENT => 'Present',
            self::NOT_MARKED => 'Not Marked',
            self::ABSENT => 'Absent',

        ];
    }
    public function getAttendanceOptionsBadges()
    {

        if ($this->attandance == self::PRESENT) {
            return '<span class="badge badge-success">Present</span>';
        } elseif ($this->attandance == self::NOT_MARKED) {
            return '<span class="badge badge-default">Not Marked</span>';
        } elseif ($this->attandance == self::ABSENT) {
            return '<span class="badge badge-danger">Absent</span>';
        }
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
            'hostel_id' => Yii::t('app', 'Hostel ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'room_id' => Yii::t('app', 'Room ID'),
            'attandance' => Yii::t('app', 'Attendance'),
            'date' => Yii::t('app', 'Date'),
            'attandance_by' => Yii::t('app', 'Attendance By'),
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
    public function getHostel()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Hostels::className(), ['id' => 'hostel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Rooms::className(), ['id' => 'room_id']);
    }
    public function getStudent()
    {
        return $this->hasOne(User::className(), ['id' => 'student_id']);
    }
    public function getAttandanceBy()
    {
        return $this->hasOne(User::className(), ['id' => 'attandance_by']);
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
     * @return \app\modules\hostelmanagement\models\HostellersAttandanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\hostelmanagement\models\HostellersAttandanceQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;


        $data['student_id'] =  $this->student_id;

        $data['room_id'] =  $this->room_id;

        $data['attandance'] =  $this->attandance;

        $data['attendance_count_perday'] =  $this->attendance_count_perday;

        $data['date'] =  $this->date;

        $data['attandance_by'] =  $this->attandance_by;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }

    public function asJsonForAttendenceHistory()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;
        $data['hostel_name'] = $this->hostel->name_of_the_hostel;
        $data['student_id'] =  $this->student_id;

        $student_details = StudentDetails::find()->where(['user_id' => $this->student_id])->one();

        if (!empty($student_details)) {
            $data['student_details']['id'] =  $this->student_id;
            $data['student_details']['name'] =  $student_details->student_name ?? "";
            $data['student_details']['gender'] =  $student_details->gender ?? "";
            $data['student_details']['rool_number'] =  $student_details->rool_number ?? "";
            $data['student_details']['admission_number'] =  $student_details->admission_number ?? "";
            $data['student_details']['profile_photo'] =  $student_details->profile_photo ?? "";
            $data['student_details']['date_of_birth'] =  $student_details->date_of_birth ?? "";
        } else {
            $data['student_details']['id'] =  "";
            $data['student_details']['name'] =   "";
            $data['student_details']['gender'] =   "";
            $data['student_details']['rool_number'] =   "";
            $data['student_details']['profile_photo'] = "";
            $data['student_details']['admission_number'] =   "";
            $data['student_details']['date_of_birth'] =   "";
        }

        $data['room_id'] =  $this->room_id;

        $data['room_name'] = $this->room->name_of_the_room;
        $data['floor_no'] = $this->room->floor->name_of_floor;

        $data['attandance'] =  !empty($this->attandance) ? $this->attandance :  HostellersAttandance::NOT_MARKED;

        $data['attendance_count_perday'] =  !empty($this->attendance_count_perday) ? $this->attendance_count_perday :  HostellersAttandance::NOT_MARKED;

        $data['date'] =  $this->date;

        $data['attandance_by'] =  $this->attandance_by;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
    // public function studentAttendenceDetails()
    // {

    //     $data['student_id'] =  $this->id;
    //     $data['campus_id'] =  $this->campus_id;
    //     // $data['user_id'] =  $this->user_id;

    //     $data['admission_number'] =  $this->admission_number;
    //     $data['rool_number'] =  $this->rool_number;
    //     $data['profile_photo'] =  $this->profile_photo;
    //     $data['student_name'] =  $this->student_name;
    //     $data['gender'] =  $this->gender;
    //     $data['date_of_birth'] =  $this->date_of_birth;
    //     $data['phone_number'] =  $this->phone_number;

    //     $hostellersAttendance = HostellersAttandance::find()->where(['student_id' => $this->user_id])->all();

    //     $data['attendance'] = [];

    //     foreach ($hostellersAttendance as $attendance) {
    //         $attendanceData = [
    //             'id' => $attendance->id,
    //             'present_or_absent' => $attendance->attandance,  // Assuming 'attendance' is the correct attribute name
    //             'date' => $attendance->date,
    //         ];

    //         $data['attendance'][] = $attendanceData;
    //     }

    //     if (empty($data['attendance'])) {
    //         $defaultAttendanceData = [
    //             'id' => 0,
    //             'present_or_absent' => HostellersAttandance::NOT_MARKED,
    //             'date' => null, // You might want to set a default value for the date or omit it if not needed
    //         ];

    //         $data['attendance'][] = $defaultAttendanceData;
    //     }
    //     return $data;
    // }
}
