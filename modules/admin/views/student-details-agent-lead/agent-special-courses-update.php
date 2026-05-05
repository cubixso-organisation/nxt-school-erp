<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SpecialCourses */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Special Courses',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Special Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="special-courses-update">
<div class="card">
       <div class="card-body">

    <?= $this->render('_special_courcess_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
  