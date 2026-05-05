<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryItems */

$this->title = Yii::t('app', 'Create Inventory Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-items-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
