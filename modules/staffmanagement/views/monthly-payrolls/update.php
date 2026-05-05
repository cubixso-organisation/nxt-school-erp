<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\MonthlyPayrolls */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Monthly Payrolls',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monthly Payrolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="monthly-payrolls-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
