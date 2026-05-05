<?php

namespace app\modules\exammanagement\models;

use Yii;
use \app\modules\exammanagement\models\base\ExamStudentMarksheet as BaseExamStudentMarksheet;

/**
 * This is the model class for table "exam_student_marksheet".
 */
class ExamStudentMarksheet extends BaseExamStudentMarksheet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['user_id', 'student_id', 'session_id', 'class_id', 'section_id', 'exam_id', 'total_marks', 'total_percentage', 'marks_type', 'total_grade', 'total_cgpa', 'marksheet_url', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['user_id', 'student_id', 'session_id', 'class_id', 'section_id', 'exam_id', 'marks_type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_marks', 'total_percentage', 'total_cgpa'], 'number'],
            [['marksheet_url'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['total_grade'], 'string', 'max' => 255]
        ]);
    }
	

}
