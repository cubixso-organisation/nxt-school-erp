<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\NoticeBoards */

$this->title = Yii::t('app', 'Create Notice Boards');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notice Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-boards-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form_teacher_notice', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
