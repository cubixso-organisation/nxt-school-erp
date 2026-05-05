<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\CampusTiming as BaseCampusTiming;

/**
 * This is the model class for table "campus_timing".
 */
class CampusTiming extends BaseCampusTiming
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            // [['campus_id', 'section_id', 'start_time', 'end_time', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            // [['campus_id', 'section_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            // [['start_time', 'end_time', 'created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
