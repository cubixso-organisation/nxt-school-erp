<?php


namespace app\modules\hostelmanagement\models\base;

use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\ParentDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "hostellers".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $campus_id
 * @property integer $hostel_id
 * @property string $joining_date
 * @property string $bill_date
 * @property string $next_bill_date
 * @property integer $sty_type
 * @property double $advance_payment
 * @property double $fees
 * @property integer $room_id
 * @property string $address
 * @property string $aadhar_number
 * @property string $photo
 * @property string $aadhar_front
 * @property string $aadhar_back
 * @property string $application_form_file
 * @property string $leave_of_date
 * @property string $leave_month
 * @property integer $is_all_items_checked
 * @property integer $is_balance_amount_paid
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\hostelmanagement\models\StudentDetails $student
 * @property \app\modules\hostelmanagement\models\Campus $campus
 * @property \app\modules\hostelmanagement\models\Hostels $hostel
 */
class Hostellers extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'student',
            'campus',
            'hostel',
            'room'
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
    public $student_ids = [];
    public function rules()
    {
        return [
            [['student_id', 'campus_id', 'hostel_id', 'joining_date', 'sty_type', 'advance_payment', 'fees', 'room_id',], 'required'],
            [['student_id', 'campus_id', 'hostel_id', 'sty_type', 'room_id', 'is_all_items_checked', 'is_balance_amount_paid',  'create_user_id', 'update_user_id'], 'integer'],

            [['joining_date', 'bill_date', 'next_bill_date', 'leave_of_date', 'created_on', 'updated_on', 'aadhar_number', 'onboarded_by', 'offboarded_by', 'status', 'address','student_ids'], 'safe'],

            [['advance_payment', 'fees'], 'number'],
            [['address', 'photo', 'aadhar_front', 'aadhar_back', 'application_form_file'], 'string', 'max' => 255],
            [['aadhar_number', 'leave_month'], 'string', 'max' => 50],
            [['student_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hostellers';
    }

    public static function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            // self::STATUS_INACTIVE => 'In Active',
            self::STATUS_DELETE => 'Not In Hostel',

        ];
    }
    public  function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-default">Not In Hostel</span>';
        }
    }
    public function afterSave($insert, $changedAttributes)
{
    parent::afterSave($insert, $changedAttributes);

    if (isset($changedAttributes['status'])) {
        $oldStatus = $changedAttributes['status'];
        $newStatus = $this->status;

        // Check if the status has been changed from 'Not In Hostel' to 'Active'
        if ($oldStatus == self::STATUS_DELETE && $newStatus == self::STATUS_ACTIVE) {
            $room = $this->room;
            if ($room) {
                // Ensure there's an available bed in the room before decrementing
                if ($room->available_bed > 0) {
                    $room->available_bed -= 1;
                    $room->save(false);
                }
            }
        }
        // Check if the status has been changed from 'Active' to 'Not In Hostel'
        elseif ($oldStatus == self::STATUS_ACTIVE && $newStatus == self::STATUS_DELETE) {
            $room = $this->room;
            if ($room) {
                $room->available_bed += 1;
                $room->save(false);
            }
        }
    }
}


    public function getRooms($hostel_id)
    {
        $out = [];
        $dat = '';
        $campusId = User::getCampusId();
        $data = Rooms::find()
            ->where(['hostel_id' => $hostel_id])->andWhere(['no_of_beds'])
            ->all();

        foreach ($data as $dat) {
            $out[] = ['id' => $dat['user_id'], 'name' => $dat['student_name']];
        }


        // var_dump($data->createCommand()->getRawSql());
        // exit;


        return $output = [
            'output' => $out
        ];
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
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student Name'),
            'campus_id' => Yii::t('app', 'Campus'),
            'hostel_id' => Yii::t('app', 'Hostel'),
            'joining_date' => Yii::t('app', 'Joining Date'),
            'bill_date' => Yii::t('app', 'Bill Date'),
            'next_bill_date' => Yii::t('app', 'Next Bill Date'),
            'sty_type' => Yii::t('app', 'Sty Type'),
            'advance_payment' => Yii::t('app', 'Advance Payment'),
            'fees' => Yii::t('app', 'Total Fees'),
            'room_id' => Yii::t('app', 'Room'),
            'address' => Yii::t('app', 'Address'),
            'aadhar_number' => Yii::t('app', 'Aadhar Number'),
            'photo' => Yii::t('app', 'Photo'),
            'aadhar_front' => Yii::t('app', 'Aadhar Front'),
            'aadhar_back' => Yii::t('app', 'Aadhar Back'),
            'application_form_file' => Yii::t('app', 'Application Form File'),
            'leave_of_date' => Yii::t('app', 'Date of Leaving'),
            'leave_month' => Yii::t('app', 'Leave Month'),
            'is_all_items_checked' => Yii::t('app', 'Is All Items Checked'),
            'is_balance_amount_paid' => Yii::t('app', 'Is Balance Amount Paid'),
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['user_id' => 'student_id']);
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

    public function getRoom()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Rooms::className(), ['id' => 'room_id']);
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
            // 'uuid' => [
            //     'class' => UUIDBehavior::className(),
            //     'column' => 'id',
            // ],
        ];
    }




    /**
     * @inheritdoc
     * @return \app\models\HostellersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\HostellersQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        if (!empty($this->student)) {
            $data['student_detail']['id'] =  $this->student_id;
            $data['student_detail']['name'] =  $this->student->student_name ?? "";
            $data['student_detail']['gender'] =  $this->gender ?? "";
            $data['student_detail']['rool_number'] =  $this->rool_number ?? "";
            $data['student_detail']['admission_number'] =  $this->admission_number ?? "";
            $data['student_detail']['date_of_birth'] =  $this->date_of_birth ?? "";
            $parentDetails = ParentDetails::find()->where(['id' => $this->student->parent_id])->one();
            $data['student_detail']['name_of_the_father'] =  $parentDetails->name_of_the_father ?? "";
            $data['student_detail']['contact_number'] =  $parentDetails->contact_number ?? "";
        } else {
            $data['student_detail'] = [];
        }



        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['hostel_name'] = $this->hostel->name ?? "";
        $data['hostel_type'] = $this->hostel->type_id ?? "";

        $data['joining_date'] =  $this->joining_date;
        $data['room']['id'] =  $this->room_id;
        $data['room']['name'] =  $this->room->name_of_the_room;
        $data['room']['no_of_beds'] =  $this->room->no_of_beds;

        $data['address'] =  $this->address;

        $data['aadhar_number'] =  $this->aadhar_number;

        $data['photo'] =  $this->photo;

        $data['aadhar_front'] =  $this->aadhar_front;

        $data['aadhar_back'] =  $this->aadhar_back;

        $data['application_form_file'] =  $this->application_form_file;

        $data['leave_of_date'] =  $this->leave_of_date;

        $data['leave_month'] =  $this->leave_month;

        $data['is_all_items_checked'] =  $this->is_all_items_checked;

        $data['is_balance_amount_paid'] =  $this->is_balance_amount_paid;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }


    public function studentListJson($attendanceNo = '')
    {
        $data = [];
        $data['id'] =  $this->id;

        $student_detail = StudentDetails::find()->where(['user_id' => $this->student_id])->one();
        // var_dump($student_detail);
        // exit;
        if (!empty($student_detail)) {
            $data['student_detail']['id'] =  $this->student_id;
            $data['student_detail']['name'] =  $student_detail->student_name ?? "";
            $data['student_detail']['gender'] =  $student_detail->gender ?? "";
            $data['student_detail']['rool_number'] =  $student_detail->rool_number ?? "";
            $data['student_detail']['admission_number'] =  $student_detail->admission_number ?? "";
            $data['student_detail']['profile_photo'] =  $student_detail->profile_photo ?? "";
            $data['student_detail']['date_of_birth'] =  $student_detail->date_of_birth ?? "";
        } else {
            $data['student_detail']['id'] =  "";
            $data['student_detail']['name'] =   "";
            $data['student_detail']['gender'] =   "";
            $data['student_detail']['rool_number'] =   "";
            $data['student_detail']['profile_photo'] = "";
            $data['student_detail']['admission_number'] =   "";
            $data['student_detail']['date_of_birth'] =   "";
        }
        if (!empty($this->student->parent_id)) {
            $parentDetails = ParentDetails::find()->where(['id' => $this->student->parent_id])->one();
            $data['student_detail']['name_of_the_father'] = $parentDetails->name_of_the_father ?? "";
            $data['student_detail']['contact_number'] = $parentDetails->contact_number ?? "";
            $data['student_detail']['email'] = $parentDetails->email ?? "";
            $data['student_detail']['address'] = $parentDetails->current_address ?? "";
        }
        $data['hostel_id'] = $this->hostel->id ?? "";
        $data['hostel_name'] = $this->hostel->name ?? "";



        $data['room']['id'] =  $this->room_id;
        $data['room']['name'] =  $this->room->name_of_the_room;
        $data['room']['no_of_beds'] =  $this->room->no_of_beds;


        $floor = Floor::find()->where(['id' => $this->room->floor_id])->one();
        if (!empty($floor)) {
            $data['floor']['id'] =  $floor->id ?? "";
            $data['floor']['name'] = $floor->name_of_floor ?? "";
        } else {
            $data['floor']['id'] =  "";
            $data['floor']['name'] = "";
        }

        // Attandance


        $hostellersAttandance = HostellersAttandance::find()->where(['student_id' => $this->student_id])->andWhere(['DATE(date)' => date('Y-m-d')])->andWhere(['attendance_count_perday' => (int)$attendanceNo])->one();


        if (!empty($hostellersAttandance)) {
            $data['attandance']['id'] = $hostellersAttandance->id;
            $data['attandance']['present_or_absent'] = $hostellersAttandance->attandance;
        } else {
            $data['attandance']['id'] = 0;
            $data['attandance']['present_or_absent'] = HostellersAttandance::NOT_MARKED;
        }
        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }

    public function getdata($hostel_id)
    {
        if ($hostel_id) {
            $data = Rooms::find()->where(['hostel_id' => $hostel_id])->andWhere(['>', 'no_of_beds', 0])->all();
            return json_encode($data);
        } else {
            return json_encode([]); // Return an empty array when there is no data
        }
    }

    public function getstudentdata($student_id)
    {
        if ($student_id) {
            $data = StudentDetails::find()->where(['id' => $student_id])->one();
            return json_encode($data);
        } else {
            return json_encode([]); // Return an empty array when there is no data
        }
    }


    public function asJsonForRoomStudents($attendanceNo = '')
    {
        $data = [];
        $data['id'] =  $this->id;

        // if (!empty($this->student)) {
        $data['student_detail']['id'] = $this->student_id;
        $data['student_detail']['name'] = $this->student->student_name ?? "";
        $data['student_detail']['gender'] = $this->student->gender ?? "";
        $data['student_detail']['rool_number'] = $this->student->rool_number ?? "";
        $data['student_detail']['admission_number'] = $this->student->admission_number ?? "";
        $data['student_detail']['date_of_birth'] = $this->student->date_of_birth ?? "";
        $data['student_detail']['blood_group'] = $this->student->blood_group_id ?? "";
        $data['student_detail']['academic_year'] = $this->student->academic_year ?? "";
        if (!empty($this->student->parent_id)) {
            $parentDetails = ParentDetails::find()->where(['id' => $this->student->parent_id])->one();
            $data['student_detail']['name_of_the_father'] = $parentDetails->name_of_the_father ?? "";
            $data['student_detail']['contact_number'] = $parentDetails->contact_number ?? "";
            $data['student_detail']['email'] = $parentDetails->email ?? "";
            $data['student_detail']['address'] = $parentDetails->current_address ?? "";
        }
        $data['campus_id'] =  $this->campus_id;
        $hostellersAttandance = HostellersAttandance::find()->where(['student_id' => $this->student_id])->andWhere(['DATE(date)' => date('Y-m-d')])->andWhere(['attendance_count_perday' => $attendanceNo])->one();

        if (!empty($hostellersAttandance)) {
            $data['attandance']['id'] = $hostellersAttandance->id;
            $data['attandance']['present_or_absent'] = $hostellersAttandance->attandance;
        } else {
            $data['attandance']['id'] = 0;
            $data['attandance']['present_or_absent'] = HostellersAttandance::NOT_MARKED;
        }
        $data['hostel_id'] =  $this->hostel_id;

        $data['hostel_name'] = $this->hostel->name;;
        $data['hostel_type'] = $this->hostel->type_id;

        $data['joining_date'] =  $this->joining_date;

        $data['bill_date'] =  $this->bill_date;

        $data['next_bill_date'] =  $this->next_bill_date;

        $data['sty_type'] =  $this->sty_type;

        $data['advance_payment'] =  $this->advance_payment;

        $data['fees'] =  $this->fees;

        $data['room']['id'] =  $this->room_id;

        $data['room']['name'] =  $this->room->name_of_the_room;

        $data['room']['no_of_beds'] =  $this->room->no_of_beds;
        $data['floor']['floor_id'] = $this->room->floor->id;

        $data['floor']['name'] = $this->room->floor->name_of_floor;

        $data['address'] =  $this->address;

        $data['aadhar_number'] =  $this->aadhar_number;

        $data['photo'] =  $this->photo ?? Null;

        $data['aadhar_front'] =  $this->aadhar_front;

        $data['aadhar_back'] =  $this->aadhar_back;

        $data['application_form_file'] =  $this->application_form_file;

        $data['leave_of_date'] =  $this->leave_of_date;

        $data['leave_month'] =  $this->leave_month;

        $data['is_all_items_checked'] =  $this->is_all_items_checked;

        $data['is_balance_amount_paid'] =  $this->is_balance_amount_paid;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
