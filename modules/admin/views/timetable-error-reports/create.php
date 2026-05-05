<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TimetableErrorReports */

$this->title = Yii::t('app', 'Create Timetable Error Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Timetable Error Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-error-reports-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
