<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AdmissionEnquirie */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="admission-enquirie-form">

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

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'student_name')->textInput(['maxlength' => true, 'placeholder' => 'Student Name']) ?>

    <?= $form->field($model, 'parent_name')->textInput(['maxlength' => true, 'placeholder' => 'Parent Name']) ?>

    <?= $form->field($model, 'contact_no')->textInput(['placeholder' => 'Contact No']) ?>
    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email']) ?>

    <?= $form->field($model, 'next_class')->textInput(['maxlength' => true, 'placeholder' => 'Next Class']) ?>

    <?= $form->field($model, 'previous_class')->textInput(['maxlength' => true, 'placeholder' => 'Previous Class']) ?>

    <?= $form->field($model, 'dob')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Dob'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
