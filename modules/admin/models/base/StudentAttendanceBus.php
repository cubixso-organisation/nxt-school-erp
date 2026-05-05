<?php

namespace app\modules\admin\models\base;

use app\models\User;
use app\modules\admin\models\StudentHasBus;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_attendance_bus".
 *
 * @property integer $id
 * @property integer $bus_route_id
 * @property integer $student_id
 * @property integer $student_has_bus_id
 * @property string $unique_key 
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentHasBus $studentHasBus
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\BusRoute $busRoute
 */
class StudentAttendanceBus extends \yii\db\ActiveRecord
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
            'studentHasBus',
            'createUser',
            'updateUser',
            'student',
            'busRoute',
            'studentPickedUpPoint',
            'actualPickupPoint'
        ];
    }
 
    public const STATUS_ABSENT = 0;
    public const STATUS_PRESENT = 1;

    const student_status_missed =0;
    const student_status_picked =1;
    const student_status_reached =2;
    const student_status_left  =3;
    const student_status_dropped =4;



    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_route_id', 'student_id', 'student_has_bus_id'], 'required'],
            [['bus_route_id', 'student_id', 'student_has_bus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['unique_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_attendance_bus';
    }




    public function getStateOptions()
    {
        return [

            self::STATUS_ABSENT => 'Absent',
            self::STATUS_PRESENT => 'Present',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_PRESENT) {
            return '<span class="badge badge-success">Present</span>';
        } elseif ($this->status == self::STATUS_ABSENT) {
            return '<span class="badge badge-danger">Absent</span>';
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
            'bus_route_id' => Yii::t('app', 'Bus Route ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'student_has_bus_id' => Yii::t('app', 'Student Has Bus ID'),
            'unique_key' => Yii::t('app', 'Unique Key'),
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
    public function getStudentHasBus()
    {
        return $this->hasOne(\app\modules\admin\models\StudentHasBus::className(), ['id' => 'student_has_bus_id']);
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
    public function getStudent()
    {
        return $this->hasOne(\app\modules\admin\models\StudentDetails::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusRoute()
    {
        return $this->hasOne(\app\modules\admin\models\BusRoute::className(), ['id' => 'bus_route_id']);
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
    public function getStudentPickedUpPoint()
    {
        return $this->hasOne(\app\modules\admin\models\BusRoute::className(), ['id' => 'student_picked_up_point']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActualPickupPoint()
    {
        return $this->hasOne(\app\modules\admin\models\BusRoute::className(), ['id' => 'actual_pickup_point']);
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
     * @return \app\modules\admin\models\StudentAttendanceBusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentAttendanceBusQuery(get_called_class());
    }
public function asJson()
{
    $data = [] ;
    $data['student_attendance_bus_id'] =  $this->id;

    $data['bus_route_id'] =  $this->bus_route_id;

    $data['student_id'] =  $this->student_id;

    $data['student_has_bus_id'] =  $this->student_has_bus_id;

    $data['unique_key'] =  $this->unique_key;

    $data['status'] =  $this->status;
    $data['student_status'] =  $this->student_status;

    $data['student_picked_up_point'] =  $this->busRoute->point_name;
    $data['student_reached_school'] =  $this->student->campus->name_of_the_educational_Institution;
    $student_has_parent = StudentHasParent::find()->where(['student_id'=>$this->student_id])->one();
    if(!empty( $student_has_parent)){
        $parent_id  = $student_has_parent->parent_id;

    }else{
        $parent_id  ='';
    }

    $parent_details = User::find()->where(['id'=>$parent_id])->one();
    if(!empty(  $parent_details)){
        $data['student_reached_home_address'] =  $parent_details->address;
        $data['pickup_point_time'] =  $this->pickup_point_time;
        $data['school_reached_time'] =  $this->school_reached_time;
        $data['school_left_time'] =  $this->school_left_time;
        $data['home_reached_time'] =  $this->home_reached_time;
    }else{
        $data['student_reached_home_address'] =  '';
        $data['pickup_point_time'] =  '';
        $data['school_reached_time'] =  '';
        $data['school_left_time'] = '';
        $data['home_reached_time'] =  '';
    }
   




    $data['created_on'] =  $this->created_on;

    $data['updated_on'] =  $this->created_on;

 

    return $data;
}


}
