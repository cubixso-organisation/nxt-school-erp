<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveApplied */

$this->title = 'Create Staff Leave Applied';
$this->params['breadcrumbs'][] = ['label' => 'Staff Leave Applieds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-leave-applied-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
