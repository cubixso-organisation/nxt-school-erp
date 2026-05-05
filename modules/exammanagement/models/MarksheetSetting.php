<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\MarksheetSetting as BaseMarksheetSetting;

/**
 * This is the model class for table "marksheet_setting".
 */
class MarksheetSetting extends BaseMarksheetSetting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'marksheet_header_image', 'principal_signature', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'marksheet_header_image', 'principal_signature', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
