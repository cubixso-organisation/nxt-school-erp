<?php

use kartik\widgets\FileInput;

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffDetails */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="staff-details-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>
    <div class="row grid-margin stretch-card">
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name'])  ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'designation_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDesignations::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
                                                                'options' => ['placeholder' => 'Choose Staff designations'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?php
                                                            // echo   $form->field($model, 'payroll_id')->widget(\kartik\widgets\Select2::classname(), [
                                                            //     'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\Payroll::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
                                                            //     'options' => ['placeholder' => 'Choose Payroll'],
                                                            //     'pluginOptions' => [
                                                            //         'allowClear' => true
                                                            //     ],
                                                            // ]);  
                                                            ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true, 'placeholder' => 'Contact No'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'date_of_birth')->widget(\kartik\datecontrol\DateControl::classname(), [
                                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                                                                'saveFormat' => 'php:Y-m-d',
                                                                'ajaxConversion' => true,
                                                                'options' => [
                                                                    'pluginOptions' => [
                                                                        'placeholder' => 'Choose Date Of Birth',
                                                                        'autoclose' => true
                                                                    ]
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])  ?> </div>




        <div class='col-lg-6'> <?php
                                echo $form->field($model, 'aadhar_card')->widget(FileInput::classname(), [
                                    'id' => 'aadhar_card',
                                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'previewFileType' => 'aadhar_card', 'initialPreview' => [
                                            $model->aadhar_card
                                        ],
                                        'initialPreviewAsData' => true,

                                        'overwriteInitial' => true,

                                        'showUpload' => false,
                                    ]
                                ]);

                                ?></div>
        <div class='col-lg-6'> <?php
                                echo $form->field($model, 'pan_card')->widget(FileInput::classname(), [
                                    'id' => 'pan_card',
                                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'previewFileType' => 'pan_card', 'initialPreview' => [
                                            $model->pan_card
                                        ],
                                        'initialPreviewAsData' => true,

                                        'overwriteInitial' => true,

                                        'showUpload' => false,
                                    ]
                                ]);

                                ?></div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>

            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>