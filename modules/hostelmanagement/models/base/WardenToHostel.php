<?php


namespace app\modules\hostelmanagement\models\base;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "warden_to_hostel".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $hostel_id
 * @property integer $warden_id
 * @property integer $floor_id
 * @property string $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\hostelmanagement\models\Hostels $hostel
 * @property \app\modules\hostelmanagement\models\User $warden
 * @property \app\modules\hostelmanagement\models\Floor $floor
 */
class WardenToHostel extends \yii\db\ActiveRecord
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
            'warden',
            'floor'
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
            [['campus_id', 'hostel_id', 'warden_id', 'floor_id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'warden_to_hostel';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'In Active',
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
            'campus_id' => Yii::t('app', 'Campus'),
            'hostel_id' => Yii::t('app', 'Hostel'),
            'warden_id' => Yii::t('app', 'Warden'),
            'floor_id' => Yii::t('app', 'Floor'),
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
    public function getWarden()
    {
        return $this->hasOne(User::className(), ['id' => 'warden_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloor()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Floor::className(), ['id' => 'floor_id']);
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



    /**
     * @inheritdoc
     * @return \app\modules\hostelmanagement\models\WardenToHostelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\hostelmanagement\models\WardenToHostelQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['warden_id'] =  $this->warden_id;

        $data['floor_id'] =  $this->floor_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }

    public function asJsonForFloor()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['hostel_name'] = $this->hostel->name;

        $data['warden_id'] =  $this->warden_id;

        $warden_name = User::find()->where(['id' => $this->warden_id])->one();

        $data['warden_name'] = !empty($warden_name->first_name) ? $warden_name->first_name . " " . $warden_name->last_name : 0;

        $data['floor_id'] =  $this->floor_id;

        $data['floor_name'] = $this->floor->name_of_floor;

        $data['rooms'] = Rooms::find()->where(['floor_id' => $this->floor_id])->count();

        $no_of_beds = Rooms::find()->where(['floor_id' => $this->floor_id])->sum('no_of_beds');
        $data['no_of_beds'] = !empty($no_of_beds) ? $no_of_beds : 0;
        $available_bed = Rooms::find()->where(['floor_id' => $this->floor_id])->sum('available_bed');
        $data['available_bed'] = !empty($available_bed) ? $available_bed : 0;
        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
