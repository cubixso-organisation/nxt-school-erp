<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamSchedules */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exam Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-schedules-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Exam Schedules').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'session.title',
            'label' => Yii::t('app', 'Session'),
        ],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        [
            'attribute' => 'exam.id',
            'label' => Yii::t('app', 'Exam'),
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
        'max_marks',
        'min_marks',
        'exam_date',
        'exam_duration',
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
        <h4>Exams<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnExams = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
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
</div>

