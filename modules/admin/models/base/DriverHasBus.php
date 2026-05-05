<?php

namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "driver_has_bus".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $driver_id
 * @property integer $bus_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\BusDetails $bus
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $driver
 */
class DriverHasBus extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public $phone_number;

 
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'bus',
            'campus',
            'createUser',
            'updateUser',
            'driver'
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
            [['campus_id', 'driver_id', 'bus_id', 'status'], 'required'],
            [['campus_id', 'driver_id', 'bus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on','phone_number'], 'safe'],
            ['driver_id', 'validateBusStatus', 'on' =>'update'],

        ];
    }




    public function validateBusStatus($attribute, $params)
    {
        // echo $this->bus_id;
        // exit;
        $DriverHasBus = DriverHasBus::find()->where(['driver_id'=>$this->driver_id])->one();

        if (!empty($DriverHasBus)) {
            $bus_id = $DriverHasBus->bus_id;
            //get bus status
            $bus_details = BusDetails::find()->where(['id'=>$bus_id])->one();

            if (!empty($bus_details)) {
                if ($bus_details->status==BusDetails::STATUS_DRIVE_MODE||$bus_details->status==BusDetails::STATUS_PARKING) {
                    if ($bus_details->status==BusDetails::STATUS_DRIVE_MODE) {
                        $this->addError($attribute, 'You can not update Driver bus is Driving mode');
                    } else {
                        if ($this->bus_id != $DriverHasBus->bus_id) {
                            //check updated bus status
                            $bus_current_status = BusDetails::find()->where(['id'=>$this->bus_id])->one();
                            if (!empty($bus_current_status)) {
                                if ($bus_current_status->status !=BusDetails::STATUS_DRIVE_MODE) {
                                    $update_driver_has_bus_lst = DriverHasBus::find()->where(['bus_id'=>$this->bus_id])->one();
                                    if (!empty($update_driver_has_bus_lst)) {
                                        $update_driver_has_bus_lst->status = DriverHasBus::STATUS_INACTIVE;
                                        $update_driver_has_bus_lst->save(false);
                                    }
                                } else {
                                    $this->addError($attribute, 'You assigned bus is driving mode you can not change now retry after some time');
                                }
                            } else {
                                $this->addError($attribute, 'Bus Details Not Found');
                            }
                        }
                    }
                } else {
                    $this->addError($attribute, 'Bus Status Inactive');
                }
            } else {
                $this->addError($attribute, 'Bus Data Not Found');
            }
        } else {
            $this->addError($attribute, 'Driver Data Not Found');
        }
    }




    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'driver_has_bus';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Assigned',
            self::STATUS_INACTIVE => 'UnAssigned',
            // self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Assigned</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">UnAssigned</span>';
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
            'driver_id' => Yii::t('app', 'Driver ID'),
            'bus_id' => Yii::t('app', 'Bus ID'),
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
    public function getBus()
    {
        return $this->hasOne(\app\modules\admin\models\BusDetails::className(), ['id' => 'bus_id']);
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
    public function getDriver()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'driver_id']);
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
     * @return \app\modules\admin\models\DriverHasBusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\DriverHasBusQuery(get_called_class());
    }
public function asJson()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['driver_id'] =  $this->driver_id;

    $data['bus_id'] =  $this->bus_id;

    $data['status'] =  $this->status;

    $data['created_on'] =  $this->created_on;

    $data['updated_on'] =  $this->updated_on;

    $data['create_user_id'] =  $this->create_user_id;

    $data['update_user_id'] =  $this->update_user_id;

    return $data;
}
}
