<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamStudentMarksheet */

$this->title = 'Create Exam Student Marksheet';
$this->params['breadcrumbs'][] = ['label' => 'Exam Student Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-student-marksheet-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
