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
        'tooltipStyleFeedback' => true,
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Template Name']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['placeholder' => 'Campus', 'value' => (new Campus())->getCampusId(), 'style' => 'display:none']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
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
        </div>

        <div class="col-md-6">
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
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
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
