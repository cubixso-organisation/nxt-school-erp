<?php

namespace app\modules\staffmanagement\models;

use Yii;
use \app\modules\staffmanagement\models\base\StaffDetails as BaseStaffDetails;

/**
 * This is the model class for table "staff_details".
 */
class StaffDetails extends BaseStaffDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'campus_id', 'designation_id', 'contact_no', 'date_of_birth', 'gender', 'email', 'aadhar_card', 'pan_card', 'status', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'designation_id', 'payroll_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date_of_birth', 'created_on', 'updated_on'], 'safe'],
            [['name', 'contact_no', 'aadhar_card', 'pan_card'], 'string', 'max' => 255],
            [['gender', 'email'], 'string', 'max' => 50]
        ]);
    }
	

}
