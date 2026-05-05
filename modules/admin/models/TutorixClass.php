<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\TutorixClass as BaseTutorixClass;

/**
 * This is the model class for table "tutorix_class".
 */
class TutorixClass extends BaseTutorixClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['class_id', 'name','price', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['class_id', 'status','price', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ]);
    }
	

}
