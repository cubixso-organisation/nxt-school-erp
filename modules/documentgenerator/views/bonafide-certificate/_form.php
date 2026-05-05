<?php

use yii\helpers\Html;
   use app\modules\admin\models\User;
   use kartik\form\ActiveForm;
   use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\BonafideCertificate */
/* @var $form yii\widgets\ActiveForm */

?>
<?php
$campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
?>
<div class="bonafide-certificate-form">

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

    <?= $form->field($model, 'campus_id')->hiddenInput(['value' => $campusId])->label(false) ?>

    <?= $form->field($model, 'certificate_name')->textInput(['maxlength' => true, 'placeholder' => 'Certificate Name']) ?>

    <?= $form->field($model, 'header_left_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'header_center_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'header_right_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'body_text')->textarea(['rows' => 6]) ?>
    <span class="text-primary">[name] [dob] [present_address] [guardian] [created_at] [admission_no] [roll_no] [class] [section] [gender] [admission_date] [category] [cast] [father_name] [mother_name] [email] [phone] </span>
    <?= $form->field($model, 'footer_right_text')->textarea(['rows' => 6]) ?>

    <div class="col-md-6">
                            <?= $form->field($model, 'right_sig')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*', "id" => 'right_sig_id'],
                                'pluginOptions' => [
                                    'previewFileType' => 'image',
                                    'initialPreview' => [$model->right_sig],
                                    'initialPreviewAsData' => true,
                                    'overwriteInitial' => true,
                                    'showUpload' => false,
                                ],
                            ])->label('Left Signature (*Only Png)'); ?>
                        </div>


    <div class="col-md-6">
                    <?= $form->field($model, 'background_image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*', "id" => 'backround_image_id'],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'initialPreview' => [$model->background_image],
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => true,
                            'showUpload' => false,
                        ],
                    ])->label('Background Image (1100X850px)'); ?>
                    <?= $form->field($model, 'template_type')->dropDownList($model->getTemplateType()) ?>

                </div>

    

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="row">
                <div class="col-md-2">

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" data-toggle="modal" id="previewButton" data-target="#exampleModal">
                        Preview
                    </button>
                </div>


            </div>
  
    <?php ActiveForm::end(); ?>

</div>
