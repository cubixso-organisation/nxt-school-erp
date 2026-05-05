<?php

namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "fcm_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $image_url
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 */
class FcmNotification extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;



    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;


    
 

    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'user',
            'updateUser',
            'createUser'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'description', 'status'], 'required'],
            [['user_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title', 'description', 'url', 'image_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fcm_notification';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'url' => Yii::t('app', 'Url'),
            'image_url' => Yii::t('app', 'Image Url'),
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
    public function getUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'user_id']);
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
    public function getCreateUser()
    {
        return $this->hasOne(\app\modules\admin\models\User::className(), ['id' => 'create_user_id']);
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
     * @return \app\modules\admin\models\FcmNotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\FcmNotificationQuery(get_called_class());
    }




    public function asJson(){
        $data = [] ; 
                $data['id'] =  $this->id;
            
                    $data['user_id'] =  $this->user_id;
            
                    $data['title'] =  $this->title;
            
                    $data['description'] =  $this->description;
            
                    $data['url'] =  $this->url;
            
                    $data['image_url'] =  $this->image_url;
                    $data['notification_type'] =  $this->notification_type;
                    $data['notification_type_id'] =  $this->notification_type_id;
                    $data['status'] =  $this->status;

                    $data['created_on'] =  $this->created_on;
            
                    $data['updated_on'] =  $this->updated_on;
            
                    $data['create_user_id'] =  $this->create_user_id;
            
                    $data['update_user_id'] =  $this->update_user_id;
            
                return $data;
    }

    




}
