<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\ChildMerit */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Child Merit',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Child Merits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="child-merit-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
