<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DaycareAttendance */

$this->title = Yii::t('app', 'Create Daycare Attendance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Daycare Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daycare-attendance-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
