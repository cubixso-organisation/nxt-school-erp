<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryGroupComponents */

$this->title = 'Update Salary Group Components: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Salary Group Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="salary-group-components-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
