<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveTypes */

$this->title = 'Update Staff Leave Types: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Staff Leave Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-leave-types-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
