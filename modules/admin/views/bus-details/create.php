<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusDetails */

$this->title = Yii::t('app', 'Create Bus Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bus Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-details-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
