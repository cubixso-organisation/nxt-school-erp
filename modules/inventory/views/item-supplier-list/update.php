<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemSupplierList */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Item Supplier',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Supplier'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="item-supplier-list-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
