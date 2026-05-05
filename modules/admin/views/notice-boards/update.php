<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\NoticeBoards */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Notice Boards',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notice Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="notice-boards-update">
    <div class="card">
        <div class="card-body">
            <?php if ($model->student_id == null || $model->teacher_id == null) : ?>
                <!-- Render Form 1 -->
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            <?php elseif ($model->student_id == null) : ?>
                <!-- Render Form 2 -->
                <?= $this->render('_form_teacher_notice', [
                    'model' => $model,
                ]) ?>
            <?php else : ?>
                <!-- Render Form 3 -->
                <?= $this->render('_form_student_notice', [
                    'model' => $model,
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>