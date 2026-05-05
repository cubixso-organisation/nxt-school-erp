<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryGroups */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Salary Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-groups-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Salary Groups'.' '. Html::encode($this->title) ?></h2>
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
        'name',
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
if($providerSalaryGroupComponents->totalCount){
    $gridColumnSalaryGroupComponents = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'component.name',
                'label' => 'Component'
            ],
            'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerSalaryGroupComponents,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-salary-group-components']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Salary Group Components'),
        ],
        'export' => false,
        'columns' => $gridColumnSalaryGroupComponents
    ]);
}

?>
</div>
</div>
</div>

</div>

