<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AttendanceTimeTables */

$this->title = Yii::t('app', 'Create Attendance Time Tables');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attendance Time Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-time-tables-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
