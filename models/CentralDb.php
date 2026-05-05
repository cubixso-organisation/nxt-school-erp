<?php

namespace app\models;

use Yii;
use \app\models\base\CentralDb as BaseCentralDb;

/**
 * This is the model class for table "central_db".
 */
class CentralDb extends BaseCentralDb
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['id', 'db_username', 'db_password', 'db_name', 'sub_domain'], 'required'],
            // [['id', 'created_user_id', 'updated_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe'],
            // [['name', 'email', 'school_name', 'address', 'domain', 'db_username', 'db_password', 'db_name', 'sub_domain'], 'string', 'max' => 255],
            // [['phone'], 'string', 'max' => 15],
            // [['status'], 'string', 'max' => 50]
        ]);
    }
	

}
