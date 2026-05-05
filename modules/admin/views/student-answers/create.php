<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentAnswers */

$this->title = 'Create Student Answers';
$this->params['breadcrumbs'][] = ['label' => 'Student Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-answers-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
