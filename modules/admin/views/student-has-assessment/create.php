<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentHasAssessment */

$this->title = Yii::t('app', 'Create Student Has Assessment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Has Assessments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-has-assessment-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
