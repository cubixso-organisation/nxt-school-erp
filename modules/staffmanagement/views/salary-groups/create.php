<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryGroups */

$this->title = 'Create Salary Groups';
$this->params['breadcrumbs'][] = ['label' => 'Salary Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-groups-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
