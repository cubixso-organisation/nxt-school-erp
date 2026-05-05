<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "razorpay_linked_account".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $email
 * @property string $phone
 * @property string $reference_id
 * @property string $legal_business_name
 * @property string $business_type
 * @property string $contact_name
 * @property string $street1
 * @property string $street2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $country
 * @property string $pan
 * @property string $gst
 * @property string $razorpay_acc_id
 * @property string $account_status
 * @property string $account_number
 * @property string $ifsc_code
 * @property string $beneficiary_name
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 */
class RazorpayLinkedAccount extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus'
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
            [['campus_id', 'email', 'phone', 'reference_id', 'legal_business_name', 'business_type', 'contact_name', 'street1', 'street2', 'city', 'state', 'postal_code', 'country', 'pan', 'gst', 'razorpay_acc_id', 'account_status', 'account_number', 'ifsc_code', 'beneficiary_name', 'status',], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'safe'],
            [['email', 'phone', 'reference_id', 'legal_business_name', 'business_type', 'contact_name', 'street1', 'street2', 'city', 'state', 'postal_code', 'country', 'pan', 'gst', 'razorpay_acc_id', 'account_status', 'account_number', 'ifsc_code', 'beneficiary_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'razorpay_linked_account';
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
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'reference_id' => Yii::t('app', 'Reference ID'),
            'legal_business_name' => Yii::t('app', 'Legal Business Name'),
            'business_type' => Yii::t('app', 'Business Type'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'street1' => Yii::t('app', 'Street1'),
            'street2' => Yii::t('app', 'Street2'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'country' => Yii::t('app', 'Country'),
            'pan' => Yii::t('app', 'Pan'),
            'gst' => Yii::t('app', 'Gst'),
            'razorpay_acc_id' => Yii::t('app', 'Razorpay Acc ID'),
            'account_status' => Yii::t('app', 'Account Status'),
            'account_number' => Yii::t('app', 'Account Number'),
            'ifsc_code' => Yii::t('app', 'Ifsc Code'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
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
     * @return \app\modules\admin\models\RazorpayLinkedAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\RazorpayLinkedAccountQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['email'] =  $this->email;

        $data['phone'] =  $this->phone;

        $data['reference_id'] =  $this->reference_id;

        $data['legal_business_name'] =  $this->legal_business_name;

        $data['business_type'] =  $this->business_type;

        $data['contact_name'] =  $this->contact_name;

        $data['street1'] =  $this->street1;

        $data['street2'] =  $this->street2;

        $data['city'] =  $this->city;

        $data['state'] =  $this->state;

        $data['postal_code'] =  $this->postal_code;

        $data['country'] =  $this->country;

        $data['pan'] =  $this->pan;

        $data['gst'] =  $this->gst;

        $data['razorpay_acc_id'] =  $this->razorpay_acc_id;

        $data['account_status'] =  $this->account_status;

        $data['account_number'] =  $this->account_number;

        $data['ifsc_code'] =  $this->ifsc_code;

        $data['beneficiary_name'] =  $this->beneficiary_name;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
