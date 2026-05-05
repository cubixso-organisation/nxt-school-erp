<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TestUsers */

$this->title = Yii::t('app', 'Create Test Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Test Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
