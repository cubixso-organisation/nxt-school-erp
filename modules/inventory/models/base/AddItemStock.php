<?php


namespace app\modules\inventory\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "add_item_stock".
 *
 * @property integer $id
 * @property integer $item_category_id
 * @property integer $item_supplier_list_id
 * @property integer $item_store_id
 * @property integer $inventory_items_id
 * @property integer $quantity
 * @property string $purchase_price
 * @property string $date
 * @property string $attach_document
 * @property string $description
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\inventory\models\ItemCategory $itemCategory
 * @property \app\modules\inventory\models\ItemSupplierList $itemSupplierList
 * @property \app\modules\inventory\models\ItemStore $itemStore
 * @property \app\modules\inventory\models\InventoryItems $inventoryItems
 */
class AddItemStock extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'itemCategory',
            'itemSupplierList',
            'itemStore',
            'inventoryItems'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;



    const ADD_TO_STOCK = 1;
    const REMOVE_FROM_STOCK = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_category_id', 'item_supplier_list_id', 'item_store_id', 'inventory_items_id', 'quantity', 'date', 'status', 'type'], 'required'],
            [['item_category_id', 'item_supplier_list_id', 'item_store_id', 'inventory_items_id', 'quantity', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['purchase_price'], 'number'],
            [['date', 'created_on', 'updated_on'], 'safe'],
            [['description'], 'string'],
            [['attach_document'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_item_stock';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {

        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">In Active</span>';
        } elseif ($this->status == self::STATUS_DELETE) {
            return '<span class="badge badge-danger">Deleted</span>';
        }
    }

    public function getFeatureOptions()
    {
        return [

            self::IS_FEATURED => 'Is Featured',
            self::IS_NOT_FEATURED => 'Not Featured',

        ];
    }

    public function getFeatureOptionsBadges()
    {
        if ($this->is_featured == self::IS_FEATURED) {
            return '<span class="badge badge-success">Featured</span>';
        } elseif ($this->is_featured == self::IS_NOT_FEATURED) {
            return '<span class="badge badge-danger">Not Featured</span>';
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_category_id' => Yii::t('app', 'Item Category'),
            'item_supplier_list_id' => Yii::t('app', 'Item Supplier List'),
            'item_store_id' => Yii::t('app', 'Item Store'),
            'inventory_items_id' => Yii::t('app', 'Inventory Items'),
            'quantity' => Yii::t('app', 'Quantity'),
            'purchase_price' => Yii::t('app', 'Purchase Price'),
            'date' => Yii::t('app', 'Date'),
            'attach_document' => Yii::t('app', 'Attach Document'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCategory()
    {
        return $this->hasOne(\app\modules\inventory\models\ItemCategory::className(), ['id' => 'item_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSupplierList()
    {
        return $this->hasOne(\app\modules\inventory\models\ItemSupplierList::className(), ['id' => 'item_supplier_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemStore()
    {
        return $this->hasOne(\app\modules\inventory\models\ItemStore::className(), ['id' => 'item_store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryItems()
    {
        return $this->hasOne(\app\modules\inventory\models\InventoryItems::className(), ['id' => 'inventory_items_id']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_on',
                'updatedAtAttribute' => 'updated_on',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_user_id',
                'updatedByAttribute' => 'updated_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\inventory\models\AddItemStockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\inventory\models\AddItemStockQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['item_category_id'] =  $this->item_category_id;

        $data['item_supplier_list_id'] =  $this->item_supplier_list_id;

        $data['item_store_id'] =  $this->item_store_id;

        $data['inventory_items_id'] =  $this->inventory_items_id;

        $data['quantity'] =  $this->quantity;

        $data['purchase_price'] =  $this->purchase_price;

        $data['date'] =  $this->date;

        $data['attach_document'] =  $this->attach_document;

        $data['description'] =  $this->description;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
