<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\TeacherClassAndSubjects */

$this->title = Yii::t('app', 'Create Teacher Class And Subjects');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Class And Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-class-and-subjects-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
        'out'=>$out
    ]) ?>
    </div>
    </div>
</div>
