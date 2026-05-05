<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamStudentMarksheet */

$this->title = 'Update Exam Student Marksheet: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Exam Student Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="exam-student-marksheet-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
