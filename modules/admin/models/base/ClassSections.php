<?php


namespace app\modules\admin\models\base;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/** 
 * This is the base model class for table "class_sections".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_class_id
 * @property string $section_name
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentClass $studentClass
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\FeeStructures[] $feeStructures
 */
class ClassSections extends \yii\db\ActiveRecord
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
            'studentClass',
            'createUser',
            'updateUser',
            'feeStructures',
            'examsResults',
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
            [['campus_id', 'student_class_id', 'section_name', 'status'], 'required'],
            [['campus_id', 'student_class_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['section_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_sections';
    }

    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
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
            'student_class_id' => Yii::t('app', 'Student Class ID'),
            'section_name' => Yii::t('app', 'Section Name'),
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
    public function getExamsResults()
    {
        return $this->hasMany(\app\modules\admin\models\ExamsResult::className(), ['section_id' => 'id']);
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
    public function getStudentClass()
    {
        return $this->hasOne(\app\modules\admin\models\StudentClass::className(), ['id' => 'student_class_id']);
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
    public function getFeeStructures()
    {
        return $this->hasMany(\app\modules\admin\models\FeeStructures::className(), ['class_section_id' => 'id']);
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
                'value' => date('Y-m-d H:i:s'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ],
        ];
    }
    public function getSections($class_id)
    {
        $out = [];
        $campusId = User::getCampusId();

        if (!empty($class_id)) {
            // Retrieve data from the Floor table where hostel_id is equal to $floor and (campus_id is equal to $campusId or campus_id is equal to $chiefWardenCampusId)
            $data = ClassSections::find()
                ->where(['student_class_id' => $class_id])
                ->andWhere(['campus_id' => $campusId])
                ->all();

            foreach ($data as $dat) {
                $out[] = ['id' => $dat['id'], 'name' => $dat['section_name']];
            }
        }

        return [
            'output' => $out
        ];
    }


    /**
     * @inheritdoc
     * @return \app\modules\admin\models\ClassSectionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\ClassSectionsQuery(get_called_class());
    }
    public function asJson()
    {
        $data = [];
        $data['id'] =  $this->id;

        $data['campus_id'] =  $this->campus_id;

        $data['student_class_id'] =  $this->student_class_id;

        $data['section_name'] =  $this->section_name;



        return $data;
    }

    public function asJsonExamResult($student_id)
    {

        $data = [];
        $data['id'] =  $this->id;

        $data['student_class'] =  $this->studentClass->title;
        $data['section_name'] =  $this->section_name;
        $exams_result = ExamsResult::find()->where(['student_id' => $student_id])

            ->all();
        if (!empty($exams_result)) {
            foreach ($exams_result as $exams_result_data) {
                $list[] = $exams_result_data->asJsonsStudentProfile();
            }
            if (!empty($list)) {
                $data['exams_result'] = $list;
            } else {
                $data['exams_result'] = '';
            }
        } else {
            $data['exams_result'] = '';
        }





        return $data;
    }
}
