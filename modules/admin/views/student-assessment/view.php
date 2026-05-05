<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentAssessment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Assessments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-assessment-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Assessment').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        [
            'attribute' => 'teacherDetails.name',
            'label' => Yii::t('app', 'Teacher Details'),
        ],
        'subject_timetable_id:datetime',
        [
            'attribute' => 'academicYear.title',
            'label' => Yii::t('app', 'Academic Year'),
        ],
        [
            'attribute' => 'class.title',
            'label' => Yii::t('app', 'Class'),
        ],
        [
            'attribute' => 'section.id',
            'label' => Yii::t('app', 'Section'),
        ],
        [
            'attribute' => 'subject.id',
            'label' => Yii::t('app', 'Subject'),
        ],
        'assessment:ntext',
        'submission_date',
        'document',
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
        'city',
        'fee_receipt_content',
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
        <h4>StudentClass<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentClass = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
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
            'label' => Yii::t('app', 'Campus'),
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
            'label' => Yii::t('app', 'Campus'),
        ],
        'title',
        'year_from',
        'year_to',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->academicYear,
        'attributes' => $gridColumnAcademicYears    ]);
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
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        'name',
        'profile_image',
        [
            'attribute' => 'class.title',
            'label' => Yii::t('app', 'Class'),
        ],
        [
            'attribute' => 'section.id',
            'label' => Yii::t('app', 'Section'),
        ],
        'id_number',
        'date_of_birth',
        [
            'attribute' => 'academicYear.title',
            'label' => Yii::t('app', 'Academic Year'),
        ],
        'gender',
        'blood_group_id',
        'father_name',
        'contact_number',
        'email',
        'address',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->teacherDetails,
        'attributes' => $gridColumnTeacherDetails    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>Subjects<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSubjects = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        'subject_name',
        'subject_code',
        'subject_type',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->subject,
        'attributes' => $gridColumnSubjects    ]);
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
            'label' => Yii::t('app', 'Campus'),
        ],
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
            'label' => Yii::t('app', 'Campus'),
        ],
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
<?php
if($providerStudentHasAssessment->totalCount){
    $gridColumnStudentHasAssessment = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'student.id',
                'label' => Yii::t('app', 'Student')
            ],
                        'date',
            'is_read',
            'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerStudentHasAssessment,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-has-assessment']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Student Has Assessment')),
        ],
        'export' => false,
        'columns' => $gridColumnStudentHasAssessment
    ]);
}

?>
</div>
</div>
</div>

</div>

