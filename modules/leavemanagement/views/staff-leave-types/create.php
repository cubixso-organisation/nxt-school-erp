<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveTypes */

$this->title = 'Create Staff Leave Types';
$this->params['breadcrumbs'][] = ['label' => 'Staff Leave Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-leave-types-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
