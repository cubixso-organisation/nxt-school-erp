<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\ItemSupplierList as BaseItemSupplierList;

/**
 * This is the model class for table "item_supplier_list".
 */
class ItemSupplierList extends BaseItemSupplierList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'phone', 'email'], 'required'],
            [['address', 'description'], 'string'],
            [['created_on', 'updated_on','campus_id'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['name', 'email', 'contact_person_name', 'contact_person_email'], 'string', 'max' => 255],
            [['phone', 'contact_person_phone'], 'string', 'max' => 20]
        ]);
    }
	

}
