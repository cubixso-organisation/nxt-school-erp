<?php

namespace app\modules\admin\models\base;

use app\modules\admin\models\base\StudentHasBus as BaseStudentHasBus;
use app\modules\admin\models\base\StudentHasParent as BaseStudentHasParent;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentAttendanceBus;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\StudentHasParent;
use app\modules\admin\models\User;
use app\modules\childassessment\models\base\StudentMeritMarks;
use app\modules\hostelmanagement\models\base\HostellersAttandance;
use app\modules\librarymanagement\models\base\LibraryMembers;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/** 
 * This is the base model class for table "student_details".
 * 
 * @property integer $id
 * @property integer $campus_id
 * @property integer $user_id
 * @property string $admission_number
 * @property string $profile_photo
 * @property string $student_name
 * @property string $gender
 * @property string $date_of_birth
 * @property string $phone_number
 * @property integer $verified_phone
 * @property string $previous_school_name
 * @property string $previous_school_address
 * @property integer $student_class_id
 * @property integer $hostal_is_required
 * @property integer $bus_transport_required 
 * @property integer $status
 * @property string $created_on
 * @property string $updated_on
 * @property integer $create_user_id
 * @property integer $update_user_id
 *
 * @property \app\modules\admin\models\AgentStudentJoin[] $agentStudentJoins
 * @property \app\modules\admin\models\AssignFeeToStudent[] $assignFeeToStudents
 * @property \app\modules\admin\models\PayFees[] $payFees
 * @property \app\modules\admin\models\PaymentDetails[] $paymentDetails
 * @property \app\modules\admin\models\StudentAttendanceBus[] $studentAttendanceBuses
 * @property \app\modules\admin\models\User $user
 * @property \app\modules\admin\models\User $updateUser
 * @property \app\modules\admin\models\User $createUser
 * @property \app\modules\admin\models\Campus $campus
 * @property \app\modules\admin\models\StudentClass $studentClass
 * @property \app\modules\admin\models\StudentHasBus[] $studentHasBuses
 * @property \app\modules\admin\models\StudentHasParent[] $studentHasParents
 * @property \app\modules\admin\models\StudentSpecialCourses[] $studentSpecialCourses
 */
