<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Institutes */

$this->title = Yii::t('app', 'Create Institutes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Institutes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institutes-create">
<div class="card">
       <div class="card-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'invalidEmail'=>!empty($invalidEmail)??$invalidEmail
    ]) ?>

       </div>
</div>
</div>
