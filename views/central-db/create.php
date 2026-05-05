<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CentralDb */

$this->title = Yii::t('app', 'Create Central Db');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Central Dbs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="central-db-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
