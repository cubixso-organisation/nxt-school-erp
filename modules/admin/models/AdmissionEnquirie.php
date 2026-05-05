<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\AdmissionEnquirie as BaseAdmissionEnquirie;

/**
 * This is the model class for table "admission_enquirie".
 */
class AdmissionEnquirie extends BaseAdmissionEnquirie
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'student_name', 'parent_name', 'contact_no', 'next_class', 'dob', 'status','email'], 'required'],
            [['campus_id', 'contact_no', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['dob', 'created_on', 'updated_on'], 'safe'],
            [['address','message'], 'string'],
            [['student_name', 'next_class', 'previous_class','email'], 'string', 'max' => 199],
            [['parent_name'], 'string', 'max' => 250]
        ]);
    }
	

}
