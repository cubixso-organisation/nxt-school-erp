<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\GradeDefination */

$this->title = Yii::t('app', 'Create Grade Defination');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grade Definations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-defination-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
