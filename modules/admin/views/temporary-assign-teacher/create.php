<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TemporaryAssignTeacher */

$this->title = Yii::t('app', 'Create Temporary Assign Teacher');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Temporary Assign Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="temporary-assign-teacher-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
