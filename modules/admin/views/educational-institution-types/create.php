<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\EducationalInstitutionTypes */

$this->title = Yii::t('app', 'Create Educational Institution Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Educational Institution Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="educational-institution-types-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
