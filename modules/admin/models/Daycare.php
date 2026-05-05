<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Daycare as BaseDaycare;

/**
 * This is the model class for table "daycare".
 */
class Daycare extends BaseDaycare
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'status'], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['start_time', 'end_time', 'created_on', 'updated_on'], 'safe'],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	

}
