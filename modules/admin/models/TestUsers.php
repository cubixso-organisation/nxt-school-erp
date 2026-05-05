<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TestUsers as BaseTestUsers;

/**
 * This is the model class for table "test_users".
 */
class TestUsers extends BaseTestUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['email', 'first_name', 'last_name'], 'required'],
            [['date_of_birth', 'created_on', 'updated_on'], 'safe'],
            [['description', 'address'], 'string'],
            [['status', 'create_user_id', 'update_user_id'], 'integer'],
            [['username', 'last_name', 'contact_no', 'alternative_contact', 'profile_image'], 'string', 'max' => 255],
            [['email', 'first_name'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 10]
        ]);
    }
	
}
