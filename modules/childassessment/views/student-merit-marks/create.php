<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\StudentMeritMarks */

$this->title = Yii::t('app', 'Create Student Merit Marks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Merit Marks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-merit-marks-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
