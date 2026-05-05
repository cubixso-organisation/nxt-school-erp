<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\ItemStore as BaseItemStore;

/**
 * This is the model class for table "item_store".
 */
class ItemStore extends BaseItemStore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['item_store_name', 'item_store_code'], 'required'],
            [['description'], 'string'],
            [['created_on', 'updated_on','campus_id'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['item_store_name'], 'string', 'max' => 255],
            [['item_store_code'], 'string', 'max' => 20]
        ]);
    }
	

}
