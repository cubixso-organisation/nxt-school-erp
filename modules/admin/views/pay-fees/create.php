<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PayFees */

$this->title = Yii::t('app', 'Create Pay Fees');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pay Fees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-fees-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
