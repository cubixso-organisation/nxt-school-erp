<?php


namespace app\modules\staffmanagement\models\base;

use app\modules\staffmanagement\models\StaffAttendenceQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "staff_attendence".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $staff_id
 * @property string $date
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \app\modules\staffmanagement\models\Campus $campus
 * @property \app\modules\staffmanagement\models\StaffDetails $staff
 */
class StaffAttendence extends \yii\db\ActiveRecord
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
            'staff',
        ];
    }

    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 2;
    const STATUS_NOT_MARKED = 3;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'staff_id', 'date', 'status', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'staff_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'updated_on', 'attendance_count_perday'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_attendence';
    }

    public static function getStateOptions()
    {
        return [
            self::STATUS_PRESENT => 'Present',

            self::STATUS_ABSENT => 'Absent',
            self::STATUS_NOT_MARKED => 'Not Marked',

        ];
    }
    public  function getStateOptionsBadges()
    {

        if ($this->attendence == self::STATUS_PRESENT) {
            return 'Present';
        } elseif ($this->attendence == self::STATUS_ABSENT) {
            return 'Absent';
        } elseif ($this->attendence == self::STATUS_NOT_MARKED) {
            return 'Not Marked';
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
            'id' => 'ID',
            'campus_id' => 'Campus ID',
            'staff_id' => 'Staff ID',
            'date' => 'Date',
            'status' => 'Status',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
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
    public function getStaff()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\StaffDetails::className(), ['id' => 'staff_id']);
    }

    // public function getDesignation()
    // {
    //     return $this->hasOne(\app\modules\staffmanagement\models\StaffDesignations::className(), ['id' => 'designation_id']);
    // }

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
     * @return \app\models\StaffAttendenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StaffAttendenceQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['staff_id'] =  $this->staff_id;

        $data['date'] =  $this->date;

        $data['status'] =  $this->status;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        return $data;
    }
}
