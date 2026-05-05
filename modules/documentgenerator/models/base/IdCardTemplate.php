<?php


namespace app\modules\documentgenerator\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "id_card_template".
 *
 * @property integer $id
 * @property string $name
 * @property integer $campus_id
 * @property string $school_logo
 * @property string $signature
 * @property string $front_background_image
 * @property string $back_background_image
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 */
class IdCardTemplate extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $title;
    public $section_name;
    public $certificate_name;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            // ''
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'campus_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['school_logo', 'signature', 'front_background_image', 'back_background_image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on', 'school_logo', 'signature', 'front_background_image', 'back_background_image', 'status', 'created_on', 'updated_on', 'create_user_id', 'update_user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'id_card_template';
    }

    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'In Active',
            self::STATUS_ACTIVE => 'Active',
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
            'id' => 'ID',
            'name' => 'Name',
            'campus_id' => 'Campus ID',
            'school_logo' => 'School Logo',
            'signature' => 'Signature',
            'front_background_image' => 'Front Background Image',
            'back_background_image' => 'Back Background Image',
            'status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'create_user_id' => 'Create User ID',
            'update_user_id' => 'Update User ID',
        ];
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
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ],
        ];
    }



    /**
     * @inheritdoc
     * @return \app\modules\documentgenerator\models\IdCardTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\documentgenerator\models\IdCardTemplateQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['name'] =  $this->name;

        $data['campus_id'] =  $this->campus_id;

        $data['school_logo'] =  $this->school_logo;

        $data['signature'] =  $this->signature;

        $data['front_background_image'] =  $this->front_background_image;

        $data['back_background_image'] =  $this->back_background_image;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
