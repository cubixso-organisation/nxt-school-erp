<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentNoticeBoards */

$this->title = Yii::t('app', 'Create Student Notice Boards');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Notice Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-notice-boards-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
