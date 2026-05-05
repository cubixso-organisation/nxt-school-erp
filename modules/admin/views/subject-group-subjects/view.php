<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubjectGroupSubjects */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subject Group Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-group-subjects-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Subject Group Subjects').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'subjectGroup.id',
            'label' => Yii::t('app', 'Subject Group'),
        ],
        [
            'attribute' => 'subject.id',
            'label' => Yii::t('app', 'Subject'),
        ],
        [
            'attribute' => 'academicYear.title',
            'label' => Yii::t('app', 'Academic Year'),
        ],
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
        'model' => $model->academicYear,
        'attributes' => $gridColumnAcademicYears    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>SubjectGroups<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSubjectGroups = [
        ['attribute' => 'id', 'visible' => false],
        'campus_id',
        'subject_group_name',
        'description',
        [
            'attribute' => 'academicYear.title',
            'label' => Yii::t('app', 'Academic Year'),
        ],
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->subjectGroup,
        'attributes' => $gridColumnSubjectGroups    ]);
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
        'campus_id',
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
</div>

