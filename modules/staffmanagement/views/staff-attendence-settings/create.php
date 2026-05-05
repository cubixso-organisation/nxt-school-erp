<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffAttendenceSettings */

$this->title = 'Create Staff Attendence Settings';
$this->params['breadcrumbs'][] = ['label' => 'Staff Attendence Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-attendence-settings-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
