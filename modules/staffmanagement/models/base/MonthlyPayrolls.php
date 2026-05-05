<?php


namespace app\modules\staffmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "monthly_payrolls".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $staff_id
 * @property integer $user_id
 * @property double $yearly_ctc
 * @property double $monthly_ctc
 * @property string $salary_components
 * @property double $total_monthly_pay
 * @property string $date
 * @property string $month
 * @property integer $salary_group_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\staffmanagement\models\Campus $campus
 * @property \app\modules\staffmanagement\models\StaffDetails $staff
 * @property \app\modules\staffmanagement\models\User $user
 */
class MonthlyPayrolls extends \yii\db\ActiveRecord
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
            'user',
            'group',
            'staffSalary'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


    const JANUARY = '01';
    const FEBRUARY = '02';
    const MARCH = '03';
    const APRIL = '04';
    const MAY = '05';
    const JUNE = '06';
    const JULY = '07';
    const AUGUST = '08';
    const SEPTEMBER = '09';
    const OCTOBER = '10';
    const NOVEMBER = '11';
    const DECEMBER = '12';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'staff_id', 'user_id', 'yearly_ctc', 'monthly_ctc', 'salary_components', 'total_monthly_pay', 'date', 'month', 'salary_group_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'staff_id', 'user_id', 'salary_group_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['yearly_ctc', 'monthly_ctc', 'total_monthly_pay'], 'number'],
            [['salary_components'], 'string'],
            [['date', 'created_on', 'updated_on'], 'safe'],
            [['month'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monthly_payrolls';
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
    public function getMonthOptions()
    {
        return [
            self::JANUARY => 'January',
            self::FEBRUARY => 'February',
            self::MARCH => 'March',
            self::APRIL => 'April',
            self::MAY => 'May',
            self::JUNE => 'June',
            self::JULY => 'July',
            self::AUGUST => 'August',
            self::SEPTEMBER => 'September',
            self::OCTOBER => 'October',
            self::NOVEMBER => 'November',
            self::DECEMBER => 'December',
        ];
    }

    public  function getMonthOptionsBadges($month)
    {
        switch ($month) {
            case self::JANUARY:
            case self::FEBRUARY:
            case self::MARCH:
            case self::APRIL:
            case self::MAY:
            case self::JUNE:
            case self::JULY:
            case self::AUGUST:
            case self::SEPTEMBER:
            case self::OCTOBER:
            case self::NOVEMBER:
            case self::DECEMBER:
                return '<span class="badge badge-primary">' . $this->getMonthOptions()[$month] . '</span>';
            default:
                return '<span class="badge badge-secondary">Invalid Month</span>';
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
            'campus_id' => Yii::t('app', 'Campus Name'),
            'staff_id' => Yii::t('app', 'Staff '),
            'user_id' => Yii::t('app', 'User'),
            'yearly_ctc' => Yii::t('app', 'Yearly Ctc'),
            'monthly_ctc' => Yii::t('app', 'Monthly Ctc'),
            'salary_components' => Yii::t('app', 'Salary Components'),
            'total_monthly_pay' => Yii::t('app', 'Total Monthly Pay'),
            'date' => Yii::t('app', 'Date'),
            'month' => Yii::t('app', 'Month'),
            'salary_group_id' => Yii::t('app', 'Salary Group'),
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
    public function getStaff()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\StaffDetails::className(), ['id' => 'staff_id']);
    }

    public function getStaffSalary()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\StaffSalary::className(), ['staff_id' => 'staff_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\SalaryGroups::className(), ['id' => 'salary_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
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
     * @return \app\modules\staffmanagement\models\MonthlyPayrollsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\staffmanagement\models\MonthlyPayrollsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['staff_id'] =  $this->staff_id;

        $data['user_id'] =  $this->user_id;

        $data['yearly_ctc'] =  $this->yearly_ctc;

        $data['monthly_ctc'] =  $this->monthly_ctc;

        $data['salary_components'] =  $this->salary_components;

        $data['total_monthly_pay'] =  $this->total_monthly_pay;

        $data['date'] =  $this->date;

        $data['month'] =  $this->month;

        $data['salary_group_id'] =  $this->salary_group_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
