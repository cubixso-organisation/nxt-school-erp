<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryComponents */

$this->title = 'Update Salary Components: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Salary Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="salary-components-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
