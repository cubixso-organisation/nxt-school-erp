<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Floor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Floors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Floor').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'hostel.name',
            'label' => Yii::t('app', 'Hostel'),
        ],
        'campus_id',
        'name_of_floor',
        'no_of_rooms',
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
<?php
if($providerRooms->totalCount){
    $gridColumnRooms = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            'hostel_id',
                        'block',
            'name_of_the_room',
            'no_of_beds',
            'type',
            'status',
            'create_user_id',
            'update_user_id',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerRooms,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-rooms']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Rooms')),
        ],
        'export' => false,
        'columns' => $gridColumnRooms
    ]);
}

?>
</div>
</div>
</div>

</div>

