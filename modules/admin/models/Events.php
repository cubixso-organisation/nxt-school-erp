<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Events as BaseEvents;

/**
 * This is the model class for table "events".
 */
class Events extends BaseEvents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['event_name',  'venue', 'start_time', 'end_time'], 'required'],
            [[ 'create_user_id', 'update_user_id'], 'integer'],
            [['section'], 'each', 'rule' => ['integer']],
            [['start_time','section', 'end_time', 'created_on', 'updated_on'], 'safe'],
            [['event_name', 'venue'], 'string', 'max' => 199],
            [['image', 'description'], 'string', 'max' => 255]
        ]);
    }
	

}
