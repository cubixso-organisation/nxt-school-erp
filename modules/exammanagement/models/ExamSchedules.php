<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\ExamSchedules as BaseExamSchedules;

/**
 * This is the model class for table "exam_schedules".
 */
class ExamSchedules extends BaseExamSchedules
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
        
        ]);
    }
	

}
