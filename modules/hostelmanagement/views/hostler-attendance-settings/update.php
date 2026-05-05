<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\HostlerAttendanceSettings */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Hostler Attendance Settings',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hostler Attendance Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hostler-attendance-settings-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
