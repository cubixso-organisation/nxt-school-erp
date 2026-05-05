<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\WardenToHostel */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Warden To Hostel',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Warden To Hostels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="warden-to-hostel-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
