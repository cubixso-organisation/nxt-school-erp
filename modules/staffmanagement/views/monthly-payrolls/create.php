<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\MonthlyPayrolls */

$this->title = Yii::t('app', 'Create Monthly Payrolls');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monthly Payrolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monthly-payrolls-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
