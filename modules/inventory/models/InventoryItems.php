<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\InventoryItems as BaseInventoryItems;

/**
 * This is the model class for table "inventory_items".
 */
class InventoryItems extends BaseInventoryItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['item_name', 'item_category_id', 'status'], 'required'],
            [['item_category_id','available_quantity', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_on', 'updated_on','available_quantity'], 'safe'],
            [['item_name'], 'string', 'max' => 255]
        ]);
    }
	

}
