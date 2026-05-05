<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Permissions as BasePermissions;

/**
 * This is the model class for table "permissions".
 */
class Permissions extends BasePermissions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['name', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['create_user_id', 'update_user_id'], 'integer'],
            // [['name'], 'string', 'max' => 255]
        ]);
    }
	

}
