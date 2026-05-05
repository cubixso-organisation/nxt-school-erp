<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\TimetableErrorReportsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-timetable-error-reports-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'subject_timetable_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Subject timetable')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true, 'placeholder' => 'Class']) ?>

    <?= $form->field($model, 'room')->textInput(['maxlength' => true, 'placeholder' => 'Room']) ?>

    <?= $form->field($model, 'section')->textInput(['maxlength' => true, 'placeholder' => 'Section']) ?>

    <?php /* echo $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Subject']) */ ?>

    <?php /* echo $form->field($model, 'teacher')->textInput(['maxlength' => true, 'placeholder' => 'Teacher']) */ ?>

    <?php /* echo $form->field($model, 'time_from')->textInput(['maxlength' => true, 'placeholder' => 'Time From']) */ ?>

    <?php /* echo $form->field($model, 'time_to')->textInput(['maxlength' => true, 'placeholder' => 'Time To']) */ ?>

    <?php /* echo $form->field($model, 'error_type')->textInput(['maxlength' => true, 'placeholder' => 'Error Type']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
