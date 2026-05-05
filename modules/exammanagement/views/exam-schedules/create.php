<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamSchedules */

$this->title = Yii::t('app', 'Create Exam Schedules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exam Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-schedules-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
