<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\ChildMerit */

$this->title = Yii::t('app', 'Create Child Merit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Child Merits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-merit-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
