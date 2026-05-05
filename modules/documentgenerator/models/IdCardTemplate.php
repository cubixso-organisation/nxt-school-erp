<?php

namespace app\modules\documentgenerator\models;

use Yii;
use \app\modules\documentgenerator\models\base\IdCardTemplate as BaseIdCardTemplate;

/**
 * This is the model class for table "id_card_template".
 */
class IdCardTemplate extends BaseIdCardTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['name', 'campus_id', 'school_logo', 'signature', 'front_background_image', 'back_background_image', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['name', 'school_logo', 'signature', 'front_background_image', 'back_background_image'], 'string'],
            // [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
