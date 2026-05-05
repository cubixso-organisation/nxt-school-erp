<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffSalary */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Staff Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-salary-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Staff Salary').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'staff.name',
            'label' => Yii::t('app', 'Staff'),
        ],
        'ctc',
        'basic_salary_type',
        'basic_salary_value',
        'earnings:ntext',
        'ctc_monthly',
        'ctc_yearly',
        'total_deduction_monthly',
        'total_deduction_yearly',
        'salary_group_id',
        'status',
        'create_user_id',
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
        <h4>StaffDetails<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStaffDetails = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        'user_id',
        'designation_id',
        'payroll_id',
        'contact_no',
        'date_of_birth',
        'gender',
        'email',
        'aadhar_card',
        'pan_card',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->staff,
        'attributes' => $gridColumnStaffDetails    ]);
    ?>
    </div>
    </div>
</div>

