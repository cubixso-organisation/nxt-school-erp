<?php


namespace app\modules\hostelmanagement\models\base;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "warden_attandance".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $hostel_id
 * @property integer $warden_id
 * @property integer $attandance
 * @property string $date
 * @property integer $attandance_by
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\hostelmanagement\models\Campus $campus
 * @property \app\modules\hostelmanagement\models\User $warden
 */
class WardenAttandance extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'warden',
            'hostel',
            'attandanceBy'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    const PRESENT = 1;
    const ABSENT = 2;
    const NOT_MARKED = 3;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'hostel_id', 'warden_id', 'date', 'attandance_by', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'hostel_id', 'warden_id', 'attandance', 'attandance_by', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'warden_attandance';
    }
    public function getAttendanceOptions()
    {
        return [

            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::NOT_MARKED => 'Not Marked',

        ];
    }
    public function getAttendanceOptionsBadges()
    {

        if ($this->attandance == self::PRESENT) {
            return '<span class="badge badge-success">Present</span>';
        } elseif ($this->attandance == self::NOT_MARKED) {
            return '<span class="badge badge-default">Not Marked</span>';
        } elseif ($this->attandance == self::ABSENT) {
            return '<span class="badge badge-danger">Absent</span>';
        }
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'hostel_id' => Yii::t('app', 'Hostel ID'),
            'warden_id' => Yii::t('app', 'Warden ID'),
            'attandance' => Yii::t('app', 'Attandance'),
            'date' => Yii::t('app', 'Date'),
            'attandance_by' => Yii::t('app', 'Attandance By'),
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
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarden()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'warden_id']);
    }
    public function getHostel()
    {
        return $this->hasOne(\app\modules\hostelmanagement\models\Hostels::className(), ['id' => 'hostel_id']);
    }
    public function getAttandanceBy()
    {
        return $this->hasOne(User::className(), ['id' => 'attandance_by']);
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
     * @return \app\modules\hostelmanagement\models\WardenAttandanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\hostelmanagement\models\WardenAttandanceQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['hostel_id'] =  $this->hostel_id;

        $data['warden_id'] =  $this->warden_id;

        $data['attandance'] =  $this->attandance;

        $data['date'] =  $this->date;

        $data['attandance_by'] =  $this->attandance_by;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
