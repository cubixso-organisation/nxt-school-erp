<?php

use app\modules\admin\models\Campus;
use kartik\file\FileInput;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\IdCardTemplate */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="id-card-template-form">

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

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Template Name']) ?>

    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['placeholder' => 'Campus', 'value' => (new Campus())->getCampusId(), 'style' => 'display:none']) ?>

    <?= $form->field($model, 'school_logo')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', "id" => 'school_logo'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'initialPreview' => [$model->school_logo],
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'showUpload' => false,
        ],
    ])->label('School Logo (*Only Png)'); ?>

    <?= $form->field($model, 'signature')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', "id" => 'signature'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'initialPreview' => [$model->signature],
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'showUpload' => false,
        ],
    ])->label('Signature (*Only Png)'); ?>
    <?= $form->field($model, 'front_background_image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', "id" => 'front_background_image'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'initialPreview' => [$model->front_background_image],
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'showUpload' => false,
        ],
    ])->label('Background Image (*Only Png)'); ?>

    <!-- <?= $form->field($model, 'back_background_image')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', "id" => 'back_background_image'],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [$model->back_background_image],
                    'initialPreviewAsData' => true,
                    'overwriteInitial' => true,
                    'showUpload' => false,
                ],
            ])->label('Back Side Background Image (*Only Png)'); ?> -->

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>