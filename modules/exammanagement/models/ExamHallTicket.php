<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\ExamHallTicket as BaseExamHallTicket;

/**
 * This is the model class for table "exam_hall_ticket".
 */
class ExamHallTicket extends BaseExamHallTicket
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['academic_year_id', 'campus_id', 'student_detail_id','student_user_id', 'admission_no', 'created_user_id', 'updated_user_id'], 'integer'],
            [['campus_id', 'student_detail_id', 'hall_ticket_pdf', 'admission_no', 'created_user_id', 'updated_user_id'], 'required'],
            [['created_on', 'updated_on','student_user_id'], 'safe'],
            [['hall_ticket_pdf'], 'string', 'max' => 255]
        ]);
    }
	

}
