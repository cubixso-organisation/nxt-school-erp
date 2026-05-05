<?php

namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "campus_web_settings".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property string $name
 * @property string $setting_key
 * @property string $value
 * @property integer $type_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\Campus $campus
 */
class CampusWebSettings extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'createUser',
            'updateUser',
            'campus'
        ];
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;

    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;



    public const SMS_KEY = 'sms_api_key';
    public const MAP_KEY = 'google_map_api_key';
    public const PAYTM_MID = 'paytm_mid';
    public const PAYTM_KEY = 'paytm_Key';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'setting_key', 'value'], 'required'],
            [['campus_id', 'type_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name', 'setting_key', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'campus_web_settings';
    }

    public function getSettingKeyOptions()
    {
        return [

            self::SMS_KEY => 'sms_api_key',
            self::MAP_KEY => 'google_map_api_key',
            self::PAYTM_MID => 'paytm_mid',
            self::PAYTM_KEY => 'paytm_Key',

        ];
    }
    public function getSettingKeyOptionsBadges()
    {
        if ($this->setting_key == self::SMS_KEY) {
            return '<span class="badge badge-success">sms_api_key</span>';
        } elseif ($this->setting_key == self::MAP_KEY) {
            return '<span class="badge badge-default">google_map_api_key/span>';
        } elseif ($this->setting_key == self::PAYTM_MID) {
            return '<span class="badge badge-danger">paytm_mid</span>';
        } elseif ($this->setting_key == self::PAYTM_KEY) {
            return '<span class="badge badge-danger">paytm_Key</span>';
        }
    }


    public function getStateOptions()
    {
        return [

            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETE => 'Deleted',

        ];
    }
    public function getStateOptionsBadges()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Active</span>';
        } elseif ($this->status == self::STATUS_INACTIVE) {
            return '<span class="badge badge-default">Inactive</span>';
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
              'campus_id' => Yii::t('app', 'School Or College'),
            'name' => Yii::t('app', 'Name'),
            'setting_key' => Yii::t('app', 'Setting Key'),
            'value' => Yii::t('app', 'Value'),
            'type_id' => Yii::t('app', 'All Campus'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
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
                'value' =>date('Y-m-d H:i:s'),
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
     * @return \app\modules\admin\models\CampusWebSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\CampusWebSettingsQuery(get_called_class());
    }


public function getWebSettingByCampus($campus_id, $setting_key)
{
    $campus_web_settings = CampusWebSettings::find()
    ->where(['campus_id'=>$campus_id])
    ->where(['setting_key'=>$setting_key])
    ->one();
    if (!empty($campus_web_settings)) {
        return $campus_web_settings->value;
    } else {
        return;
    }
}


public function asJson()
{
    $data = [] ;
    $data['id'] =  $this->id;

    $data['campus_id'] =  $this->campus_id;

    $data['name'] =  $this->name;

    $data['setting_key'] =  $this->setting_key;

    $data['value'] =  $this->value;

    $data['type_id'] =  $this->type_id;

    $data['status'] =  $this->status;

    $data['created_on'] =  $this->created_on;

    $data['updated_on'] =  $this->updated_on;

    $data['create_user_id'] =  $this->create_user_id;

    $data['update_user_id'] =  $this->update_user_id;

    return $data;
}
}
