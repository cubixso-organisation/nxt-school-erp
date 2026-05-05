<?php


namespace app\modules\exammanagement\models\base;

use app\models\User;
use app\modules\admin\models\base\Campus;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\SubjectGroupSubjects;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;

/**
 * This is the base model class for table "teacher_class_and_subjects".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $teacher_detail_id
 * @property integer $teacher_user_id
 * @property integer $section_id
 * @property integer $subject_id
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $update_user_id
 * @property integer $create_user_id
 *
 * @property \app\modules\exammanagement\models\Campus $campus
 * @property \app\modules\exammanagement\models\ClassSections $section
 * @property \app\modules\exammanagement\models\Subjects $subject
 * @property \app\modules\exammanagement\models\TeacherDetails $teacherDetail
 * @property \app\modules\exammanagement\models\User $teacherUser
 */
class TeacherClassAndSubjects extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'campus',
            'section',
            'subject',
            'teacherDetail',
            'teacherUser'
        ];
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    const IS_FEATURED = 1;
    const IS_NOT_FEATURED = 0;


    const SINGLE_SUBJECT = 1;
    const MULTIPLE_SUBJECT = 2;
    const NO_SUBJECT = 3;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_detail_id',  'section_id', 'subject_id', 'status',], 'required'],
            [['campus_id', 'teacher_detail_id', 'teacher_user_id', 'section_id', 'subject_id', 'status', 'update_user_id', 'create_user_id'], 'integer'],
            [['created_on', 'updated_on', 'campus_id', 'teacher_user_id', 'created_on', 'updated_on', 'update_user_id', 'create_user_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_class_and_subjects';
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
            'id' => Yii::t('app', 'ID'),
            'campus_id' => Yii::t('app', 'Campus'),
            'teacher_detail_id' => Yii::t('app', 'Teacher Name'),
            'teacher_user_id' => Yii::t('app', 'Teacher Username'),
            'section_id' => Yii::t('app', 'Class & Section'),
            'subject_id' => Yii::t('app', 'Subject'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampus()
    {
        return $this->hasOne(\app\modules\admin\models\Campus::className(), ['id' => 'campus_id']);
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
    public function getSubject()
    {
        return $this->hasOne(\app\modules\admin\models\Subjects::className(), ['id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherDetail()
    {
        return $this->hasOne(\app\modules\admin\models\TeacherDetails::className(), ['id' => 'teacher_detail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'teacher_user_id']);
    }


    public function getSubjects($type)
    {
        $dat = [];
        $subjectGroupSection = SubjectGroupsClassSections::find()->where(['class_sections_id' => $type])->one();
    
        if (!empty($subjectGroupSection)) {
            $subjectGroupSubjects = SubjectGroupSubjects::find()
                ->joinWith(['subject as sub'])
                ->where(['subject_group_subjects.subject_group_id' => $subjectGroupSection->subject_group_id])
                ->andWhere(['sub.campus_id' => User::getCampusId()])
                ->all();
    
            foreach ($subjectGroupSubjects as $subjectGroupSubject) {
                $dat[] = [
                    'id' => $subjectGroupSubject->subject_id,
                    'name' => $subjectGroupSubject->subject->subject_name, // Ensure name is properly fetched
                ];
            }
        }
    
        return ['output' => $dat];
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
     * @return \app\modules\exammanagement\models\TeacherClassAndSubjectsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\exammanagement\models\TeacherClassAndSubjectsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['teacher_detail_id'] =  $this->teacher_detail_id;

        $data['teacher_user_id'] =  $this->teacher_user_id;

        $data['section']['id'] =  $this->section_id;
        $data['section']['name'] =  $this->section->section_name ?? "";

        $data['subject_id'] =  $this->subject_id;

        $data['status'] =  $this->status;

        $data['created_on'] =  $this->created_on;

        $data['updated_on'] =  $this->updated_on;

        $data['update_user_id'] =  $this->update_user_id;

        $data['create_user_id'] =  $this->create_user_id;

        return $data;
    }
}
