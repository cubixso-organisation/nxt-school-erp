<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptions */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tutorix-subscriptions-form">

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

    <?= $form->field($model, 'user_id')->textInput(['placeholder' => 'User']) ?>

    <?= $form->field($model, 'student_id')->textInput(['placeholder' => 'Student']) ?>

    <?= $form->field($model, 'parent_id')->textInput(['placeholder' => 'Parent']) ?>

    <?= $form->field($model, 'subscription_type')->textInput(['placeholder' => 'Subscription Type']) ?>

    <?= $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) ?>

    <?= $form->field($model, 'total_item')->textInput(['placeholder' => 'Total Item']) ?>

    <?= $form->field($model, 'total_item_price')->textInput(['placeholder' => 'Total Item Price']) ?>

    <?= $form->field($model, 'gst_percentage')->textInput(['placeholder' => 'Gst Percentage']) ?>

    <?= $form->field($model, 'gst_amount')->textInput(['placeholder' => 'Gst Amount']) ?>

    <?= $form->field($model, 'other_charges')->textInput(['placeholder' => 'Other Charges']) ?>

    <?= $form->field($model, 'coupon_applied_id')->textInput(['placeholder' => 'Coupon Applied']) ?>

    <?= $form->field($model, 'coupon_code')->textInput(['maxlength' => true, 'placeholder' => 'Coupon Code']) ?>

    <?= $form->field($model, 'coupon_discount')->textInput(['placeholder' => 'Coupon Discount']) ?>

    <?= $form->field($model, 'total_amount')->textInput(['placeholder' => 'Total Amount']) ?>

    <?= $form->field($model, 'payment_status')->textInput(['placeholder' => 'Payment Status']) ?>

    <?= $form->field($model, 'payment_method')->textInput(['placeholder' => 'Payment Method']) ?>

    <?= $form->field($model, 'tutorix_user_access_token')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'unique_id')->textInput(['maxlength' => true, 'placeholder' => 'Unique']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
