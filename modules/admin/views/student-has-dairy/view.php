<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentHasDairy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Has Dairies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-has-dairy-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Has Dairy').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'student.id',
            'label' => Yii::t('app', 'Student'),
        ],
        [
            'attribute' => 'studentDairy.id',
            'label' => Yii::t('app', 'Student Dairy'),
        ],
        'is_read',
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
    ];
    echo DetailView::widget([
        'model' => $model->student,
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
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->createUser,
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
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->updateUser,
        'attributes' => $gridColumnUser    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>StudentDairy<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentDairy = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
        'teacher_details_id',
        'subject_timetable_id',
        'academic_year_id',
        'class_id',
        'section_id',
        'subject_id',
        'dairy',
        'remarks',
        'submission_date',
        'document',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->studentDairy,
        'attributes' => $gridColumnStudentDairy    ]);
    ?>
    </div>
    </div>
</div>

