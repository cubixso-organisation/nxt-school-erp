<?php


namespace app\modules\staffmanagement\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "salary_group_to_staff".
 *
 * @property integer $id
 * @property integer $staff_id
 * @property integer $staff_user_id
 * @property integer $salary_group_id
 * @property integer $campus_id
 * @property integer $status
 * @property string $updated_on
 * @property string $created_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\staffmanagement\models\StaffDetails $staff
 * @property \app\modules\staffmanagement\models\User $staffUser
 * @property \app\modules\staffmanagement\models\Campus $campus
 * @property \app\modules\staffmanagement\models\SalaryGroups $salaryGroup
 */
class SalaryGroupToStaff extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'staff',
            'staffUser',
            'campus',
            'salaryGroup'
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
            [['staff_id', 'staff_user_id', 'salary_group_id', 'campus_id', 'status', 'updated_on', 'created_on', 'create_user_id', 'update_user_id'], 'required'],
            [['staff_id', 'staff_user_id', 'salary_group_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['updated_on', 'created_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salary_group_to_staff';
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
            'staff_id' => Yii::t('app', 'Staff ID'),
            'staff_user_id' => Yii::t('app', 'Staff User ID'),
            'salary_group_id' => Yii::t('app', 'Salary Group ID'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'status' => Yii::t('app', 'Status'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_on' => Yii::t('app', 'Created On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\StaffDetails::className(), ['id' => 'staff_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'staff_user_id']);
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
    public function getSalaryGroup()
    {
        return $this->hasOne(\app\modules\staffmanagement\models\SalaryGroups::className(), ['id' => 'salary_group_id']);
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
     * @return \app\modules\staffmanagement\models\SalaryGroupToStaffQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\staffmanagement\models\SalaryGroupToStaffQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['staff_id'] =  $this->staff_id;
        
                $data['staff_user_id'] =  $this->staff_user_id;
        
                $data['salary_group_id'] =  $this->salary_group_id;
        
                $data['campus_id'] =  $this->campus_id;
        
                $data['status'] =  $this->status;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['created_on'] =  $this->created_on;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
            return $data;
}


}


