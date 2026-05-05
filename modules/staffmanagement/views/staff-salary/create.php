<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffSalary */

$this->title = Yii::t('app', 'Create Staff Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Staff Salaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-salary-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
