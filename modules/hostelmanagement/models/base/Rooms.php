<?php


namespace app\modules\hostelmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "rooms".
 *
 * @property integer $id
 * @property integer $hostel_id
 * @property string $name_of_the_room
 * @property integer $no_of_beds
 * @property integer $type
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\hostelmanagement\models\Hostels $hostel
 * @property \app\modules\hostelmanagement\models\User $createUser
 * @property \app\modules\hostelmanagement\models\User $updateUser
 */
class Rooms extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'hostel',
            'createUser',
            'updateUser',
            'floor'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const TYPE_AC = 1;
    const TYPE_NON_AC = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hostel_id', 'name_of_the_room', 'no_of_beds', 'type'], 'required'],
            [['hostel_id',  'floor_id', 'no_of_beds', 'type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on', 'available_bed'], 'safe'],
            [['name_of_the_room'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rooms';
    }

    public static function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public  function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="btn btn-inverse-success btn-sm btn-rounded btn-icon">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="btn btn-inverse-warning btn-sm btn-rounded btn-icon">In Active</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="btn btn-inverse-danger btn-sm btn-rounded btn-icon">Deleted</span>';
        }
    }


    public static function getRoomTypeOptions()
    {
        return [
            self::TYPE_AC => 'AC',

            self::TYPE_NON_AC => 'NON-AC',


        ];
    }
    public  function getRoomTypeOptionsBadges()
    {

        if ($this->type == self::TYPE_AC) {
            return '<span class="btn btn-inverse-success btn-sm btn-rounded btn-icon">AC</span>';
        } elseif ($this->type == self::TYPE_NON_AC) {
            return '<span class="btn btn-inverse-warning btn-sm btn-rounded btn-icon">NON-AC</span>';
        }
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
            'hostel_id' => Yii::t('app', 'Hostel'),
            'floor_id' => Yii::t('app', 'Floor'),
            'name_of_the_room' => Yii::t('app', 'Name Of The Room'),
            'no_of_beds' => Yii::t('app', 'No Of Beds'),
            'type' => Yii::t('app', 'Type'),
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
    public function getFloor()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Floor::className(), ['id' => 'floor_id']);
    }
    public function getHostel()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Hostels::className(), ['id' => 'hostel_id']);
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
     * @return \app\models\RoomsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\RoomsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['floor_id'] =  $this->floor_id;

        $data['name_of_the_room'] =  $this->name_of_the_room;

        $data['no_of_beds'] =  $this->no_of_beds;

        $data['available_bed'] =  $this->available_bed;

        $data['type'] =  $this->type;

        if ($this->type == Rooms::TYPE_AC) {
            $data['type'] = 'AC';
        } else {
            $data['type'] = 'N-AC';
        }

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }


    public function roomListJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['floor_id'] =  $this->floor_id;
        $data['floor_name'] =  $this->floor->name ?? "";

        $data['name_of_the_room'] =  $this->name_of_the_room;

        $data['no_of_beds'] =  $this->no_of_beds;

        $data['available_bed'] =  $this->available_bed;

        $data['type'] =  $this->type;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
