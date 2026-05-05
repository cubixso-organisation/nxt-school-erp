<?php


namespace app\modules\hostelmanagement\models\base;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "floor".
 *
 * @property integer $id
 * @property integer $hostel_id
 * @property integer $campus_id
 * @property string $name_of_floor
 * @property integer $no_of_rooms
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\hostelmanagement\models\Hostels $hostel
 * @property \app\modules\hostelmanagement\models\Rooms[] $rooms
 */
class Floor extends \yii\db\ActiveRecord
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
            'rooms'
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
    public function rules()
    {
        return [
            [['hostel_id', 'campus_id', 'name_of_floor', 'status'], 'required'],
            [['hostel_id', 'campus_id', 'no_of_rooms', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on', 'no_of_rooms',], 'safe'],
            [['name_of_floor'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floor';
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
            'hostel_id' => Yii::t('app', 'Hostel'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'name_of_floor' => Yii::t('app', 'Name Of Floor'),
            'no_of_rooms' => Yii::t('app', 'No Of Rooms'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
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
    public function getRooms()
    {
        return $this->hasMany(\app\modules\hostelmanagement\models\Rooms::className(), ['floor_id' => 'id']);
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
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }

    public function getFloorData($floor)
    {
        $out = [];
        $campusId = User::getCampusId();
        $chiefWardenCampusId = User::getUserCampusId();

        if (!empty($floor)) {
            // Retrieve data from the Floor table where hostel_id is equal to $floor and (campus_id is equal to $campusId or campus_id is equal to $chiefWardenCampusId)
            $data = Floor::find()
                ->where(['hostel_id' => $floor])
                ->andWhere(['or', ['campus_id' => $campusId], ['campus_id' => $chiefWardenCampusId]])
                ->all();

            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['name_of_floor']];
            }
        }

        return [
            'output' => $out
        ];
    }
    public function getFloor($hostel_id)
    {
        $out = [];
        $campusId = User::getCampusId();
        $chiefWardenCampusId = User::getUserCampusId();

        if (!empty($hostel_id)) {
            // Retrieve data from the Floor table where hostel_id is equal to $floor and (campus_id is equal to $campusId or campus_id is equal to $chiefWardenCampusId)
            $data = Floor::find()
                ->where(['hostel_id' => $hostel_id])
                ->andWhere(['or', ['campus_id' => $campusId], ['campus_id' => $chiefWardenCampusId]])
                ->all();

            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['name_of_floor']];
            }
        }

        return [
            'output' => $out
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\hostelmanagement\models\FloorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\hostelmanagement\models\FloorQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['campus_id'] =  $this->campus_id;

        $data['name_of_floor'] =  $this->name_of_floor;

        $data['no_of_rooms'] =  $this->no_of_rooms;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }


    public function floorListJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['hostel_id'] =  $this->hostel_id;
        $data['hostel_name'] =  $this->hostel->name ?? "";
        $data['hostel_type'] =  $this->hostel->type_id ?? "";

        $data['campus_id'] =  $this->campus_id;

        $data['name_of_floor'] =  $this->name_of_floor;
        $wardenAssigedToFloor = WardenToHostel::find()->where(['floor_id' => $this->id])->one();

        if (!empty($wardenAssigedToFloor)) {
            $warden = User::find()->where(['id' => $wardenAssigedToFloor->warden_id])->andWhere(['user_role' => User::ROLE_WARDEN])->one();
            if (!empty($warden)) {
                $data['warden_name'] =  $warden->first_name;
            } else {
                $data['warden_name'] =  "Not Assigned";
            }
        } else {
            $data['warden_name'] =  "Not Assigned";
        }


        $totalRooms = Rooms::find()->where(['floor_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->count();
        $totalBeds = Rooms::find()->where(['floor_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->sum('no_of_beds');
        $availableBeds = Rooms::find()->where(['floor_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->sum('available_bed');

        $data['dashboard']['no_of_rooms'] =  (int)$totalRooms;
        $data['dashboard']['no_of_beds'] =  (int)$totalBeds;
        $data['dashboard']['available_beds'] = (int)$availableBeds;


        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }

    public function getWardenFloors($user_id)
    {
        $warden_floors = WardenToHostel::find()->where(['warden_id' => $user_id])->all();

        $floor_ids = [];

        foreach ($warden_floors as $warden_floor) {
            $floor_ids[] = $warden_floor->floor_id;
        }

        return $floor_ids;
    }
}
