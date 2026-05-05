<?php


namespace app\modules\inventory\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "item_supplier_list".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $contact_person_name
 * @property string $contact_person_phone
 * @property string $contact_person_email
 * @property string $description
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 */
class ItemSupplierList extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    // public function relationNames()
    // {
    //     return [
    //         ''
    //     ];
    // }

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
            [['name', 'phone', 'email','contact_person_name', 'contact_person_email'], 'required'],
            [['address', 'description'], 'string'],
            [['created_on', 'updated_on','campus_id'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['name', 'email', 'contact_person_name', 'contact_person_email'], 'string', 'max' => 255],
            [['phone', 'contact_person_phone'], 'integer', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_supplier_list';
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
            'name' => Yii::t('app', 'Company/Organization Name'),
            'phone' => Yii::t('app', 'Company/Organization Phone'),
            'email' => Yii::t('app', 'Company/Organization Email'),
            'address' => Yii::t('app', 'Company/Organization Address'),
            'contact_person_name' => Yii::t('app', 'Contact Person Name'),
            'contact_person_phone' => Yii::t('app', 'Contact Person Phone'),
            'contact_person_email' => Yii::t('app', 'Contact Person Email'),
            'description' => Yii::t('app', 'Description'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
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
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\inventory\models\ItemSupplierListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\inventory\models\ItemSupplierListQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['name'] =  $this->name;
        
                $data['phone'] =  $this->phone;
        
                $data['email'] =  $this->email;
        
                $data['address'] =  $this->address;
        
                $data['contact_person_name'] =  $this->contact_person_name;
        
                $data['contact_person_phone'] =  $this->contact_person_phone;
        
                $data['contact_person_email'] =  $this->contact_person_email;
        
                $data['description'] =  $this->description;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
                $data['created_user_id'] =  $this->created_user_id;
        
                $data['updated_user_id'] =  $this->updated_user_id;
        
            return $data;
}


}


