<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptionItems */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tutorix Subscription Items',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Subscription Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', ]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tutorix-subscription-items-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
