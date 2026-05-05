<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\AddItemStock as BaseAddItemStock;

/**
 * This is the model class for table "add_item_stock".
 */
class AddItemStock extends BaseAddItemStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['item_category_id', 'item_supplier_list_id', 'item_store_id', 'inventory_items_id', 'quantity', 'purchase_price', 'date', 'status'], 'required'],
            [['item_category_id', 'item_supplier_list_id', 'item_store_id', 'inventory_items_id', 'quantity', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['purchase_price'], 'number'],
            [['date', 'created_on', 'updated_on'], 'safe'],
            [['description'], 'string'],
            [['attach_document'], 'string', 'max' => 255]
        ]);
    }
	

}
