<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\ItemCategory as BaseItemCategory;

/**
 * This is the model class for table "item_category".
 */
class ItemCategory extends BaseItemCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['item_category', 'status'], 'required'],
            [['description'], 'string'],
            [['status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on','campus_id'], 'safe'],
            [['item_category'], 'string', 'max' => 255]
        ]);
    }
	

}
