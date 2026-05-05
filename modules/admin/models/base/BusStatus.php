<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "bus_status".
 *
 * @property integer $id
 * @property integer $bus_route_id
 * @property string $bus_reached_time
 * @property string $bus_left_time
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\BusRoute $busRoute
 */
class BusStatus extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'createUser',
            'updateUser',
            'busRoute'
        ];
    }

    const bus_reached = 1;
    const bus_left = 2;
    public const bus_skip  = 3;
    public const bus_completed =4;
    public const bus_next_stop=5;



    const status_direction_school=1;
    const status_direction_from_school=2;

 

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_route_id', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['bus_route_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['bus_reached_time', 'bus_left_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_status';
    }

    
   


    public function getStateOptions()
    {
        return [

            self::bus_reached => 'Bus Reached',
            self::bus_left => 'Bus Left',
         

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::bus_reached) {
            return '<span class="badge badge-success">Bus Reached</span>';
        } elseif ($this->status == self::bus_reached) {
            return '<span class="badge badge-default">Bus Left</span>';
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
            'bus_reached_time' => Yii::t('app', 'Bus Reached Time'),
            'bus_left_time' => Yii::t('app', 'Bus Left Time'),
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
    public function getBusRoute()
    {
        return $this->hasOne(\app\modules\admin\models\BusRoute::className(), ['id' => 'bus_route_id']);
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
     * @return \app\modules\admin\models\BusStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\BusStatusQuery(get_called_class());
    }


 
public function asJson(){
    $data = [] ; 
               $data['bus_status_id'] =  $this->id;        
                $data['bus_reached_time'] =  $this->bus_reached_time;
        
                $data['bus_left_time'] =  $this->bus_left_time;
                
                $data['unique_key'] =  $this->unique_key;

                $data['status_direction'] =  $this->status_direction;

        
                 $data['created_on'] =  $this->created_on;

                 $data['updated_on'] =  $this->created_on;
                
                 $data['status'] =  $this->status;

        
            return $data;
}


}


