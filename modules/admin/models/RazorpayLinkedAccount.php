<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\RazorpayLinkedAccount as BaseRazorpayLinkedAccount;

/**
 * This is the model class for table "razorpay_linked_account".
 */
class RazorpayLinkedAccount extends BaseRazorpayLinkedAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'email', 'phone', 'reference_id', 'legal_business_name', 'business_type', 'contact_name', 'street1', 'street2', 'city', 'state', 'postal_code', 'country', 'pan', 'gst', 'razorpay_acc_id', 'account_status', 'account_number', 'ifsc_code', 'beneficiary_name', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['email', 'phone', 'reference_id', 'legal_business_name', 'business_type', 'contact_name', 'street1', 'street2', 'city', 'state', 'postal_code', 'country', 'pan', 'gst', 'razorpay_acc_id', 'account_status', 'account_number', 'ifsc_code', 'beneficiary_name'], 'string', 'max' => 255]
        ]);
    }
	

}
