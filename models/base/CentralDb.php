<?php


namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "central_db".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $school_name
 * @property string $address
 * @property string $domain
 * @property string $db_username
 * @property string $db_password
 * @property string $db_name
 * @property string $sub_domain
 * @property string $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 */
class CentralDb extends \yii\db\ActiveRecord
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
            [['db_username', 'db_password', 'db_name', 'sub_domain'], 'safe'],
            [['email', 'phone', 'domain'], 'required'],
            [['email', 'phone', 'domain'], 'unique', 'when' => function ($model, $attribute) {
                return $model->isAttributeChanged($attribute);
            }],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name', 'email', 'school_name', 'address', 'domain', 'db_username', 'db_password', 'db_name', 'sub_domain'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'central_db';
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
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'school_name' => Yii::t('app', 'School Name'),
            'address' => Yii::t('app', 'Address'),
            'domain' => Yii::t('app', 'Domain'),
            'db_username' => Yii::t('app', 'Db Username'),
            'db_password' => Yii::t('app', 'Db Password'),
            'db_name' => Yii::t('app', 'Db Name'),
            'sub_domain' => Yii::t('app', 'Sub Domain'),
            'status' => Yii::t('app', 'Status'),
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
                'updatedByAttribute' => 'created_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\models\CentralDbQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\CentralDbQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['name'] =  $this->name;

        $data['email'] =  $this->email;

        $data['phone'] =  $this->phone;

        $data['school_name'] =  $this->school_name;

        $data['address'] =  $this->address;

        $data['domain'] =  $this->domain;

        $data['db_username'] =  $this->db_username;

        $data['db_password'] =  $this->db_password;

        $data['db_name'] =  $this->db_name;

        $data['sub_domain'] =  $this->sub_domain;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
