<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AssignFeeToStudent */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assign Fee To Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-fee-to-student-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Assign Fee To Student').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'student.id',
            'label' => Yii::t('app', 'Student'),
        ],
        [
            'attribute' => 'feeStructures.id',
            'label' => Yii::t('app', 'Fee Structures'),
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
        <h4>StudentDetails<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnStudentDetails = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        'user_id',
        'admission_number',
        'profile_photo',
        'student_name',
        'gender',
        'date_of_birth',
        'name_of_the_parent',
        'phone_number',
        'verified_phone',
        'previous_school_name',
        'previous_school_address',
        'student_class_id',
        'hostal_is_required',
        'bus_transport_required',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->student,
        'attributes' => $gridColumnStudentDetails    ]);
    ?>
    </div>
    </div>

    
</div>

