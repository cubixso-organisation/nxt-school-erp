<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\BusDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "student_has_bus".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_id
 * @property integer $bus_id
 * @property integer $bus_route_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\StudentAttendanceBus[] $studentAttendanceBuses
 * @property \app\modules\admin\models\StudentDetails $student
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\BusDetails $bus
 * @property \app\modules\admin\models\BusRoute $busRoute
 * @property \app\modules\admin\models\Campus $campus
 */
class StudentHasBus extends \yii\db\ActiveRecord
{ 
    use \mootensai\relation\RelationTrait;
    public $student_class_id;
    public $class_section_id;

 
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'studentAttendanceBuses',
            'student',
            'createUser',
            'updateUser',
            'bus',
            'busRoute',
            'campus'
        ];
    } 

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;

    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_id', 'bus_id', 'bus_route_id', 'status'], 'required'],
            [['campus_id', 'student_id', 'bus_id', 'bus_route_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            ['student_id', 'validateStudentState', 'on' =>'update'],

        ];
    }


    public function validateStudentState($attribute, $params)
    {
        // $this->addError($attribute, 'Authorize contact number already exists');
         $student_has_bus = StudentHasBus::find()->where(['student_id'=>$this->student_id])->one();
         if(!empty( $student_has_bus )){
            $bus_id = $student_has_bus->bus_id;
            //get bus status
            $bus_details = BusDetails::find()->where(['id'=>$bus_id])->one();
            if(!empty($bus_details)){
                if($bus_details->status==BusDetails::STATUS_DRIVE_MODE||$bus_details->status==BusDetails::STATUS_PARKING){
                    if($bus_details->status==BusDetails::STATUS_DRIVE_MODE){
                        $this->addError($attribute, 'You can not update student bus is Driving mode');
   
                    }

                }else{
                    $this->addError($attribute, 'Bus Status Inactive');
  
                }

            }else{
                $this->addError($attribute, 'Bus Data Not Found');

            }


         }else{
            $this->addError($attribute, 'Student Data Not Found');
         }

    }






    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_has_bus';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
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
              'campus_id' => Yii::t('app', 'School Or College'),
            'student_id' => Yii::t('app', 'Student'),
            'bus_id' => Yii::t('app', 'Bus'),
            'bus_route_id' => Yii::t('app', 'Bus Route'),
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
    public function getStudentAttendanceBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentAttendanceBus::className(), ['student_has_bus_id' => 'id']);
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
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
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
    public function getBus()
    {
        return $this->hasOne(\app\modules\admin\models\BusDetails::className(), ['id' => 'bus_id']);
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
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
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



    public function getBusRouteData($bus_id){

        $out = [];
        $data = BusRoute::find()
            ->where(['bus_id' => $bus_id])
            // ->andWhere(['status'=>State::STATUS_ACTIVE])
            ->asArray()
            ->all();
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['point_name']];
        }
        return $output = [
            'output' => $out
        ];

    }





    /**
     * @inheritdoc
     * @return \app\modules\admin\models\StudentHasBusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentHasBusQuery(get_called_class());
    }
public function asJson()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['student_id'] =  $this->student_id;

    $data['bus_id'] =  $this->bus_id;

    $data['bus_route_id'] =  $this->bus_route_id;

    $data['status'] =  $this->status;



    return $data;
}
}
