<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\MarksDivition as BaseMarksDivition;

/**
 * This is the model class for table "marks_divition".
 */
class MarksDivition extends BaseMarksDivition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['title', 'campus_id', 'status'], 'required'],
            [['campus_id', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 199]
        ]);
    }
	

}
