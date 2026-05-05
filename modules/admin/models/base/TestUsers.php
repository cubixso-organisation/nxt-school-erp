<?php

namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "test_users".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $contact_no
 * @property string $alternative_contact
 * @property string $date_of_birth
 * @property string $gender
 * @property string $description
 * @property string $address
 * @property string $profile_image
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 */
class TestUsers extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
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
            [['email', 'first_name', 'last_name'], 'required'],
            [['date_of_birth', 'created_on', 'updated_on'], 'safe'],
            [['description', 'address'], 'string'],
            [['status', 'create_user_id', 'update_user_id'], 'integer'],
            [['username', 'last_name', 'contact_no', 'alternative_contact', 'profile_image'], 'string', 'max' => 255],
            [['email', 'first_name'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_users';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'alternative_contact' => Yii::t('app', 'Alternative Contact'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'description' => Yii::t('app', 'Description'),
            'address' => Yii::t('app', 'Address'),
            'profile_image' => Yii::t('app', 'Profile Image'),
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
     * @return \app\modules\admin\models\TestUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\TestUsersQuery(get_called_class());
    }
}
