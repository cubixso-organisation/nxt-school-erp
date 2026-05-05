<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffDesignations */

$this->title = 'Update Staff Designations: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Staff Designations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-designations-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
