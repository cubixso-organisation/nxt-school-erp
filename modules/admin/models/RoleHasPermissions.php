<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\RoleHasPermissions as BaseRoleHasPermissions;

/**
 * This is the model class for table "role_has_permissions".
 */
class RoleHasPermissions extends BaseRoleHasPermissions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['role_id', 'permission_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['role_id', 'permission_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
