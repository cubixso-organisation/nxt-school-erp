<?php


namespace app\modules\hostelmanagement\models\base;

use app\modules\admin\models\base\Campus;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "hostels".
 *
 * @property integer $id
 * @property integer $campus_id

 * @property string $image_file
 * @property string $name
 * @property string $email
 * @property string $name_of_the_hostel
 * @property string $area
 * @property string $pincode
 * @property string $address
 * @property integer $type_id
 * @property double $lat
 * @property double $lng
 * @property string $coordinates
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\hostelmanagement\models\Campus $campus
 */
class Hostels extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    

    const TYPE_BOYS = 1;
    const TYPE_GIRLS = 2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'name', 'email', 'area', 'address', 'type_id'], 'required'],
            [['id', 'campus_id', 'status', 'create_user_id', 'update_user_id', 'type_id', 'warden_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['created_on', 'updated_on', 'warden_id','mess_menu'], 'safe'],
            [['name', 'email', 'name_of_the_hostel', 'area', 'address', 'coordinates'], 'string', 'max' => 255],
            [['pincode'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hostels';
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

    public static function getTypeOptions()
    {
        return [
            self::TYPE_BOYS => 'Boys',

            self::TYPE_GIRLS => 'Girls',

        ];
    }
    public  function getTypeOptionsBadges()
    {

        if ($this->type_id == self::TYPE_BOYS) {
            return '<span class="badge bg-success">Active</span>';
        } elseif ($this->type_id == self::TYPE_GIRLS) {
            return '<span class="badge bg-success">Active</span>';
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
            'campus_id' => Yii::t('app', 'Campus'),
            'image_file' => Yii::t('app', 'Image File'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'name_of_the_hostel' => Yii::t('app', 'Name Of The Hostel'),
            'area' => Yii::t('app', 'Area'),
            'pincode' => Yii::t('app', 'Pincode'),
            'address' => Yii::t('app', 'Address'),
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'coordinates' => Yii::t('app', 'Coordinates'),
            'type_id' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }


    public function getRooms($hostel_id)
    {
        $out = [];
        $data = Rooms::find()
            ->where(['hostel_id' => $hostel_id])
            ->andWhere(['>', 'no_of_beds', 0])
            ->andWhere(['status' => Rooms::STATUS_ACTIVE])
            ->asArray()
            ->all();

        // var_dump($data);exit;
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['name_of_the_room'] . '(' . $dat['available_bed'] . ')'];
        }
        return $output = [
            'output' => $out
        ];
    }
    public static function getTotalHostelRooms($hostel_id)
    {
        $total_rooms = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->andWhere(['hostel_id' => $hostel_id])->count();
        return $total_rooms;
    }

    public static function getTotalWardens($hostel_id)
    {
        $total_wardens = WardenToHostel::find()->where(['status' => Hostels::STATUS_ACTIVE])->andWhere(['hostel_id' => $hostel_id])->count();
        return $total_wardens;
    }
    public static function getTotalStudents($hostel_id)
    {
        $total_students = WardenToHostel::find()->where(['status' => Hostels::STATUS_ACTIVE])->andWhere(['hostel_id' => $hostel_id])->count();
        return $total_students;
    }
    public static function getTotalHostelFloors($hostel_id)
    {
        $total_floors = Floor::find()->where(['status' => Floor::STATUS_ACTIVE])->andWhere(['hostel_id' => $hostel_id])->count();
        return $total_floors;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    public static function getCampusId($user_id)
    {
        $campus = Campus::find()
            ->where(['user_id' => $user_id])
            ->one();

        return $campus->id;
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
     * @return \app\models\HostelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\HostelsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['warden_id'] =  $this->warden_id;

        $data['type_id'] =  $this->type_id;

        $data['image_file'] =  $this->image_file;

        $data['name'] =  $this->name;

        $data['email'] =  $this->email;


        $data['area'] =  $this->area;

        $data['pincode'] =  $this->pincode;

        $data['address'] =  $this->address;

        $data['lat'] =  $this->lat;

        $data['lng'] =  $this->lng;

        $data['coordinates'] =  $this->coordinates;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }



    public function asHostelList()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['warden_id'] =  $this->warden_id;

        $data['type_id'] =  $this->type_id;

        $data['image_file'] =  $this->image_file;

        $data['name'] =  $this->name;

        $data['email'] =  $this->email;

        $floorCount = Floor::find()->where(['hostel_id' => $this->id])->andWhere(['status' => Floor::STATUS_ACTIVE])->count();
        $roomCount = Rooms::find()->where(['hostel_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->count();
        $totalBeds = Rooms::find()->where(['hostel_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->sum('no_of_beds');
        $availableBed = Rooms::find()->where(['hostel_id' => $this->id])->andWhere(['status' => Rooms::STATUS_ACTIVE])->sum('available_bed');
        $data['dashboard']['total_floor'] =  (int)$floorCount ?? 0;
        $data['dashboard']['total_room'] =  (int)$roomCount ?? 0;
        $data['dashboard']['total_beds'] =  (int)$totalBeds ?? 0;
        $data['dashboard']['un_occupied'] =  (int)$availableBed ?? 0;


        $data['area'] =  $this->area;

        $data['pincode'] =  $this->pincode;

        $data['address'] =  $this->address;



        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
