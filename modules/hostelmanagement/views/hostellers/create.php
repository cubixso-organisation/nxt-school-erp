<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Hostellers */

$this->title = Yii::t('app', 'Create Hostellers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hostellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hostellers-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
