<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserOtp */

$this->title = Yii::t('app', 'Create User Otp');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Otps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-otp-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
