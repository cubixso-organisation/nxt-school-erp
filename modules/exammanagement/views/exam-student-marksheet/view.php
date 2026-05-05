<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamStudentMarksheet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Exam Student Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-student-marksheet-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Exam Student Marksheet'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin){ ?>
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
            'attribute' => 'user.username',
            'label' => 'User',
        ],
        [
            'attribute' => 'student.id',
            'label' => 'Student',
        ],
        [
            'attribute' => 'session.title',
            'label' => 'Session',
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
            'attribute' => 'exam.id',
            'label' => 'Exam',
        ],
        'total_marks',
        'total_percentage',
        'marks_type',
        'total_grade',
        'total_cgpa',
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
        <h4>StudentClass<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentClass = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
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
        'campus_id',
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
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->user,
        'attributes' => $gridColumnUser    ]);
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
        [
            'attribute' => 'user.username',
            'label' => 'User',
        ],
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
        <h4>Exams<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnExams = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
        'name_of_exam',
        'marks_type',
        'total_percentage_or_gpa',
        'session_id',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->exam,
        'attributes' => $gridColumnExams    ]);
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
        'campus_id',
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
</div>

