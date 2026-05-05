<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\WardenToHostel */

$this->title = Yii::t('app', 'Create Warden To Hostel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Warden To Hostels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warden-to-hostel-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
