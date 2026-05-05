<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\TeacherClassAndSubjects */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Teacher Class And Subjects',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Class And Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="teacher-class-and-subjects-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
