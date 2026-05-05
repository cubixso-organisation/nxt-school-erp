<?php


namespace app\modules\staffmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "salary_components".
 *
 * @property integer $id
 * @property string $name
 * @property integer $component_type
 * @property integer $value_type
 * @property double $component_value_monthly
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\staffmanagement\models\SalaryGroupComponents[] $salaryGroupComponents
 */
class SalaryComponents extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'salaryGroupComponents'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const COMPONENT_TYPE_EARNING = 1;
    const COMPONENT_TYPE_DEDUCTION = 2;

    const VALUE_TYPE_FIXED = 1;
    const VALUE_TYPE_CTC_PERCENTAGE = 2;
    const VALUE_TYPE_BASIC_PERCENTAGE = 3;
    const VALUE_TYPE_CTC_VARIABLE = 4;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'component_type', 'value_type', 'component_value_monthly', 'status',], 'required'],
            [['component_type', 'value_type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['component_value_monthly'], 'number'],
            [['created_on', 'updated_on', 'create_user_id', 'update_user_id','campus_id'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salary_components';
    }


    public function getComponentTypeOptions()
    {
        return [

            self::COMPONENT_TYPE_EARNING => 'Earning',
            self::COMPONENT_TYPE_DEDUCTION => 'Deduction',

        ];
    }
    public function getComponentTypeOptionsBadges()
    {

        if ($this->component_type == self::COMPONENT_TYPE_EARNING) {
            return '<span class="badge badge-success">Earning</span>';
        } elseif ($this->component_type == self::COMPONENT_TYPE_DEDUCTION) {
            return '<span class="badge badge-danger">Deduction</span>';
        }
    }



    public function getValueTypeOptions()
    {
        return [

            // self::VALUE_TYPE_FIXED => 'Fixed',
            self::VALUE_TYPE_CTC_PERCENTAGE => 'CTC Percentage',
            self::VALUE_TYPE_BASIC_PERCENTAGE => 'Basic Percentage',
            // self::VALUE_TYPE_CTC_VARIABLE => 'Variable',

        ];
    }
    public function getValueTypeOptionsBadges()
    {

        if ($this->value_type == self::VALUE_TYPE_FIXED) {
            return '<span class="badge badge-success">Fixed</span>';
        } elseif ($this->value_type == self::VALUE_TYPE_CTC_PERCENTAGE) {
            return '<span class="badge badge-default">CTC Percentage</span>';
        } elseif ($this->value_type == self::VALUE_TYPE_BASIC_PERCENTAGE) {
            return '<span class="badge badge-default">Basic Percentage</span>';
        } elseif ($this->value_type == self::VALUE_TYPE_CTC_VARIABLE) {
            return '<span class="badge badge-default">Variable</span>';
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
            'name' => Yii::t('app', 'Name'),
            'component_type' => Yii::t('app', 'Component Type %'),
            'value_type' => Yii::t('app', 'Value Type %'),
            'component_value_monthly' => Yii::t('app', 'Component Value Monthly %'),
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
    public function getSalaryGroupComponents()
    {
        return $this->hasMany(\app\modules\staffmanagement\models\SalaryGroupComponents::className(), ['component_id' => 'id']);
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
     * @return \app\modules\staffmanagement\models\SalaryComponentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\staffmanagement\models\SalaryComponentsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['name'] =  $this->name;

        $data['component_type'] =  $this->component_type;

        $data['value_type'] =  $this->value_type;

        $data['component_value_monthly'] =  $this->component_value_monthly;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
