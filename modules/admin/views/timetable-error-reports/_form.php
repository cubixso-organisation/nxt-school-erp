<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TimetableErrorReports */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="timetable-error-reports-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>
  
    <?= $form->errorSummary($model); ?>
  
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

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Subject']) ?>

    <?= $form->field($model, 'teacher')->textInput(['maxlength' => true, 'placeholder' => 'Teacher']) ?>

    <?= $form->field($model, 'time_from')->textInput(['maxlength' => true, 'placeholder' => 'Time From']) ?>

    <?= $form->field($model, 'time_to')->textInput(['maxlength' => true, 'placeholder' => 'Time To']) ?>

    <?= $form->field($model, 'error_type')->textInput(['maxlength' => true, 'placeholder' => 'Error Type']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
