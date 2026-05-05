<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RazorpayLinkedAccount */

$this->title = Yii::t('app', 'Create Payment Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Create Payment Account'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="razorpay-linked-account-create">
    <div class="card">
        <div class="card-body">
            <!-- <h1><?= Html::encode($this->title) ?></h1> -->

            <?= $this->render('_form', [
                'model' => $model,
                'jsonData' => $jsonData,

            ]) ?>
        </div>
    </div>
</div>