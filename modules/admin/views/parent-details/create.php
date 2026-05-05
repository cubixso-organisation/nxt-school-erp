<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ParentDetails */

$this->title = Yii::t('app', 'Create Parent Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parent Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parent-details-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
