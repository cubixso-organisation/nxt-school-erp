<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemCategory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Item Category',
]) . ' ' . $model->item_category;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_category, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="item-category-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
