<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\BusStatus;
use app\modules\admin\models\StudentAttendanceBus;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\StudentHasBus;
use Yii;
use yii\behaviors\TimestampBehavior; 
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "bus_route".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $bus_id
 * @property string $point_name
 * @property double $lat
 * @property double $lng
 * @property integer $short_order
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\BusDetails $bus
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentHasBus[] $studentHasBuses
 */
class BusRoute extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $student_class_id;
    public $section_id;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'bus',
            'updateUser',
            'createUser',
            'campus',
            'busStatuses',
            'studentAttendanceBuses',
            'studentHasBuses'
        ];
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_REACHED  = 1;
    public const STATUS_LEFT  = 2;
    public const STATUS_SKIP  = 3;
    public const STATUS_COMPLETED =4;
    public const STATUS_NEXT_STOP=5;


    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'bus_id','coordinates','short_order','point_name', 'lat', 'lng'], 'required'],
            [['campus_id', 'bus_id', 'short_order', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['created_on', 'updated_on'], 'safe'],
            [['point_name'], 'string', 'max' => 255],
            ['short_order', 'validateRouteSortOrderCreate', 'on' => 'create'],
            ['short_order', 'validateRouteSortOrderUpdate', 'on' => 'update'],


        ];
    }




    public function validateRouteSortOrderCreate($attribute, $params)
    {
        $sortOrder = BusRoute::find()
            ->where(['campus_id' => $this->campus_id])
            ->andWhere(['bus_id' => $this->bus_id])
            ->andWhere(['short_order' => $this->short_order])
            ->one();
        if (!empty($sortOrder)) {
            $this->addError($attribute, 'Sort Order already taken try another');
        }
    }


    public function validateRouteSortOrderUpdate($attribute, $params)
    {
        $sortOrder = BusRoute::find()
            ->where(['id' => $this->id])
            ->one();
        if (!empty($sortOrder)) {
            if ($sortOrder->short_order==$this->short_order) {
            } else {
                $sortOrderAnother = BusRoute::find()
                ->where(['campus_id' => $this->campus_id])
                ->andWhere(['bus_id' => $this->bus_id])
                ->andWhere(['short_order' => $this->short_order])
                ->one();
                if (!empty($sortOrderAnother)) {
                    $this->addError($attribute, 'Sort Order already taken try another');
                } else {
                }
            }
        } else {
        }
    }








    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_route';
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
            'bus_id' => Yii::t('app', 'Bus'),
            'point_name' => Yii::t('app', 'Point Name'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'short_order' => Yii::t('app', 'Route Order'),
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
    public function getBusStatuses()
    {
        return $this->hasMany(\app\modules\admin\models\BusStatus::className(), ['bus_route_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAttendanceBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentAttendanceBus::className(), ['bus_route_id' => 'id']);
    }




    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(\app\modules\admin\models\BusDetails::className(), ['id' => 'bus_id']);
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
    public function getStudentHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasBus::className(), ['bus_route_id' => 'id']);
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
                'value' =>date('Y-m-d H:i:s'),
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
     * @return \app\modules\admin\models\BusRouteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\BusRouteQuery(get_called_class());
    }
public function asJson()
{
    $data = [] ;
    $data['bus_route_id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['bus_id'] =  $this->bus_id;

    $data['point_name'] =  $this->point_name;

    $data['lat'] =  $this->lat;

    $data['lng'] =  $this->lng;

    $data['short_order'] =  $this->short_order;

    $data['status'] =  $this->status;
    $data['session_key'] =  $this->session_key;

    $data['unique_key'] =  $this->unique_key;

    //get Student Details
    $student_has_bus = StudentHasBus::find()->where(['bus_route_id'=> $this->id])->all();
    if (!empty($student_has_bus)) {
        foreach ($student_has_bus as $student_has_bus_data) {
            $student_id[] = $student_has_bus_data->student_id;
        }

        $data['TotalStudentCountOfBusRote'] = count($student_has_bus);
    } else {
        $data['TotalStudentCountOfBusRote'] = 0;
    }


    $bus_status = BusStatus::find()
    ->where(['bus_status.bus_route_id'=>$this->id])
    ->andWhere(['bus_status.unique_key'=>$this->unique_key])
    ->andWhere(['bus_status.session_key'=>$this->session_key])
    ->one();



    if (!empty($bus_status)) {
        $data['bus_status'] = $bus_status->asJson();
    } else {
        $data['bus_status'] = [
            "bus_status_id"=> null,
            "bus_reached_time"=> null,
            "bus_left_time"=> null,
            "unique_key"=> null,
            "status_direction"=> null,
            "created_on"=> null,
            "updated_on"=> null,
            "status"=> null
        ];
    }

    $student_route_absent = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andWhere(['student_attendance_bus.unique_key'=>$this->unique_key])
    ->andWhere(['status'=>StudentAttendanceBus::STATUS_ABSENT])
    ->count();
    $student_route_present = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andWhere(['unique_key'=>$this->unique_key])
    ->andWhere(['status'=>StudentAttendanceBus::STATUS_PRESENT])
    ->count();

    $data['student_route_absent'] = $student_route_absent;
    $data['student_route_present'] = $student_route_present;


    return $data;
}




public function asJsonByDate($startDate='', $endDate='')
{
    $data = [] ;
    $data['bus_route_id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['bus_id'] =  $this->bus_id;

    $data['point_name'] =  $this->point_name;

    $data['lat'] =  $this->lat;

    $data['lng'] =  $this->lng;

    $data['short_order'] =  $this->short_order;

    $data['status'] =  $this->status;


    //get Student Details
    $student_has_bus = StudentHasBus::find()->where(['bus_route_id'=> $this->id])
    ->all();
    if (!empty($student_has_bus)) {
        foreach ($student_has_bus as $student_has_bus_data) {
            $student_id[] = $student_has_bus_data->student_id;
        }

        $data['TotalStudentCountOfBusRote'] = count($student_has_bus);
    } else {
        $data['TotalStudentCountOfBusRote'] = 0;
    }

    $bus_status = BusStatus::find()->where(['bus_route_id'=>$this->id])
    ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
    ->one();
    if (!empty($bus_status)) {
        $data['bus_status'] = $bus_status->asJson();
    } else {
        $data['bus_status'] = [
            "bus_status_id"=> null,
            "bus_reached_time"=> null,
            "bus_left_time"=> null,
            "unique_key"=> null,
            "status_direction"=> null,
            "created_on"=> null,
            "updated_on"=> null,
            "status"=> null
        ];
    }

    $student_route_absent = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
    ->andWhere(['status'=>StudentAttendanceBus::STATUS_ABSENT])
    ->count();
    $student_route_present = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
    ->andWhere(['status'=>StudentAttendanceBus::STATUS_PRESENT])

    ->count();

    $data['student_route_absent'] = $student_route_absent;
    $data['student_route_present'] = $student_route_present;




    return $data;
}





public function asJsonByDateParent($startDate='', $endDate='', $student_id='')
{
    $data = [] ;
    $data['bus_route_id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['bus_id'] =  $this->bus_id;

    $data['point_name'] =  $this->point_name;

    $data['lat'] =  $this->lat;

    $data['lng'] =  $this->lng;

    $data['short_order'] =  $this->short_order;

    $data['status'] =  $this->status;


    $bus_status = BusStatus::find()->where(['bus_route_id'=>$this->id])
    ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
    ->one();
    if (!empty($bus_status)) {
        $data['bus_status'] = $bus_status->asJson();
    } else {
        $data['bus_status'] = [
            "bus_status_id"=> null,
            "bus_reached_time"=> null,
            "bus_left_time"=> null,
            "unique_key"=> null,
            "status_direction"=> null,
            "created_on"=> null,
            "updated_on"=> null,
            "status"=> null
        ];
    }




    $student_route_absent = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
    ->andWhere(['student_id'=>$student_id])
    ->one();


    $data['student_route_p_a'] = $student_route_absent;
    




    return $data;
}


public function asJsonByDateParentLive($student_id='')
{
    $data = [] ;
    $data['bus_route_id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['bus_id'] =  $this->bus_id;

    $data['point_name'] =  $this->point_name;

    $data['lat'] =  $this->lat;

    $data['lng'] =  $this->lng;

    $data['short_order'] =  $this->short_order;

    $data['status'] =  $this->status;


     $bus_status = BusStatus::find()
    ->where(['bus_status.bus_route_id'=>$this->id])
    ->andWhere(['bus_status.unique_key'=>$this->unique_key])
    ->andWhere(['bus_status.session_key'=>$this->session_key])
    ->one();
    if (!empty($bus_status)) {
        $data['bus_status'] = $bus_status->asJson();
    } else {
        $data['bus_status'] = [
            "bus_status_id"=> null,
            "bus_reached_time"=> null,
            "bus_left_time"=> null,
            "unique_key"=> null,
            "status_direction"=> null,
            "created_on"=> null,
            "updated_on"=> null,
            "status"=> null
        ];
    }


    $student_route_absent = StudentAttendanceBus::find()
    ->where(['bus_route_id'=>$this->id])
    ->andWhere(['unique_key'=>$this->unique_key])
    ->andWhere(['session_key'=>$this->session_key])
    ->andWhere(['student_id'=>$student_id])
    ->one();


    




    return $data;
}










public function asJsonRouteDetails()
{
    $data = [] ;
    $data['bus_route_id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['bus_id'] =  $this->bus_id;

    $data['point_name'] =  $this->point_name;

    $data['lat'] =  $this->lat;

    $data['lng'] =  $this->lng;

    $data['short_order'] =  $this->short_order;

    $data['status'] =  $this->status;

    return $data;
}
}
