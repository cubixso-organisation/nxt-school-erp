<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\StudentFaces as BaseStudentFaces;

/**
 * This is the model class for table "student_faces".
 */
class StudentFaces extends BaseStudentFaces
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['student_id', 'campus_id', 'face_id', 'image_url', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'required'],
            [['student_id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['face_id', 'image_url'], 'string'],
            [['created_on', 'updated_on'], 'safe']
        ]);
    }
	

}
