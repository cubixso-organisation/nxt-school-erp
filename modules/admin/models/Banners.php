<?php

namespace app\modules\admin\models;

use Yii;
use \app\modules\admin\models\base\Banners as BaseBanners;

/**
 * This is the model class for table "banners".
 */
class Banners extends BaseBanners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['title',  'url', 'status'], 'required'],
            [['status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on','image', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 199],
            [['url'], 'string', 'max' => 250],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg']

        ]);
    }
	

}
