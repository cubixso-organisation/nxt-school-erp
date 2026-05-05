<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TestUsers */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Test Users',
]) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Test Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="test-users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
