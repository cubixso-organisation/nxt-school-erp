<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\AppBanner as BaseAppBanner;

/**
 * This is the model class for table "app_banner".
 */
class AppBanner extends BaseAppBanner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['title',  'status'], 'required'],
            [['status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on','image', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 199],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg']

        ]);
    }
	

}
