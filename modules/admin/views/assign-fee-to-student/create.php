<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AssignFeeToStudent */

$this->title = Yii::t('app', 'Create Assign Fee To Student');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assign Fee To Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-fee-to-student-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
