<?php

namespace app\modules\inventory\models;

use Yii;
use \app\modules\inventory\models\base\IssueReturnInventory as BaseIssueReturnInventory;

/**
 * This is the model class for table "issue_return_inventory".
 */
class IssueReturnInventory extends BaseIssueReturnInventory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['user_type', 'issue_to', 'issue_by', 'issue_date', 'item_category_id', 'status'], 'required'],
            [['issue_date', 'return_date','expected_return_date', 'created_on', 'updated_on','campus_id', 'quantity', 'inventory_items_id'], 'safe'],
            [['note'], 'string'],
            [['item_category_id', 'quantity', 'status', 'created_user_id', 'updated_user_id','campus_id'], 'integer'],
            [['user_type', 'issue_to', 'issue_by'], 'string', 'max' => 255]
        ]);
    }
	

}
