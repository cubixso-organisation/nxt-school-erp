<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\LoginLogs as BaseLoginLogs;

/**
 * This is the model class for table "login_logs".
 */
class LoginLogs extends BaseLoginLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'username', 'ip', 'country', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['campus_id', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'integer'],
            [['username', 'ip', 'country'], 'string', 'max' => 255]
        ]);
    }
	

}
