<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Hostels */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="hostels-form">

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
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?php
                                                            if (!Yii::$app->user->isGuest) {
                                                                if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                                                                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
                                                                } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                                                                    // Your code for the Chef Warden condition goes here
                                                                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
                                                                }
                                                            }
                                                            ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])  ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'area')->textInput(['maxlength' => true, 'placeholder' => 'Area'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address'])  ?> </div>


        <div class='col-lg-6'> <?= $form->field($model, 'type_id')->dropDownList($model->getTypeOptions())  ?> </div>
        <div class='col-lg-6'> <?php
                                echo $form->field($model, 'image_file')->widget(FileInput::classname(), [
                                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'previewFileType' => 'image_file', 'initialPreview' => [
                                            $model->image_file
                                        ],
                                        'initialPreviewAsData' => true,

                                        'overwriteInitial' => true,

                                        'showUpload' => false,
                                    ]
                                ]);

                                ?></div>

        <div class='col-lg-6'> <?php
                                echo $form->field($model, 'mess_menu')->widget(FileInput::classname(), [
                                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'previewFileType' => 'mess_menu', 'initialPreview' => [
                                            $model->mess_menu
                                        ],
                                        'initialPreviewAsData' => true,

                                        'overwriteInitial' => true,

                                        'showUpload' => false,
                                    ]
                                ]);

                                ?></div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

    </div> <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>