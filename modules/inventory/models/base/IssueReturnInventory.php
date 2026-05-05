<?php


namespace app\modules\inventory\models\base;

use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\TeacherDetails;
use app\modules\librarymanagement\models\base\LibraryMembers;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "issue_return_inventory".
 *
 * @property integer $id
 * @property string $user_type
 * @property string $issue_to
 * @property string $issue_by
 * @property string $issue_date
 * @property string $return_date
 * @property string $note
 * @property integer $item_category_id
 * @property integer $inventory_items_id
 * @property integer $quantity
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 *
 * @property \app\modules\inventory\models\ItemCategory $itemCategory
 * @property \app\modules\inventory\models\InventoryItems $inventoryItems
 */
class IssueReturnInventory extends \yii\db\ActiveRecord
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
            'inventoryItems',
            'issueTo',
            'issueBy'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;
    const ACTIVE = 1;
    const RETURN = 2;
    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    public const role_teacher = 'teacher';
    public const ROLE_WARDEN = 'Warden';
    public const ROLE_LIBRARIAN = 'Librarian';
    public const ROLE_STUDENT = 'Student';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type', 'issue_to', 'issue_by', 'issue_date', 'item_category_id', 'status'], 'required'],
            [['issue_date', 'return_date', 'expected_return_date', 'created_on', 'updated_on', 'campus_id', 'quantity', 'inventory_items_id'], 'safe'],
            [['note'], 'string'],
            [['item_category_id', 'quantity', 'status', 'created_user_id', 'updated_user_id', 'campus_id'], 'integer'],
            [['user_type', 'issue_to', 'issue_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue_return_inventory';
    }

    public function getUserTypeInventory()
    {
        return [

            self::role_teacher => 'teacher',
            self::ROLE_WARDEN => 'Warden',
            self::ROLE_LIBRARIAN => 'Librarian',
            self::ROLE_STUDENT => 'Student',

        ];
    }
    public function getStateOptions()
    {
        return [

            // self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Returned',

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
            'user_type' => Yii::t('app', 'User Type'),
            'issue_to' => Yii::t('app', 'Issue To'),
            'issue_by' => Yii::t('app', 'Issue By'),
            'issue_date' => Yii::t('app', 'Issue Date'),
            'expected_return_date' => Yii::t('app', 'Expected Return Date'),
            'note' => Yii::t('app', 'Note'),
            'item_category_id' => Yii::t('app', 'Item Category'),
            'inventory_items_id' => Yii::t('app', 'Inventory Items'),
            'quantity' => Yii::t('app', 'Quantity'),
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
    public function getInventoryItems()
    {
        return $this->hasOne(\app\modules\inventory\models\InventoryItems::className(), ['id' => 'inventory_items_id']);
    }


    public function getIssueTo()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'issue_to']);
    }
    public function getIssueBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'issue_by']);
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
    public function getUser($type)
    {
        $out = [];
        $dat = '';
        $campusId = User::getCampusId();

        if ($type == User::ROLE_STUDENT) {
            $data = StudentDetails::find()
                ->where(['campus_id' => $campusId])
                ->all();

            foreach ($data as $dat) {
                $out[] = ['id' => $dat['user_id'], 'name' => $dat['student_name']];
            }
        } else if ($type == User::role_teacher) {
            $data = User::find()
                ->where(['campus_id' => $campusId])->andWhere(['user_role' => User::role_teacher])
                ->all();
            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
            }
            //    var_dump($data->createCommand()->getRawSql());
            // exit;
        } else if ($type == User::ROLE_WARDEN) {
            $data = user::find()
                ->where(['campus_id' => $campusId])->andWhere(['user_role' => User::ROLE_WARDEN])
                ->all();
            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
            }
        } else if ($type == User::ROLE_LIBRARIAN) {
            $data = User::find()
                ->where(['campus_id' => $campusId])->andWhere(['user_role' => User::ROLE_LIBRARIAN])
                ->all();
            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['username']];
            }
        }

        // var_dump($data->createCommand()->getRawSql());
        // exit;


        return $output = [
            'output' => $out
        ];
    }
    public function getCategory($type)
    {
        $out = [];
        $name = '';

        // Get campus ID from user
        $campusId = (new User())->getCampusId();

        // Find item names with the specified category and campus ID
        $itemName = InventoryItems::find()
            ->joinWith('itemCategory') // Assuming there's a relation named 'itemCategory'
            ->andWhere(['item_category_id' => $type])
            ->andWhere(['item_category.campus_id' => $campusId]) // Adjust the relation name accordingly
            ->all();

        foreach ($itemName as $name) {
            $out[] = ['id' => $name['id'], 'name' => $name['item_name']];
        }

        return $output = [
            'output' => $out
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\inventory\models\IssueReturnInventoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\inventory\models\IssueReturnInventoryQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['user_type'] =  $this->user_type;

        $data['issue_to'] =  $this->issue_to;

        $data['issue_by'] =  $this->issue_by;

        $data['issue_date'] =  $this->issue_date;

        $data['return_date'] =  $this->return_date;

        $data['note'] =  $this->note;

        $data['item_category_id'] =  $this->item_category_id;

        $data['inventory_items_id'] =  $this->inventory_items_id;

        $data['quantity'] =  $this->quantity;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
