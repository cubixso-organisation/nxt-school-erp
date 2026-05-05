<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CampusTiming */

$this->title = Yii::t('app', 'Create Campus Timing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campus Timings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campus-timing-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
