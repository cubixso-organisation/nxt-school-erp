<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TeacherAttenddence */

$this->title = Yii::t('app', 'Create Teacher Attenddence');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Attenddences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-attenddence-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
