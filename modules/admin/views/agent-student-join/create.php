<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AgentStudentJoin */

$this->title = Yii::t('app', 'Create Agent Student Join');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agent Student Joins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-student-join-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
