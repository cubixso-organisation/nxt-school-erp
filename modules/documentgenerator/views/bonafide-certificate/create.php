<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\BonafideCertificate */

$this->title = Yii::t('app', 'Create Bonafide Certificate');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bonafide Certificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonafide-certificate-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
