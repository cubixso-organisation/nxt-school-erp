<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CampusHasUsers */

$this->title = Yii::t('app', 'Create Campus Has Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campus Has Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campus-has-users-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
