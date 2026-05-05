<?php

use app\models\User;
use app\modules\exammanagement\models\base\MarksheetSetting;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\MarksheetSetting */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="marksheet-setting-form">

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

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none', 'value' => (new User())->getCampusId()]); ?>

    <div class="row">

        <div class="col-6">
            <?php
            echo $form->field($model, 'marksheet_header_image')->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => ['image/*']],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [
                        $model->marksheet_header_image
                    ],
                    'initialPreviewAsData' => true,

                    'overwriteInitial' => true,

                    'showUpload' => false,
                ]
            ]);


            ?>

        </div>
        <div class="col-6">

            <?php
            echo $form->field($model, 'principal_signature')->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => ['image/*']],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [
                        $model->principal_signature
                    ],
                    'initialPreviewAsData' => true,

                    'overwriteInitial' => true,

                    'showUpload' => false,
                ]
            ]);


            ?>
        </div>

    </div>



    <?= $form->field($model, 'status')->dropDownList((new MarksheetSetting())->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>