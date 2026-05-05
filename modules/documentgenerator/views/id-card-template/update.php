<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\IdCardTemplate */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Id Card Template',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Id Card Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="id-card-template-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
