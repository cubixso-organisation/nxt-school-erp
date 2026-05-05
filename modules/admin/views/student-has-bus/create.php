<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentHasBus */

$this->title = Yii::t('app', 'Create Assign Student To Bus');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Has Buses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-has-bus-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
