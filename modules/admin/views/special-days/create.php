<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SpecialDays */

$this->title = Yii::t('app', 'Create Special Days');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Special Days'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-days-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
