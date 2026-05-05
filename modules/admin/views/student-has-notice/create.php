<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentHasNotice */

$this->title = Yii::t('app', 'Create Student Has Notice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Has Notices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-has-notice-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
