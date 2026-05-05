<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveApplied */

$this->title = 'Update Staff Leave Applied: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Staff Leave Applieds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-leave-applied-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
