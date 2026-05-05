<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveTypes */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="staff-leave-types-form">

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

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title'])  ?> </div>

        <!-- <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'no_of_days')->textInput(['maxlength' => true, 'placeholder' => 'No of Days'])  ?> </div> -->

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

    </div> <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


    <?php ActiveForm::end(); ?>

</div>