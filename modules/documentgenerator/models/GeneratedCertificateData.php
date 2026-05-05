<?php

namespace app\modules\documentgenerator\models;

use Yii;
use \app\modules\documentgenerator\models\base\GeneratedCertificateData as BaseGeneratedCertificateData;

/**
 * This is the model class for table "generated_certificate_data".
 */
class GeneratedCertificateData extends BaseGeneratedCertificateData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['student_id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['updated_on'], 'safe'],
            [['certificate_name', 'certificate_file_path','student_name'], 'string', 'max' => 255],
            [['created_on'], 'string', 'max' => 50]
        ]);
    }
	

}
