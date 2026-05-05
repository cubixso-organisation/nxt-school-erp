<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\FcmNotification */

$this->title = Yii::t('app', 'Create Fcm Notification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fcm Notification'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fcm-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
