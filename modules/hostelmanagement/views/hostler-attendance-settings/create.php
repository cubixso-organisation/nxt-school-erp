<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\HostlerAttendanceSettings */

$this->title = Yii::t('app', 'Create Hostler Attendance Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hostler Attendance Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hostler-attendance-settings-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
