<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemCategory */

$this->title = Yii::t('app', 'Create Item Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-category-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