class StudentDetails extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    public $bus_id;
    public $bus_route_id;
    public $fileImport;
    public $next_academic_year;
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'payFees',
            'paymentDetails',
            'studentAttendanceBuses',
            'studentClassAttendances',
            'user',
            'parent',
            'updateUser',
            'createUser',
            'campus',
            'studentClass',
            'section',
            'bloodGroup',
            'academicYear',
            'studentHasBuses',
            'studentHasDairies',
            'studentSpecialCourses',
            'teacherHasStudents'
        ];
    }
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETE = 2;
    public const STATUS_LEAVE = 3;
    public const IS_FEATURED = 1;
    public const IS_NOT_FEATURED = 0;
    public const HOSTEL_REQUIRED_YES = 1;
    public const HOSTEL_REQUIRED_NO = 2;
    public const TRANSPORT_REQUIRED_YES = 1;
    public const TRANSPORT_REQUIRED_NO = 2;
    public const IS_MALE  = 'Male';
    public const IS_FEMALE = 'Female';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admission_number','campus_id', 'user_id', 'academic_year_id', 'student_name', 'gender', 'date_of_birth', 'phone_number', 'student_class_id', 'hostal_is_required', 'bus_transport_required', 'phone_number','current_address','parent_id'], 'required'],
            [['campus_id', 'user_id', 'academic_year_id', 'parent_id', 'student_class_id', 'section_id', 'hostal_is_required', 'bus_transport_required', 'blood_group_id', 'national_Identification_number', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['admission_date', 'created_on', 'updated_on', 'bus_id', 'bus_route_id','profile_photo'], 'safe'],
            [['admission_number', 'previous_school', 'old_admission_number', 'mother_tongue', 'identification_marks', 'rool_number', 'profile_photo', 'current_address', 'permanent_address', 'student_name', 'date_of_birth', 'category', 'religion', 'caste', 'phone_number', 'email', 'student_house', 'height', 'weight'], 'string', 'max' => 255],
            [['fileImport'], 'file',  'extensions' => 'ods,xls,xlsx'],
            [['gender'], 'string', 'max' => 10],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\admin\models\base\StudentDetails', 'message' => 'This email address has already been taken.'],
            [['phone_number'], 'string', 'max' => 10],
            ['phone_number', 'match', 'pattern' => '/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/'],
            // [['profile_photo'], 'required', 'on' => 'create'],
            
        ];
    }
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass();
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];
        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }
        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass();
                }
            }
        }
        unset($model, $formName, $post);
        return $models;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_details';
    }
    public function getStateOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETE => 'Deleted',
            self::STATUS_LEAVE => 'Leave',
        ];
    }
    public function getStateOptionsAcademic()
    {
        return [
            self::STATUS_ACTIVE => 'Continue',
            self::STATUS_LEAVE => 'Left',
        ];
    }
    public function getHostelRequiredOptions()
    {
        return [
            self::HOSTEL_REQUIRED_NO => 'NO',
            self::HOSTEL_REQUIRED_YES => 'YES',
        ];
    }
    public function getTransportRequiredOptions()
    {
        return [
            self::TRANSPORT_REQUIRED_NO => 'NO',
            self::TRANSPORT_REQUIRED_YES => 'YES',
        ];
    }
    public function getGender()
    {
        return [
            self::IS_MALE => 'Male',
            self::IS_FEMALE => 'Female',
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
        } elseif ($this->status == self::STATUS_LEAVE) {
            return '<span class="badge badge-danger">Left</span>';
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
            'campus_id' => Yii::t('app', 'Campus ID'),
            'user_id' => Yii::t('app', 'User'),
            'parent_id' => Yii::t('app', '*Parent Name'),
            'admission_number' => Yii::t('app', '*Admission Number'),
            'rool_number' => Yii::t('app', 'Roll Number'),
            'profile_photo' => Yii::t('app', 'Profile Photo'),
            'student_name' => Yii::t('app', '*Student Name'),
            'gender' => Yii::t('app', '*Gender'),
            'date_of_birth' => Yii::t('app', '*Date Of Birth'),
            'category' => Yii::t('app', 'Category'),
            'religion' => Yii::t('app', 'Religion'),
            'caste' => Yii::t('app', 'Caste'),
            'phone_number' => Yii::t('app', '*Phone Number'),
            'student_class_id' => Yii::t('app', 'Student Class'),
            'section_id' => Yii::t('app', 'Section'),
            'academic_year' => Yii::t('app', '*Academic Year'),
            'hostal_is_required' => Yii::t('app', '*Hostel Is Required'),
            'bus_transport_required' => Yii::t('app', '*Bus Transport Required'),
            'academic_year_id' => Yii::t('app', '*Academic Year'),
            'email' => Yii::t('app', 'Email'),
            'admission_date' => Yii::t('app', '*Admission Date'),
            'blood_group_id' => Yii::t('app', 'Blood Group'),
            'student_house' => Yii::t('app', 'Student House'),
            'height' => Yii::t('app', 'Student Height'),
            'weight' => Yii::t('app', 'Student Weight'),
            'national_Identification_number' => Yii::t('app', 'National Identification Number'),
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
    public function getSection()
    {
        return $this->hasOne(\app\modules\admin\models\ClassSections::className(), ['id' => 'section_id']);
    }
    public function getStudentOfBalanceAmount($student_id)
    {
        $pay_fees = PayFees::find()->where(['student_id' => $student_id])->all();
        foreach ($pay_fees  as  $pay_fees_data) {
            $fee_structures_id[] = $pay_fees_data->fee_structures_id;
            $fees_cut_arr[] = $pay_fees_data->fees_cut;
        }
        if (!empty($fee_structures_id)) {
            $fee_structures_apy = FeeStructures::find()->where(['in', 'id', $fee_structures_id])->sum('fee');
            $discount_fee = array_sum($fees_cut_arr);
            $studentPayFee =  $fee_structures_apy - $discount_fee;
            $payment_details = PaymentDetails::find()->where(['student_id' => $student_id])->sum('paid_amount');
            return   $studentPayFee - $payment_details;
        } else {
            return 0;
        }
    }
    public static function getPatentNumberByStudentId($student_id)
    {
        $student_has_parent = StudentHasParent::find()->where(['student_id' => $student_id])->one();
        if (!empty($student_has_parent)) {
            $parent_id = $student_has_parent->parent_id;
            $parent = user::find()->where(['id' => $parent_id])->one();
            if (!empty($parent)) {
                $contact_no = $parent->contact_no;
                return $contact_no;
            } else {
                return;
            }
        } else {
            return;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgentStudentJoins()
    {
        return $this->hasMany(\app\modules\admin\models\AgentStudentJoin::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayFees()
    {
        return $this->hasMany(\app\modules\admin\models\PayFees::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(\app\modules\admin\models\PaymentDetails::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAttendanceBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentAttendanceBus::className(), ['student_id' => 'id']);
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
    public function getStudentHasBuses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasBus::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentSpecialCourses()
    {
        return $this->hasMany(\app\modules\admin\models\StudentSpecialCourses::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloodGroup()
    {
        return $this->hasOne(\app\modules\admin\models\BloodGroups::className(), ['id' => 'blood_group_id']);
    }
    public function getParentIdByStudentId($student_id)
    {
        $student_has_parent = BaseStudentHasParent::find()->where(['student_id' => $student_id])->one();
        if (!empty($student_has_parent)) {
            return $student_has_parent->parent_id;
        } else {
            return;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\app\modules\admin\models\ParentDetails::className(), ['id' => 'parent_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicYear()
    {
        return $this->hasOne(\app\modules\admin\models\AcademicYears::className(), ['id' => 'academic_year_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherHasStudents()
    {
        return $this->hasMany(\app\modules\admin\models\TeacherHasStudents::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentClassAttendances()
    {
        return $this->hasMany(\app\modules\admin\models\StudentClassAttendance::className(), ['student_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHasDairies()
    {
        return $this->hasMany(\app\modules\admin\models\StudentHasDairy::className(), ['student_id' => 'id']);
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
    /**
     * @inheritdoc
     * @return \app\modules\admin\models\StudentDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\admin\models\StudentDetailsQuery(get_called_class());
    }
    public function getStudentData($campus_id)
    {
        $out = [];
        $data = StudentDetails::find()
            ->where(['campus_id' => $campus_id])
            // ->andWhere(['status' => StudentDetails::STATUS_ACTIVE])
            ->asArray()
            ->all();
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
        }
        return $output = [
            'output' => $out
        ];
    }
    public function getStudentDataByClassSection($section_id)
    {
        $out = [];
        $data = StudentDetails::find()
            ->andWhere(['section_id' => $section_id])
            ->asArray()
            ->all();
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
        }
        return $output = [
            'output' => $out
        ];
    }
    public function getStudentDataByClassSectionBus($section_id)
    {
        $out = [];
        $studentHasBuses = BaseStudentHasBus::find()
            ->joinWith('student')
            ->where(['student_details.section_id' => $section_id])
            ->all();
        foreach ($studentHasBuses as $studentHasBuses_data) {
            $student_id_arr[] = $studentHasBuses_data->student_id;
        }
        if (!empty($student_id_arr)) {
            $data = StudentDetails::find()
                ->andWhere(['student_details.section_id' => $section_id])
                ->andWhere(['not in', 'student_details.id', $student_id_arr])
                ->asArray()
                ->all();
        } else {
            $data = StudentDetails::find()
                ->andWhere(['section_id' => $section_id])
                ->asArray()
                ->all();
        }
        foreach ($data as $dat) {
            $out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
        }
        return $output = [
            'output' => $out
        ];
    }
    public function getStudentDataByClassSectionParent($section_id)
{
    $out = [];
    $student_id_arr = [];

    // Get the students with buses
    $studentHasBuses = StudentHasParent::find()
        ->innerJoinWith('student')
        ->where(['student_details.section_id' => $section_id])
        ->all();

    // Collect student IDs
    foreach ($studentHasBuses as $studentHasBuses_data) {
        $student_id_arr[] = $studentHasBuses_data->student_id;
    }

    // Fetch students based on the collected IDs
    if (!empty($student_id_arr)) {
        $data = StudentDetails::find()
            ->andWhere(['student_details.section_id' => $section_id])
            ->andWhere(['not in', 'student_details.id', $student_id_arr])
            ->asArray()
            ->all();
    } else {
        $data = StudentDetails::find()
            ->andWhere(['section_id' => $section_id])
            ->asArray()
            ->all();
    }

    // Prepare the output with student name and father's name
    foreach ($data as $dat) {
        $father = ParentDetails::findOne($dat['parent_id']);
        $fatherName = $father ? $father->name_of_the_father : 'Unknown';
        $out[] = [
            'id' => $dat['id'],
            'name' => $dat['student_name'] . ' (Father: ' . $fatherName . ')'
        ];
    }

    return [
        'output' => $out,
    ];
}

    public function getStudentDataByFeeStructureId($fee_structures)
    {
        $out = [];
        $fee_structures = FeeStructures::find()->where(['id' => $fee_structures])->one();
        if (!empty($fee_structures)) {
            $student_class_id = $fee_structures->student_class_id;
            $class_section_id  = $fee_structures->class_section_id;
            $student_details_check = StudentDetails::find()
                ->where(['student_class_id' => $student_class_id])
                ->andWhere(['section_id' => $class_section_id])
                ->all();
            foreach ($student_details_check as $student_details_check_data) {
                $s_id[] = $student_details_check_data->id;
            }
            if (!empty($s_id)) {
                $pay_fees = PayFees::find()->where(['in', 'student_id', $s_id])
                    ->where(['fee_structures_id' => $fee_structures])->all();
                if (!empty($pay_fees)) {
                    foreach ($pay_fees as $pay_fees_data) {
                        $s_id_data[] =  $pay_fees_data->student_id;
                    }
                    $student_details = StudentDetails::find()
                        ->where(['student_class_id' => $student_class_id])
                        ->andWhere(['section_id' => $class_section_id])
                        ->andWhere(['not in', 'id', $s_id_data])
                        ->asArray()
                        ->all();
                    foreach ($student_details as $dat) {
                        $out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
                    }
                } else {
                    $student_details = StudentDetails::find()
                        ->where(['student_class_id' => $student_class_id])
                        ->andWhere(['section_id' => $class_section_id])
                        ->asArray()
                        ->all();
                    foreach ($student_details as $dat) {
                        $out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
                    }
                }
            } else {
            }
        }
        return $output = [
            'output' => $out
        ];
    }
    public function asJson($unique_key = '')
    {
        // var_dump($this->id);exit;
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $libraryMember = LibraryMembers::find()->where(['member_id' => $this->user_id])->one();
        if (!empty($libraryMember)) {
            $data['is_library_member'] = true;
        } else {
            $data['is_library_member'] = false;
        }
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number ?? '';
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class'] = '';
        }
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }
        $data['hostal_is_required'] =  $this->hostal_is_required;
        $data['bus_transport_required'] =  $this->bus_transport_required;
        $data['status'] =  $this->status;
        $data['created_on'] =  $this->created_on;
        $data['updated_on'] =  $this->updated_on;
        $data['create_user_id'] =  $this->create_user_id;
        $data['update_user_id'] =  $this->update_user_id;
        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $this->id])->one();
        if (!empty($agent_student_join)) {
            $data['amount'] = $agent_student_join->amount;
        } else {
            $data['amount'] = 0;
        }
        if (!empty($this->parent)) {
            $data['parentDetails'] =  $this->parent->asJson();
        } else {
            $data['parentDetails'] =  '';
        }
        if (!empty($unique_key)) {
            $student_attendance_bus = StudentAttendanceBus::find()->where(['unique_key' => $unique_key])->andWhere(['student_id' => $this->id])->one();
            if (!empty($student_attendance_bus)) {
                $data['student_attendance_bus'] = $student_attendance_bus->asJson();
            } else {
                $data['student_attendance_bus'] =  0;
            }
        } else {
            $data['student_attendance_bus'] =  0;
        }
        $studentHasBuses = StudentHasBus::find()->where(['student_id' => $this->id])->one();
        if (!empty($studentHasBuses)) {
            $data['studentHasBuses'] = $studentHasBuses->asJson();
        } else {
            $data['studentHasBuses'] = 0;
        }
        $hostellers = Hostellers::find()->where(['student_id' => $this->user_id])->one();
        if (!empty($hostellers)) {
            $data['is_hostel'] = true;
        } else {
            $data['is_hostel'] = false;
        }
         // Check if the student's face is registered in the student_faces table
    $student_face_registered = StudentFaces::find()
    ->where(['student_id' => $this->id])
    ->exists(); // returns true if a record exists, otherwise false

// Add the student_face_registered key to the existing data
$data['student_face_registered'] = $student_face_registered;
        return $data;
    }
    public function asJsonExamResult($exsm_id, $academic_year_id = '')
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class'] = '';
        }
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }
        $data['hostal_is_required'] =  $this->hostal_is_required;
        $data['bus_transport_required'] =  $this->bus_transport_required;
        $data['status'] =  $this->status;
        $data['created_on'] =  $this->created_on;
        $data['updated_on'] =  $this->updated_on;
        $data['create_user_id'] =  $this->create_user_id;
        $data['update_user_id'] =  $this->update_user_id;
        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $this->id])->one();
        if (!empty($agent_student_join)) {
            $data['amount'] = $agent_student_join->amount;
        } else {
            $data['amount'] = 0;
        }
        if (!empty($this->parent)) {
            $data['parentDetails'] =  $this->parent->asJson();
        } else {
            $data['parentDetails'] =  '';
        }
        if (!empty($unique_key)) {
            $student_attendance_bus = StudentAttendanceBus::find()->where(['unique_key' => $unique_key])->andWhere(['student_id' => $this->id])->one();
            if (!empty($student_attendance_bus)) {
                $data['student_attendance_bus'] = $student_attendance_bus->asJson();
            } else {
                $data['student_attendance_bus'] =  0;
            }
        } else {
            $data['student_attendance_bus'] =  0;
        }
        $studentHasBuses = StudentHasBus::find()->where(['student_id' => $this->id])->one();
        if (!empty($studentHasBuses)) {
            $data['studentHasBuses'] = $studentHasBuses->asJson();
        } else {
            $data['studentHasBuses'] = 0;
        }
        $exams_result = ExamsResult::find()->where(['student_id' => $this->id])
            ->andWhere(['class_id' => $this->student_class_id])->andWhere(['section_id' => $this->section_id])
            ->andWhere(['exam_id' => $exsm_id])
            ->one();
        if (!empty($exams_result)) {
            $data['exams_result'] = $exams_result->asJson();
        } else {
            $data['exams_result'] = '';
        }
        return $data;
    }
    public function asJsonDairy($student_has_dairy_id = '')
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class'] = '';
        }
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }
        $data['status'] =  $this->status;
        if (!empty($this->parent)) {
            $data['parentDetails'] =  $this->parent->asJson();
        } else {
            $data['parentDetails'] =  '';
        }
        if (!empty($student_has_dairy_id)) {
            $student_dairy_details = StudentHasDairy::find()->where(['id' => $student_has_dairy_id])->one();
            if (!empty($student_dairy_details)) {
                $data['student_dairy_details'] = $student_dairy_details->asJsonDairyList();
            } else {
                $data['student_dairy_details'] = '';
            }
        } else {
            $data['student_dairy_details'] = '';
        }
        return $data;
    }
    public function DriverAsJson($session_key = '')
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        $data['student_class'] =  $this->studentClass->asJson();
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        $data['academic_year'] = $this->academicYear->asJson();
        $data['hostal_is_required'] =  $this->hostal_is_required;
        $data['bus_transport_required'] =  $this->bus_transport_required;
        $data['status'] =  $this->status;
        $data['created_on'] =  $this->created_on;
        $data['updated_on'] =  $this->updated_on;
        $data['create_user_id'] =  $this->create_user_id;
        $data['update_user_id'] =  $this->update_user_id;
        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $this->id])->one();
        if (!empty($agent_student_join)) {
            $data['amount'] = $agent_student_join->amount;
        } else {
            $data['amount'] = 0;
        }
        if (!empty($this->parent)) {
            $data['parentDetails'] =  $this->parent->asJson();
        } else {
            $data['parentDetails'] =  '';
        }
        if (!empty($session_key)) {
            $student_attendance_bus = StudentAttendanceBus::find()->where(['session_key' => $session_key])->andWhere(['student_id' => $this->id])->one();
            if (!empty($student_attendance_bus)) {
                $data['student_attendance_bus'] = $student_attendance_bus->asJson();
            } else {
                $data['student_attendance_bus'] =  0;
            }
        } else {
            $data['student_attendance_bus'] =  0;
        }
        $studentHasBuses = StudentHasBus::find()->where(['student_id' => $this->id])->one();
        if (!empty($studentHasBuses)) {
            $data['studentHasBuses'] = $studentHasBuses->asJson();
        } else {
            $data['studentHasBuses'] = 0;
        }
        return $data;
    }
    public function StudentDetailsOfBudAsJson($startDate = '', $endDate = '', $status = '')
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        $data['address'] =  $this->user->address;
        $data['student_class'] =  $this->studentClass->asJson();
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($startDate) && !empty($startDate) && isset($status)) {
            $student_attendance_bus = StudentAttendanceBus::find()
                ->Where(['student_id' => $this->id])
                ->andFilterWhere(['between', 'created_on', $startDate, $endDate])
                ->andWhere(['status' => $status])
                ->one();
            if (!empty($student_attendance_bus)) {
                $data['student_attendance_bus'] = $student_attendance_bus->asJson();
            } else {
                $data['student_attendance_bus'] =  0;
            }
        }
        $studentHasBuses = StudentHasBus::find()->where(['student_id' => $this->id])->one();
        if (!empty($studentHasBuses)) {
            $data['studentHasBuses'] = $studentHasBuses->asJson();
        } else {
            $data['studentHasBuses'] = 0;
        }
        return $data;
    }
    public function asJsonStudentClassAttendance($teacher_id = '', $subject_timetable_id = '')
    {
        $data = [];
        $data['id'] =  $this->id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $studentClassAttendances = StudentClassAttendance::find()
            ->where(['student_id' => $this->id])
            ->andWhere(['teacher_id' => $teacher_id])
            ->andWhere(['subject_timetable_id' => $subject_timetable_id])
            ->andWhere(['date' => date('Y-m-d')])
            ->one();
        if (!empty($studentClassAttendances)) {
            $data['studentClassAttendances'] = $studentClassAttendances->asJson();
        } else {
            $data['studentClassAttendances'] = [
                'id' =>  '',
                'academic_year_id' =>  '',
                'student_id' =>  $this->id,
                'teacher_details_id' =>  '',
                'subject_timetable_id' =>  '',
                'status' =>  '',
                'create_user_id' =>  '',
                'update_user_id' =>  '',
                'created_on' =>  '',
                'updated_on' =>  '',
            ];
        }
        return $data;
    }
    public function asJsonStudentClassAttendanceTeacher()
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['status'] =  $this->status;
        return $data;
    }
    public function asJsonStudentDetails()
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['status'] =  $this->status;
        $stu_avg = StudentMeritMarks::find()->where(['student_details_id' => $this->id])->all();
        $merit_avg = 0;
        if (!empty($stu_avg)) {
            $total_marks = 0;
            foreach ($stu_avg as $record) {
                $total_marks += $record->marks_scored;
            }
            $merit_avg = $total_marks / count($stu_avg);
            $data['merit_scores'] = [];
            foreach ($stu_avg as $record) {
                $data['merit_scores'][] = $record;
            }
            $data['avg'] = $merit_avg;
        }
        return $data;
    }
    public function asJsonForHostel()
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        return $data;
    }
    public function studentDetailWarden()
    {
        $data = [];
        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $libraryMember = LibraryMembers::find()->where(['member_id' => $this->user_id])->one();
        if (!empty($libraryMember)) {
            $data['is_library_member'] = true;
        } else {
            $data['is_library_member'] = false;
        }
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;


        $hostelers = Hostellers::find()->where(['student_id' => $this->user_id])->one();
        if (!empty($hostelers)) {
            $data['hostel']['name'] = $hostelers->hostel->name ?? "";
            $data['hostel']['floor'] = $hostelers->floor->name_of_floor ?? "";
            $data['hostel']['room'] = $hostelers->floor->name_of_the_room ?? "";
        } else {
            $data['hostel']['name'] = "";
            $data['hostel']['floor'] = "";
            $data['hostel']['room'] = "";
        }
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class'] = '';
        }
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }

        $data['status'] =  $this->status;
        $data['created_on'] =  $this->created_on;
        $data['updated_on'] =  $this->updated_on;
        $data['create_user_id'] =  $this->create_user_id;
        $data['update_user_id'] =  $this->update_user_id;
        $agent_student_join = AgentStudentJoin::find()->where(['student_id' => $this->id])->one();
        if (!empty($agent_student_join)) {
            $data['amount'] = $agent_student_join->amount;
        } else {
            $data['amount'] = 0;
        }
        if (!empty($this->parent)) {
            $data['parentDetails'] =  $this->parent->asJson();
        } else {
            $data['parentDetails'] =  '';
        }



        return $data;
    }

    public function studentDetailParent()
    {

        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $libraryMember = LibraryMembers::find()->where(['member_id' => $this->user_id])->one();
        if (!empty($libraryMember)) {
            $data['is_library_member'] = true;
        } else {
            $data['is_library_member'] = false;
        }
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;


        $hostelers = Hostellers::find()->where(['student_id' => $this->user_id])->one();
        if (!empty($hostelers)) {
            $data['hostel']['name'] = $hostelers->hostel->name ?? "";
            $data['hostel']['floor'] = $hostelers->room->floor->name_of_floor ?? "";
            $data['hostel']['room'] = $hostelers->room->name_of_the_room ?? "";
            $data['hostel']['warden_id'] = $hostelers->warden_id ?? "";
            $warden_name = User::find()->where(['id' => $hostelers->warden_id])->one();
            $data['hostel']['warden_name'] = $warden_name->first_name . " " . $warden_name->last_name ?? "";
            $data['hostel']['contact_no'] = $warden_name->contact_no . " " . $warden_name->contact_no ?? "";
        } else {
            $data['hostel']['is_hosteller'] = false;
            $data['hostel']['name'] = "";
            $data['hostel']['floor'] = "";
            $data['hostel']['room'] = "";
            $data['hostel']['warden_id'] = "";
            $data['hostel']['warden_name'] = "";
        }
        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->asJson();
        } else {
            $data['student_class'] = '';
        }
        $student_section =  ClassSections::find()->where(['id' => $this->section_id])->one();
        if (!empty($student_section)) {
            $data['student_section'] = $student_section->asJson();
        } else {
            $data['student_section'] = '';
        }
        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }

        $hostellersAttendance = HostellersAttandance::find()->where(['student_id' => $this->user_id])->all();

        $data['attendance'] = [];

        foreach ($hostellersAttendance as $attendance) {
            $attendanceData = [
                'id' => $attendance->id,
                'present_or_absent' => $attendance->attandance,  // Assuming 'attendance' is the correct attribute name
                'date' => $attendance->date,
            ];

            $data['attendance'][] = $attendanceData;
        }

        if (empty($data['attendance'])) {
            $defaultAttendanceData = [
                'id' => 0,
                'present_or_absent' => HostellersAttandance::NOT_MARKED,
                'date' => null, // You might want to set a default value for the date or omit it if not needed
            ];

            $data['attendance'][] = $defaultAttendanceData;
        }
        return $data;
    }
    public function asJsonForStudentMerit()
    {

        $data['student_id'] =  $this->id;
        $data['campus_id'] =  $this->campus_id;
        $data['user_id'] =  $this->user_id;
        $data['admission_number'] =  $this->admission_number;
        $data['rool_number'] =  $this->rool_number;
        $data['profile_photo'] =  $this->profile_photo;
        $data['student_name'] =  $this->student_name;
        $data['gender'] =  $this->gender;
        $data['date_of_birth'] =  $this->date_of_birth;
        $data['phone_number'] =  $this->phone_number;



        if (!empty($this->bloodGroup)) {
            $data['blood_group'] = $this->bloodGroup->title;
        } else {
            $data['blood_group'] = '';
        }
        if (!empty($this->studentClass)) {
            $data['student_class'] =  $this->studentClass->title;
        } else {
            $data['student_class'] = '';
        }

        if (!empty($this->academicYear)) {
            $data['academic_year'] = $this->academicYear->asJson();
        } else {
            $data['academic_year'] = '';
        }
        if (!empty($this->parent)) {
            $data['parentDetails']['name_of_the_father'] =  $this->parent->name_of_the_father;
            $data['parentDetails']['contact_number'] = $this->parent->contact_number;
            $data['parentDetails']['email'] =  isset($this->parent->email) ? $this->parent->email : "";
            $data['parentDetails']['current_address'] =  $this->parent->current_address;
        } else {
            $data['parentDetails'] =  '';
        }
        return $data;
    }
}
