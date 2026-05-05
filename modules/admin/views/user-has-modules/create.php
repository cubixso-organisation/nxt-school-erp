<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserHasModules */

$this->title = Yii::t('app', 'Create User Has Modules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Has Modules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-has-modules-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
