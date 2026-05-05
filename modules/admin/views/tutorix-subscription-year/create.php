<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptionYear */

$this->title = Yii::t('app', 'Create Tutorix Subscription Year');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Subscription Years'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tutorix-subscription-year-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
