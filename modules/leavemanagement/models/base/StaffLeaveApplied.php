<?php


namespace app\modules\leavemanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "staff_leave_applied".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $user_id
 * @property integer $leave_type_id
 * @property integer $no_of_days
 * @property string $leave_reason
 * @property string $from_date
 * @property string $to_date
 * @property string $document_uploaded
 * @property string $user_role
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\leavemanagement\models\StaffLeaveTypes $leaveType
 * @property \app\modules\leavemanagement\models\Campus $campus
 * @property \app\modules\leavemanagement\models\User $user
 */
class StaffLeaveApplied extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'leaveType',
            'campus',
            'user'
        ];
    }

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_CANCELLED_BY_APPLICANT = 3;
    const STATUS_CANCELLED_BY_ADMIN = 4;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'user_id', 'leave_type_id', 'leave_reason', 'from_date', 'to_date',  'user_role', 'status', ], 'required'],
            [['campus_id', 'user_id', 'leave_type_id', 'no_of_days', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['leave_reason'], 'string'],
            [['from_date', 'to_date', 'created_on', 'updated_on','campus_id',], 'safe'],
            [['document_uploaded', 'user_role'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_leave_applied';
    }

    public static function getStateOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',

            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED_BY_APPLICANT => 'Cancelled By User',

        ];
    }
    public  function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_PENDING) {
            return '<span class="btn btn-warning btn-sm btn-rounded btn-icon">Pending</span>';
        } elseif ($this->status == self::STATUS_APPROVED) {
            return '<span class="btn btn-success btn-sm btn-rounded btn-icon">Approved</span>';
        } elseif ($this->status == self::STATUS_REJECTED) {
            return '<span class="btn btn-danger btn-sm btn-rounded btn-icon">Rejected</span>';
        } elseif ($this->status == self::STATUS_CANCELLED_BY_APPLICANT) {
            return '<span class="btn btn-danger btn-sm btn-rounded btn-icon">Cancelled By User</span>';
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
            'campus_id' => 'Campus ID',
            'user_id' => 'Leave Applicant Name',
            'leave_type_id' => 'Leave Type',
            'no_of_days' => 'No Of Days',
            'leave_reason' => 'Leave Reason',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'document_uploaded' => 'Document Uploaded',
            'user_role' => 'Applicant Role',
            'status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(\app\modules\leavemanagement\models\StaffLeaveTypes::className(), ['id' => 'leave_type_id']);
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
    public function getUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'user_id']);
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
            // 'uuid' => [
            //     'class' => UUIDBehavior::className(),
            //     'column' => 'id',
            // ],
        ];
    }




    /**
     * @inheritdoc
     * @return \app\modules\leavemanagement\models\StaffLeaveAppliedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\leavemanagement\models\StaffLeaveAppliedQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['user_id'] =  $this->user_id;

        $data['leave_type_id'] =  $this->leave_type_id;
        $data['leave_type_name'] =  $this->leaveType->title;

        $data['no_of_days'] =  $this->no_of_days;

        $data['leave_reason'] =  $this->leave_reason;

        $data['from_date'] =  $this->from_date;

        $data['to_date'] =  $this->to_date;

        $data['document_uploaded'] =  $this->document_uploaded;

        $data['user_role'] =  $this->user_role;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
