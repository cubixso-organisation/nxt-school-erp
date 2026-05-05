<?php


namespace app\modules\admin\models\base;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\rbac\Role;

/**
 * This is the base model class for table "roles".
 *
 * @property integer $id
 * @property string $name
 * @property integer $campus_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\RoleHasPermissions[] $roleHasPermissions
 * @property \app\modules\admin\models\Campus $campus
 */
class Roles extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'roleHasPermissions',
            'campus'
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
            [['name', 'status',], 'required'],
            [['campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on', 'created_on', 'campus_id', 'updated_on', 'create_user_id', 'update_user_id'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles';
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


    public function getRoles()
    {
        $roles = Roles::find()
            ->where(['campus_id' => (new Campus())->getCampusId()])
            ->andWhere(['status' => Roles::STATUS_ACTIVE])
            ->all();

        $roleList = [];
        foreach ($roles as $role) {
            $roleList[$role->name] = $role->name; // Assuming 'id' is the key and 'name' is the label
        }

        return $roleList;
    }


    function can($permission)
    {
        // Check if the user is logged in
        if (!Yii::$app->user->isGuest) {
            $userRole = Yii::$app->user->identity->user_role;
            $campusId = (new User())->getCampusId();

            // Fetch the role and ensure it exists for the current campus
            $role = Roles::findOne(['name' => $userRole, 'campus_id' => $campusId]);
            if ($role === null) {
                return false;
            }


            // var_dump( $role);
            // exit;
            // Check if the permission is active
            $permission = Permissions::findOne(['name' => $permission, 'status' => Permissions::STATUS_ACTIVE]);

            if ($permission === null) {
                return false;
            }
            $checkPermission =   RoleHasPermissions::find()->where([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
                'status' => RoleHasPermissions::STATUS_ACTIVE
            ])->exists();

            // Verify if the role has the permission and it's active
            return $checkPermission;
        }

        return false;
    }


    function userHasPermission($permissions)
    {
        // Ensure $permissions is an array, if not, convert it to an array
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        // Check if the user is logged in
        if (!Yii::$app->user->isGuest) {
            $userRole = Yii::$app->user->identity->user_role;
            $campusId = (new User())->getCampusId();

            // Fetch the role and ensure it exists for the current campus
            $role = Roles::findOne(['name' => $userRole, 'campus_id' => $campusId]);
            if ($role === null) {
                return false;
            }

            // Loop through each permission in the array
            foreach ($permissions as $permission) {
                // Check if the permission is active
                $permissionRecord = Permissions::findOne(['name' => $permission, 'status' => Permissions::STATUS_ACTIVE]);
                if ($permissionRecord === null) {
                    continue; // Skip this permission if not found
                }

                // Verify if the role has the permission and it's active
                $hasPermission = RoleHasPermissions::find()->where([
                    'role_id' => $role->id,
                    'permission_id' => $permissionRecord->id,
                    'status' => RoleHasPermissions::STATUS_ACTIVE
                ])->exists();

                if ($hasPermission) {
                    return true; // Return true if at least one permission is assigned
                }
            }

            // If none of the permissions are granted, return false
            return false;
        }

        // User is not logged in
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'campus_id' => Yii::t('app', 'Campus ID'),
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
    public function getRoleHasPermissions()
    {
        return $this->hasMany(\app\modules\admin\models\RoleHasPermissions::className(), ['role_id' => 'id']);
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
     * @return \app\modules\admin\models\RolesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\RolesQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['name'] =  $this->name;

        $data['campus_id'] =  $this->campus_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['create_user_id'] =  $this->create_user_id;

        $data['update_user_id'] =  $this->update_user_id;

        return $data;
    }
}
