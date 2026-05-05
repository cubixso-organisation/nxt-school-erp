<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DriverHasBus */

$this->title = Yii::t('app', 'Create Assign Bus Driver');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assign Bus Driver'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="driver-has-bus-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider'=>$dataProvider,
        'searchModel'=>$searchModel
    ]) ?>
    </div>
    </div>
</div>
