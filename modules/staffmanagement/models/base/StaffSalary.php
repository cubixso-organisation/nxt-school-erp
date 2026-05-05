<?php


namespace app\modules\staffmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "staff_salary".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $staff_id
 * @property double $ctc
 * @property integer $basic_salary_type
 * @property double $basic_salary_value
 * @property string $earnings
 * @property double $ctc_monthly
 * @property double $ctc_yearly
 * @property double $total_deduction_monthly
 * @property double $total_deduction_yearly
 * @property integer $salary_group_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\staffmanagement\models\Campus $campus
 * @property \app\modules\staffmanagement\models\StaffDetails $staff
 */
class StaffSalary extends \yii\db\ActiveRecord
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
            'staff'
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
            [['campus_id', 'staff_id', 'ctc', 'basic_salary_type', 'basic_salary_value', 'earnings', 'ctc_monthly', 'ctc_yearly', 'total_deduction_monthly', 'total_deduction_yearly', 'salary_group_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'staff_id', 'basic_salary_type', 'salary_group_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['ctc', 'basic_salary_value', 'ctc_monthly', 'ctc_yearly', 'total_deduction_monthly', 'total_deduction_yearly'], 'number'],
            [['earnings'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_salary';
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
        }elseif ($this->status == self::STATUS_DELETE) {
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
            'staff_id' => Yii::t('app', 'Staff'),
            'ctc' => Yii::t('app', 'Ctc'),
            'basic_salary_type' => Yii::t('app', 'Basic Salary Type'),
            'basic_salary_value' => Yii::t('app', 'Basic Salary Value'),
            'earnings' => Yii::t('app', 'Earnings'),
            'ctc_monthly' => Yii::t('app', 'Ctc Monthly'),
            'ctc_yearly' => Yii::t('app', 'Ctc Yearly'),
            'total_deduction_monthly' => Yii::t('app', 'Total Deduction Monthly'),
            'total_deduction_yearly' => Yii::t('app', 'Total Deduction Yearly'),
            'salary_group_id' => Yii::t('app', 'Salary Group'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create Uder'),
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
     * @return \app\modules\staffmanagement\models\StaffSalaryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\staffmanagement\models\StaffSalaryQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['staff_id'] =  $this->staff_id;
        
                $data['ctc'] =  $this->ctc;
        
                $data['basic_salary_type'] =  $this->basic_salary_type;
        
                $data['basic_salary_value'] =  $this->basic_salary_value;
        
                $data['earnings'] =  $this->earnings;
        
                $data['ctc_monthly'] =  $this->ctc_monthly;
        
                $data['ctc_yearly'] =  $this->ctc_yearly;
        
                $data['total_deduction_monthly'] =  $this->total_deduction_monthly;
        
                $data['total_deduction_yearly'] =  $this->total_deduction_yearly;
        
                $data['salary_group_id'] =  $this->salary_group_id;
        
                $data['status'] =  $this->status;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


