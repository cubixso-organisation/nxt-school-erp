<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\LoginLogs */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Login Logs',
]) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Login Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="login-logs-update">
<div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
