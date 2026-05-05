<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\Studentcertificates */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Studentcertificates',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Studentcertificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="studentcertificates-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
