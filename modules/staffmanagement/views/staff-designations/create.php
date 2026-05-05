<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffDesignations */

$this->title = 'Create Staff Designations';
$this->params['breadcrumbs'][] = ['label' => 'Staff Designations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-designations-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
