<?php

namespace app\modules\documentgenerator\models;

use Yii;
use \app\modules\documentgenerator\models\base\BonafideCertificate as BaseBonafideCertificate;

/**
 * This is the model class for table "bonafide_certificate".
 */
class BonafideCertificate extends BaseBonafideCertificate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['campus_id', 'certificate_name', 'template_type'], 'required'],
            [['campus_id', 'header_height', 'footer_height', 'body_height', 'body_width', 'template_type', 'created_user_id', 'updated_user_id'], 'integer'],
            [['header_left_text', 'header_center_text', 'header_right_text', 'body_text', 'footer_right_text', 'right_sig', 'certificate_design'], 'string'],
            [['updated_on'], 'safe'],
            [['certificate_name', 'background_image'], 'string', 'max' => 255],
            [['status', 'created_on'], 'string', 'max' => 50]
        ]);
    }
	

}
