<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryGroupComponents */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Salary Group Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-group-components-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Salary Group Components'.' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'group.name',
            'label' => 'Group',
        ],
        [
            'attribute' => 'component.name',
            'label' => 'Component',
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
        <h4>SalaryGroups<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSalaryGroups = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->group,
        'attributes' => $gridColumnSalaryGroups    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>SalaryComponents<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSalaryComponents = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        'component_type',
        'value_type',
        'component_value_monthly',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->component,
        'attributes' => $gridColumnSalaryComponents    ]);
    ?>
    </div>
    </div>
</div>

