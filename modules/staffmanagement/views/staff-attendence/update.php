<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffAttendence */

$this->title = 'Update Staff Attendence: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Staff Attendences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-attendence-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
