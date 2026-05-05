<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\IssueReturnInventory */

$this->title = Yii::t('app', 'Create Issue Return Inventory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Issue Return Inventories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-return-inventory-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
