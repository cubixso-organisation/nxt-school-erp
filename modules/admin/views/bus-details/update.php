<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusDetails */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Bus Details',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bus Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bus-details-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
