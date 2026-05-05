<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentAttendanceBus */

$this->title = Yii::t('app', 'Create Student Attendance Bus');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Attendance Buses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-attendance-bus-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
