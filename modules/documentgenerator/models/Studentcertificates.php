<?php

namespace app\modules\documentgenerator\models;

use Yii;
use \app\modules\documentgenerator\models\base\Studentcertificates as BaseStudentcertificates;

/**
 * This is the model class for table "studentcertificates".
 */
class Studentcertificates extends BaseStudentcertificates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(
            parent::rules(),
            [
                // [['certificate_name'], 'required'],
                // [['header_left_text', 'header_center_text', 'header_right_text', 'body_text', 'footer_left_text', 'footer_center_text', 'footer_right_text', ], 'required'],
                // [['header_height', 'footer_height', 'body_height', 'body_width', 'created_user_id', 'updated_user_id'], 'integer'],
                // [['updated_on','certificate_design','student_photo', 'background_image'], 'safe'],
                // [['certificate_name','student_photo', 'background_image'], 'string', 'max' => 255],
                // [['status', 'created_on'], 'string', 'max' => 50]
            ]
        );
    }
}
