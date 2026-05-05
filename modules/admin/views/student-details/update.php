<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDetails */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Student Details',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="student-details-update">
<div class="card">
       <div class="card-body">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
