<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ClassSections */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Class Sections',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Class Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="class-sections-update">
<div class="card">
       <div class="card-body">

    <?= $this->render('_form_student_class', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
