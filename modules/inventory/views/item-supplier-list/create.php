<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemSupplierList */

$this->title = Yii::t('app', 'Create Item Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Supplier'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-supplier-list-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
