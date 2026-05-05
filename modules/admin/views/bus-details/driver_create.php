<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\EmployeeDetails */

$this->title = Yii::t('app', 'Create Driver');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-details-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_driver_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div> 
</div>
