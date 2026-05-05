<?php


namespace app\modules\librarymanagement\models\base;

use app\models\User;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\TeacherDetails;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "library_members".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $library_card_no
 * @property string $admission_no
 * @property string $name
 * @property string $member_type
 * @property string $phone
 * @property integer $campus_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_user_id
 * @property integer $updated_user_id
 */
class LibraryMembers extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    public const ROLE_STUDENT = 'Student';
    public const role_teacher = 'teacher';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['member_id', 'library_card_no', 'admission_no', 'phone'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['member_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library_members';
    }

    public function getLibraryMemberType()
    {
        return [

            self::ROLE_STUDENT => 'Student',
            self::role_teacher => 'Teacher',

        ];
    }
    public function getLibraryMemberTypeBadges()
    {
        if ($this->member_type == self::ROLE_STUDENT) {
            return '<span class="badge badge-success">Featured</span>';
        } elseif ($this->member_type == self::ROLE_STUDENT) {
            return '<span class="badge badge-success">Not Featured</span>';
        }
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
            'member_id' => Yii::t('app', 'Name List'),
            'library_card_no' => Yii::t('app', 'Library Card No'),
            'admission_no' => Yii::t('app', 'Admission No'),
            'name' => Yii::t('app', 'Department Name'),
            'member_type' => Yii::t('app', 'Member Type'),
            'phone' => Yii::t('app', 'Phone'),
            'campus_id' => Yii::t('app', 'Campus ID'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'updated_user_id' => Yii::t('app', 'Updated User ID'),
        ];
    }
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
            $data = TeacherDetails::find()

                ->where(['campus_id' => $campusId])
                ->all();
            foreach ($data as $dat) {
                $out[] = ['id' => $dat['user_id'], 'name' => $dat['name']];
            }
        }

        // var_dump($data->createCommand()->getRawSql());
        // exit;


        return $output = [
            'output' => $out
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\LibraryMembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\LibraryMembersQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['member_id'] =  $this->member_id;

        $data['library_card_no'] =  $this->library_card_no;

        $data['admission_no'] =  $this->admission_no;

        $data['name'] =  $this->name;

        $data['member_type'] =  $this->member_type;

        $data['phone'] =  $this->phone;

        $data['campus_id'] =  $this->campus_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['created_user_id'] =  $this->created_user_id;

        $data['updated_user_id'] =  $this->updated_user_id;

        return $data;
    }
}
