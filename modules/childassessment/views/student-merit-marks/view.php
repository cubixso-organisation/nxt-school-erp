<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\StudentMeritMarks */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Merit Marks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-merit-marks-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Merit Marks').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
             <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>  
             <?php  } ?>
        </div>
    </div>
    </div>
    </div>
    <div class="card">
       <div class="card-body">

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'childMerit.name',
            'label' => Yii::t('app', 'Child Merit'),
        ],
        [
            'attribute' => 'studentDetails.id',
            'label' => Yii::t('app', 'Student Details'),
        ],
        [
            'attribute' => 'teacherDetails.name',
            'label' => Yii::t('app', 'Teacher Details'),
        ],
        'marks_scored',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
</div>
</div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>ChildMerit<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnChildMerit = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
        'name',
        'description',
        'max_marks',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->childMerit,
        'attributes' => $gridColumnChildMerit    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>StudentDetails<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentDetails = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
        'user_id',
        'parent_id',
        'admission_number',
        'rool_number',
        'profile_photo',
        'student_name',
        'gender',
        'date_of_birth',
        'category',
        'religion',
        'caste',
        'phone_number',
        'student_class_id',
        'section_id',
        'academic_year',
        'academic_year_id',
        'hostal_is_required',
        'bus_transport_required',
        'email',
        'admission_date',
        'blood_group_id',
        'student_house',
        'height',
        'weight',
        'current_address',
        'permanent_address',
        'national_Identification_number',
        'mother_tongue',
        'identification_marks',
        'previous_school',
        'old_admission_number',
        'status',
        'create_user_id',
        'update_user_id',
    ];
    echo DetailView::widget([
        'model' => $model->studentDetails,
        'attributes' => $gridColumnStudentDetails    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'email',
        'first_name',
        'last_name',
        'contact_no',
        'alternative_contact',
        'date_of_birth',
        'gender',
        'description',
        'address',
        'profile_image',
        'user_role',
        'oauth_client_user_id',
        'oauth_client',
        'access_token',
        'device_token',
        'device_type',
        'status',
        'is_active_wa_updates',
        'city_id',
        'online_status',
        'campus_id',
        'admission_number',
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'create_user_id',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->createdUser,
        'attributes' => $gridColumnUser    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'email',
        'first_name',
        'last_name',
        'contact_no',
        'alternative_contact',
        'date_of_birth',
        'gender',
        'description',
        'address',
        'profile_image',
        'user_role',
        'oauth_client_user_id',
        'oauth_client',
        'access_token',
        'device_token',
        'device_type',
        'status',
        'is_active_wa_updates',
        'city_id',
        'online_status',
        'campus_id',
        'admission_number',
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'create_user_id',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->updatedUser,
        'attributes' => $gridColumnUser    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>TeacherDetails<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnTeacherDetails = [
        ['attribute' => 'id', 'visible' => false],
        'user_id',
        'campus_id',
        'name',
        'profile_image',
        'class_id',
        'section_id',
        'id_number',
        'date_of_birth',
        'academic_year_id',
        'gender',
        'blood_group_id',
        'father_name',
        'contact_number',
        'email',
        'address',
        'status',
        'create_user_id',
        'update_user_id',
    ];
    echo DetailView::widget([
        'model' => $model->teacherDetails,
        'attributes' => $gridColumnTeacherDetails    ]);
    ?>
    </div>
    </div>
</div>

