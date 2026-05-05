<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Roles as BaseRoles;

/**
 * This is the model class for table "roles".
 */
class Roles extends BaseRoles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                // [['name', 'campus_id', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
                // [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
                // [['created_on', 'updated_on'], 'safe'],
                // [['name'], 'string', 'max' => 255]
            ]
        );
    }
}
