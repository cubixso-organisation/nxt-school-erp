<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixCoupon */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tutorix-coupon-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'placeholder' => 'Code']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'coupon_type')->dropDownList($model->getTypeOptions(), ['prompt' => 'Select'])->label("Coupon Type") ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'coupon_discount')->textInput(['placeholder' => 'Coupon Discount']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'max_discount')->textInput(['placeholder' => 'Max Discount']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'min_cart_item')->textInput(['placeholder' => 'Min Cart Item']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'max_cart_item')->textInput(['placeholder' => 'Max Cart Item']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::class, [
                'options' => ['class' => 'form-control', 'placeholder' => 'Start Date'],
                'dateFormat' => 'yyyy-MM-dd', // Adjust date format as needed
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => 'c-100:c+10', // Set the year range as needed
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::class, [
                'options' => ['class' => 'form-control', 'placeholder' => 'End Date'],
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => 'c-100:c+10',
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'min_cart_value')->textInput(['placeholder' => 'Min Cart Value']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'max_cart_value')->textInput(['placeholder' => 'Max Cart Value']) ?>
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

<script>
    $(document).ready(function() {
        // Hide the fields initially
        $('.field-tutorixcoupon-max_discount').hide();

        // Function to toggle the fields based on the selected value
        function toggleFields() {
            var selectedValue = $('#tutorixcoupon-coupon_type').val();
            if (selectedValue == '1') { // Show fields when value is '1'
                $('.field-tutorixcoupon-max_discount').show();
            } else { // Hide fields for other values
                $('.field-tutorixcoupon-max_discount').hide();
            }
        }

        // Initial toggle based on the current dropdown value
        toggleFields();

        // Toggle fields when the dropdown selection changes
        $('#tutorixcoupon-coupon_type').on('change', function() {
            toggleFields();
        });
    });
</script>