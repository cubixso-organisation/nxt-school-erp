<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\search\HostlerAttendanceSettingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-hostler-attendance-settings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>

    <?= $form->field($model, 'daily_attendance_count')->textInput(['placeholder' => 'Daily Attendance Count']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
