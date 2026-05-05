<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\MarksDivition */

$this->title = Yii::t('app', 'Create Marks Divition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Marks Divitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-divition-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
