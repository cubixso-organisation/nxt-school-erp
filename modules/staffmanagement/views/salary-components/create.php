<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryComponents */

$this->title = 'Create Salary Components';
$this->params['breadcrumbs'][] = ['label' => 'Salary Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-components-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
