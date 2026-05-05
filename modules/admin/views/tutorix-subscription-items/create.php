<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptionItems */

$this->title = Yii::t('app', 'Create Tutorix Subscription Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Subscription Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tutorix-subscription-items-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
