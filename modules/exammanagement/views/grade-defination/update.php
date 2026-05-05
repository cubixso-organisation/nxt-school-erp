<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\GradeDefination */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Grade Defination',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grade Definations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="grade-defination-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
