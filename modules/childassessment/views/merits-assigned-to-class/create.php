<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\MeritsAssignedToClass */

$this->title = 'Create Merits Assigned To Class';
$this->params['breadcrumbs'][] = ['label' => 'Merits Assigned To Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merits-assigned-to-class-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
