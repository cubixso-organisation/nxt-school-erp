<?php


namespace app\modules\admin\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the base model class for table "student_notice_boards".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $section_id
 * @property string $expiry_date
 * @property integer $teacher_details_id
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $created_on 
 * @property string $updated_on
 *
 * @property \app\modules\admin\models\ClassSections $section
 * @property \app\modules\admin\models\TeacherDetails $teacherDetails
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 */
class StudentNoticeBoards extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'section',
            'teacherDetails',
            'createUser',
            'updateUser',
            'studentHasNotices'
        ];
    } 

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;

    const is_global_yes = 1;
    const is_global_no = 2;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'section_id', 'expiry_date'], 'required'],
            [['description'], 'string'],
            [['section_id', 'teacher_details_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['expiry_date', 'created_on', 'updated_on','is_global','notice_image'], 'safe'],
            [['title','notice_image'], 'string', 'max' => 255]
        ];
    } 

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_notice_boards';
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
        }elseif ($this->status == self::STATUS_DELETE) {
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


    public function getIsGlobalOptions()
    {
        return [
            self::is_global_no => 'No',
            self::is_global_yes => 'Yes',
           
        ];
    }

    public function getIsGlobalBadges()
    {
        if ($this->is_global == self::is_global_yes) {
            return '<span class="badge badge-success">Yes</span>';
        } elseif ($this->is_global == self::is_global_no) {
            return '<span class="badge badge-danger">No</span>';
        }
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'notice_image' => Yii::t('app', 'Image'),
            'section_id' => Yii::t('app', 'Section'),
            'expiry_date' => Yii::t('app', 'Expiry Date'),
            'teacher_details_id' => Yii::t('app', 'Teacher Details'),
            'status' => Yii::t('app', 'Status'),
            'create_user_id' => Yii::t('app', 'Create User '),
            'update_user_id' => Yii::t('app', 'Update User'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherDetails()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_details_id']);
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
    public function getStudentHasNotices()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasNotice::className(), ['student_notice_board_id' => 'id']);
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
     * @return \app\modules\admin\models\StudentNoticeBoardsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentNoticeBoardsQuery(get_called_class());
    }
public function asJson(){
    $data = [] ; 
            $data['id'] =  $this->id;
        
                $data['title'] =  $this->title;
        
                $data['description'] =  $this->description;
                $data['notice_image'] =  $this->notice_image;
        
                $data['section_id'] =  $this->section_id;
        
                $data['expiry_date'] =  $this->expiry_date;
        
                $data['teacher_details_id'] =  !empty($this->teacher_details_id)?$this->teacher_details_id:'';
                if($this->is_global==StudentNoticeBoards::is_global_yes){
                    $data['designation'] =  "Principal";
                    }else{
                    $data['designation'] =  "Teacher";

                    }

                    

        
                $data['status'] =  $this->status;
        
                $data['create_user_id'] =  $this->create_user_id;
        
                $data['update_user_id'] =  $this->update_user_id;
        
                $data['created_on'] =  $this->created_on;
        
                $data['updated_on'] =  $this->updated_on;
        
            return $data;
}


}

 
