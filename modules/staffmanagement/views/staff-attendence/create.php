<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffAttendence */

$this->title = 'Create Staff Attendence';
$this->params['breadcrumbs'][] = ['label' => 'Staff Attendences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-attendence-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
