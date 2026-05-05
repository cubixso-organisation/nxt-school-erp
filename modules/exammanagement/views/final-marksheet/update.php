<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\FinalMarksheet */

$this->title = 'Update Final Marksheet: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Final Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="final-marksheet-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
