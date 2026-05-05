<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryGroupComponents */

$this->title = 'Create Salary Group Components';
$this->params['breadcrumbs'][] = ['label' => 'Salary Group Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-group-components-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
