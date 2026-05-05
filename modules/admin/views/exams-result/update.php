<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ExamsResult */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Exams Result',
]) . ' ' . $model->exams_result_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exams Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exams_result_id, 'url' => ['view', 'id' => $model->exams_result_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="exams-result-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
