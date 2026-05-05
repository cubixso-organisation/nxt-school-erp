<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\BonafideCertificate */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Bonafide Certificate',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bonafide Certificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bonafide-certificate-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
