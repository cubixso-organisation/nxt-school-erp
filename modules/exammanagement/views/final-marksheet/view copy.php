<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\FinalMarksheet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Final Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="final-marksheet-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Final Marksheet'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
             <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
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
            'label' => 'Student',
        ],
        [
            'attribute' => 'studentUser.username',
            'label' => 'Student User',
        ],
        [
            'attribute' => 'class.title',
            'label' => 'Class',
        ],
        [
            'attribute' => 'section.id',
            'label' => 'Section',
        ],
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
        [
            'attribute' => 'session.title',
            'label' => 'Session',
        ],
        'marksheet_url:ntext',
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
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
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
        [
            'attribute' => 'section.id',
            'label' => 'Section',
        ],
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
        <h4>Campus<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnCampus = [
        ['attribute' => 'id', 'visible' => false],
        'institute_id',
        'educational_institution_type_id',
        'name_of_the_educational_Institution',
        'user_id',
        'country_id',
        'state_id',
        'district_id',
        'pincode',
        'address',
        'campus_code',
        'registration_number',
        'registration_document',
        'name_of_the_authorized',
        'designation_of_the_authorized',
        'contact_number_of_the_authorized',
        'name_of_the_contact',
        'designation_of_the_contact',
        'contact_number_of_the_contact',
        'email_id_of_the_authorized',
        'aadhaar_of_the_authorized',
        'lat',
        'lng',
        'coordinates',
        'radius',
        'city',
        'fee_receipt_content',
        'academic_year',
        'status',
        'school_logo',
    ];
    echo DetailView::widget([
        'model' => $model->campus,
        'attributes' => $gridColumnCampus    ]);
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
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
        'admission_number',
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
        'model' => $model->studentUser,
        'attributes' => $gridColumnUser    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>AcademicYears<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAcademicYears = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
        'title',
        'year_from',
        'year_to',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->session,
        'attributes' => $gridColumnAcademicYears    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>StudentClass<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentClass = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
        'title',
        'is_agent',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->class,
        'attributes' => $gridColumnStudentClass    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>ClassSections<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnClassSections = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => 'Campus',
        ],
        'student_class_id',
        'section_name',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->section,
        'attributes' => $gridColumnClassSections    ]);
    ?>
    </div>
    </div>
</div>

