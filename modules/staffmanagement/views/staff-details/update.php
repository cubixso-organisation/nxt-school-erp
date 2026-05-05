<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffDetails */

$this->title = 'Update Staff Details: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Staff Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-details-update">
    <div class="card">
        <div class="card-body">
            <!-- <h1><?= Html::encode($this->title) ?></h1> -->

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>