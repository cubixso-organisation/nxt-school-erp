<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ClassRooms */

$this->title = Yii::t('app', 'Create Class Rooms');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Class Rooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-rooms-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
