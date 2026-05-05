<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\AddItemStock */

$this->title = Yii::t('app', 'Create Add Item Stock');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Add Item Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="add-item-stock-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
