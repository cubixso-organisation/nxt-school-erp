<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\Studentcertificates */

$this->title = Yii::t('app', 'Create Studentcertificates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Studentcertificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="studentcertificates-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
