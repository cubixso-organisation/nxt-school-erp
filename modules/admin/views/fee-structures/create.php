<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\FeeStructures */

$this->title = Yii::t('app', 'Create Fee Structures');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fee Structures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fee-structures-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel'=>$searchModel,
        'dataProvider'=>$dataProvider
    ]) ?>
    </div>
    </div>
</div>
