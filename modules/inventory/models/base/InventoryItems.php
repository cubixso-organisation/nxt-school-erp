<?php


namespace app\modules\inventory\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "inventory_items".
 *
 * @property integer $id
 * @property string $item_name
 * @property integer $item_category_id
 * @property integer $quantity
 * @property integer $available_quantity
 * @property string $description
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\inventory\models\ItemCategory $itemCategory
 */
class InventoryItems extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'itemCategory'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;
    public $quantity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'item_category_id', 'status'], 'required'],
            [['item_category_id', 'available_quantity', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_on', 'updated_on', 'quantity', 'available_quantity'], 'safe'],
            [['item_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inventory_items';
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


    public function getAvailableStock($item_id = '')
    {
        $dd = [];
        $addedItemStock = AddItemStock::find()->where(['inventory_items_id' => (int)$item_id])->andWhere(['type' => AddItemStock::ADD_TO_STOCK])->sum('quantity');
        $removeItemStock = AddItemStock::find()->where(['inventory_items_id' => (int)$item_id])->andWhere(['type' => AddItemStock::REMOVE_FROM_STOCK])->sum('quantity');

        $totalQuantityLeft = $addedItemStock - $removeItemStock;

        $issueItems = IssueReturnInventory::find()->where(['inventory_items_id' => ($item_id)])->andWhere(['status' => IssueReturnInventory::ACTIVE])->sum('quantity');
        $leftQuantity = $totalQuantityLeft - $issueItems;

        $dd = [
            'total_quantity' =>  $totalQuantityLeft,
            'quantity_left' =>  $leftQuantity,
        ];

        return $dd;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_name' => Yii::t('app', 'Item Name'),
            'item_category_id' => Yii::t('app', 'Item Category'),
            'available_quantity' => Yii::t('app', 'Available Quantity'),
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
     * @return \app\modules\inventory\models\InventoryItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\inventory\models\InventoryItemsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['item_name'] =  $this->item_name;

        $data['item_category_id'] =  $this->item_category_id;

        $data['quantity'] =  $this->quantity;

        $data['available_quantity'] =  $this->available_quantity;

        $data['description'] =  $this->description;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
