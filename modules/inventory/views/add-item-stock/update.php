<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\AddItemStock */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Add Item Stock',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Add Item Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="add-item-stock-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
