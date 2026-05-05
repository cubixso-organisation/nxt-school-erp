<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "events".
 *
 * @property integer $id
 * @property string $event_name
 * @property string $image
 * @property string $description
 * @property integer $section
 * @property string $venue
 * @property string $start_time
 * @property string $end_time
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class Events extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $section = [];

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const TYPE_EVENT = 1;
    const TYPE_HOLIDAY = 2;
    const IS_GLOBAL = 1;
    const IS_CLASS_WISE = 2;
    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    const BUS_REQUIRED = 1;
    const BUS_NOT_REQUIRED = 2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_name', 'venue', 'start_time', 'end_time', 'type', 'is_global'], 'required'],
            [['create_user_id', 'update_user_id','status'], 'integer'],
            [['section'], 'each', 'rule' => ['integer']],
            [['start_time', 'end_time', 'section', 'created_on', 'updated_on', 'campus_id', 'bus_required', 'status'], 'safe'],
            [['event_name', 'venue'], 'string', 'max' => 199],
            [['image', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
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


    public function getTypeOptions()
    {
        return [
            self::TYPE_EVENT => 'Event',
            self::TYPE_HOLIDAY => 'Holiday',
        ];
    }

    public function getTypeOptionsBadges()
    {
        if ($this->type == self::TYPE_EVENT) {
            return '<span class="badge badge-info">Event</span>';
        } elseif ($this->type == self::TYPE_HOLIDAY) {
            return '<span class="badge badge-warning">Holiday</span>';
        }
    }

    public function getScopeOptions()
    {
        return [
            self::IS_GLOBAL => 'Global (For All The Classed)',
            self::IS_CLASS_WISE => 'Class Wise (For The Selected Classes)',
        ];
    }

    public function getScopeOptionsBadges()
    {
        if ($this->is_global == self::IS_GLOBAL) {
            return '<span class="badge badge-primary">Global</span>';
        } elseif ($this->is_global == self::IS_CLASS_WISE) {
            return '<span class="badge badge-secondary">Class Wise</span>';
        }
    }


    public function getBusOptions()
    {
        return [
            self::BUS_REQUIRED => 'Yes',
            self::BUS_NOT_REQUIRED => 'No',
        ];
    }

    public function getBusOptionsBadges()
    {
        if ($this->bus_required == self::BUS_REQUIRED) {
            return '<span class="badge badge-success">Yes</span>';
        } elseif ($this->bus_required == self::BUS_NOT_REQUIRED) {
            return '<span class="badge badge-danger">NO</span>';
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
            'event_name' => Yii::t('app', 'Event Name'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
            'section' => Yii::t('app', 'Section'),
            'venue' => Yii::t('app', 'Venue'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
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
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\admin\models\EventsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\EventsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['event_name'] =  $this->event_name;

        $data['image'] =  $this->image;

        $data['description'] =  $this->description;

        $data['section'] =  $this->section;

        $data['venue'] =  $this->venue;

        $data['start_time'] =  $this->start_time;

        $data['end_time'] =  $this->end_time;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
