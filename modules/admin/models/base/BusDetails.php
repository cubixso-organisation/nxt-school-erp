<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\BusRoute;
use app\modules\admin\models\StudentAttendanceBus;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "bus_details".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $title 
 * @property string $vehicle_number
 * @property integer $route_no
 * @property string $start_point
 * @property string $end_point
 * @property double $start_point_lat
 * @property double $start_point_lng
 * @property double $end_point_lat
 * @property double $end_point_lng
 * @property integer $type
 * @property integer $status
 * @property integer $current_status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\BusRoute[] $busRoutes
 * @property \app\modules\admin\models\BusStatus[] $busStatuses
 * @property \app\modules\admin\models\DriverHasBus[] $driverHasBuses
 * @property \app\modules\admin\models\StudentHasBus[] $studentHasBuses
 */
class BusDetails extends \yii\db\ActiveRecord
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
            'updateUser',
            'createUser',
            'busRoutes',
            'driverHasBuses',
            'studentHasBuses'
        ];
    }

    public const STATUS_DRIVE_MODE = 1;
    public const STATUS_PARKING = 2;



    public const current_status_active = 1;
    public const current_status_in_active = 0;



    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    public const status_direction_school = 1;
    public const status_direction_from_school = 2;

    const trip_type_single_trip = 1;
    const trip_type_round_trip = 2;

    const end_drive_yes = 1;
    const end_drive_no = 2;

    public $phone_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'title', 'vehicle_number', 'start_point_coordinates', 'trip_type', 'end_point_coordinates', 'route_no', 'start_point_lat', 'start_point_lng', 'end_point_lat',  'end_point_lng'], 'required'],
            [['campus_id', 'route_no', 'type', 'status', 'current_status', 'create_user_id', 'update_user_id'], 'integer'],
            [['start_point_lat', 'start_point_lng', 'end_point_lat', 'end_point_lng'], 'number'],
            [['created_on', 'phone_number', 'updated_on'], 'safe'],
            [['title', 'vehicle_number'], 'string', 'max' => 255],
            [['start_point', 'end_point'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_details';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_DRIVE_MODE => 'On Trip',
            self::STATUS_PARKING => 'Parking',


        ];
    }


    public function getTripTypeOptions()
    {
        return [

            self::trip_type_single_trip => 'single Trip',
            self::trip_type_round_trip => 'Round Trip',


        ];
    }





    public function getTripTypeOptionsBadges()
    {
        if ($this->trip_type == self::trip_type_single_trip) {
            return '<span class="badge badge-success">Single Trip</span>';
        } elseif ($this->trip_type == self::trip_type_round_trip) {
            return '<span class="badge badge-default">Round Trip</span>';
        }
    }








    public function getCurrentStateOptions()
    {
        return [

            self::current_status_active => 'Active',
            self::current_status_in_active => 'Inactive',


        ];
    }


    public function getCurrentStateOptionsBadges()
    {
        if ($this->current_status == self::current_status_active) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->current_status == self::current_status_in_active) {
            return '<span class="badge badge-danger">Inactive</span>';
        }
    }



    public function getStateDirectionOptionsBadges()
    {
        if ($this->status_direction == self::status_direction_school) {
            return '<span class="badge badge-success">To School</span>';
        } elseif ($this->status_direction == self::status_direction_from_school) {
            return '<span class="badge badge-default">From School</span>';
        }
    }









    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_DRIVE_MODE) {
            return '<span class="badge badge-success">On Trip</span>';
        } elseif ($this->status == self::STATUS_PARKING) {
            return '<span class="badge badge-default">Parking</span>';
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
            'campus_id' => Yii::t('app', 'School Or College'),
            'title' => Yii::t('app', 'Title'),
            'vehicle_number' => Yii::t('app', 'Vehicle Number'),
            'route_no' => Yii::t('app', 'Route No'),
            'start_point' => Yii::t('app', 'Start Point'),
            'end_point' => Yii::t('app', 'End Point'),
            'start_point_lat' => Yii::t('app', 'Start Point Lat'),
            'start_point_lng' => Yii::t('app', 'Start Point Lng'),
            'end_point_lat' => Yii::t('app', 'End Point Lat'),
            'end_point_lng' => Yii::t('app', 'End Point Lng'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'current_status' => Yii::t('app', 'Current Status'),
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
    public function getBusRoutes()
    {
        return $this->hasMany(\app\modules\admin\models\BusRoute::className(), ['bus_id' => 'id']);
    }






    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusStatuses()
    {
        return $this->hasMany(\app\modules\admin\models\BusStatus::className(), ['bus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\DriverHasBus::className(), ['bus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasBus::className(), ['bus_id' => 'id']);
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
     * @return \app\modules\admin\models\BusDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\BusDetailsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['title'] =  $this->title;

        $data['vehicle_number'] =  $this->vehicle_number;

        $data['route_no'] =  $this->route_no;

        $data['start_point'] =  $this->start_point;

        $data['end_point'] =  $this->end_point;

        $data['start_point_lat'] =  $this->start_point_lat;

        $data['start_point_lng'] =  $this->start_point_lng;

        $data['end_point_lat'] =  $this->end_point_lat;

        $data['end_point_lng'] =  $this->end_point_lng;

        $data['session_key'] =  $this->session_key;


        $data['type'] =  $this->type;
        $data['status_direction'] =  $this->status_direction;

        $data['status'] =  $this->status;

        $data['current_status'] =  $this->current_status;

        $data['trip_type'] =  $this->trip_type;


        $driverDetails = DriverHasBus::find()->where(['bus_id' => $this->id])->one();
        if (!empty($driverDetails)) {
            $driver_id  = $driverDetails->driver_id;
            $employee_details = EmployeeDetails::find()->where(['user_id' => $driver_id])->one();
            if (!empty($employee_details)) {
                $data['DriverDetails'] = $employee_details->asJsonBusDriver();
            } else {
                $data['DriverDetails'] =  (object)[];
            }
        } else {
            $data['DriverDetails'] =  (object)[];
        }

        $student_has_bus = StudentHasBus::find()->where(['bus_id' => $this->id])->count();
        $data['studentCount'] = $student_has_bus;
        $student_attendance_bus_present = StudentAttendanceBus::find()->where(['session_key' => $this->session_key])->andWhere(['status' => StudentAttendanceBus::STATUS_PRESENT])->count();
        $student_attendance_bus_absent = StudentAttendanceBus::find()->where(['session_key' => $this->session_key])->andWhere(['status' => StudentAttendanceBus::STATUS_ABSENT])->count();
        $data['present'] = $student_attendance_bus_present;
        $data['absent'] = $student_attendance_bus_absent;

        $current_stop = BusRoute::find()->where(['id' => $this->current_stop])->one();

        $next_stop = BusRoute::find()->where(['id' => $this->next_stop])->one();
        if (!empty($next_stop)) {
            $data['next_stop'] = $next_stop->asJsonRouteDetails();
        } else {
            $data['next_stop'] = '';
        }



        $current_stop = BusRoute::find()->where(['id' => $this->current_stop])->one();
        if (!empty($current_stop)) {
            $data['current_stop'] = $current_stop->asJsonRouteDetails();
        } else {
            $data['current_stop'] = '';
        }



        $data['endDrive'] = $this->endDrive;

        return $data;
    }
}
