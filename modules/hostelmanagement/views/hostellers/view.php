<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Hostellers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hostellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hostellers-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Hostellers').' '. Html::encode($this->title) ?></h2>
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
            'value' => function($model){                   
                return $model->student->student_name;                   
            },
        ],
        [
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
            'value' => function($model){                   
                return $model->campus->name_of_the_educational_Institution;                   
            },
        ],
        [
            'attribute' => 'hostel.name',
            'label' => Yii::t('app', 'Hostel'),
        ],
        'joining_date',
        // 'bill_date',
        // 'next_bill_date',
        // 'sty_type',
        // 'advance_payment',
        // 'fees',
        // 'room_id',
        [
            'attribute' => 'room_id',
            'label' => Yii::t('app', 'Room'),
            'value' => function($model){                   
                return $model->room->name_of_the_room;                   
            },
        ],
        'address',
        // 'aadhar_number',
        // 'photo',
        // 'aadhar_front',
        // 'aadhar_back',
        // 'application_form_file',
        // 'leave_of_date',
        // 'leave_month',
        // 'is_all_items_checked',
        // 'is_balance_amount_paid',
        // 'status',
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Status'),
            'format' => 'raw',
            'value' => function($model){                   
                return $model->getStateOptionsBadges();                   
            },
        ],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
</div>
</div>
    </div>



</div>

