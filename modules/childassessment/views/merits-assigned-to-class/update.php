<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\MeritsAssignedToClass */

$this->title = 'Update Merits Assigned To Class: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Merits Assigned To Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="merits-assigned-to-class-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
