<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\WardenAttandance */

$this->title = Yii::t('app', 'Create Warden Attandance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Warden Attandances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warden-attandance-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
