<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\TutorixSubscriptionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-tutorix-subscriptions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'user_id')->textInput(['placeholder' => 'User']) ?>

    <?= $form->field($model, 'student_id')->textInput(['placeholder' => 'Student']) ?>

    <?= $form->field($model, 'parent_id')->textInput(['placeholder' => 'Parent']) ?>

    <?= $form->field($model, 'subscription_type')->textInput(['placeholder' => 'Subscription Type']) ?>

    <?php /* echo $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) */ ?>

    <?php /* echo $form->field($model, 'total_item')->textInput(['placeholder' => 'Total Item']) */ ?>

    <?php /* echo $form->field($model, 'total_item_price')->textInput(['placeholder' => 'Total Item Price']) */ ?>

    <?php /* echo $form->field($model, 'gst_percentage')->textInput(['placeholder' => 'Gst Percentage']) */ ?>

    <?php /* echo $form->field($model, 'gst_amount')->textInput(['placeholder' => 'Gst Amount']) */ ?>

    <?php /* echo $form->field($model, 'other_charges')->textInput(['placeholder' => 'Other Charges']) */ ?>

    <?php /* echo $form->field($model, 'coupon_applied_id')->textInput(['placeholder' => 'Coupon Applied']) */ ?>

    <?php /* echo $form->field($model, 'coupon_code')->textInput(['maxlength' => true, 'placeholder' => 'Coupon Code']) */ ?>

    <?php /* echo $form->field($model, 'coupon_discount')->textInput(['placeholder' => 'Coupon Discount']) */ ?>

    <?php /* echo $form->field($model, 'total_amount')->textInput(['placeholder' => 'Total Amount']) */ ?>

    <?php /* echo $form->field($model, 'payment_status')->textInput(['placeholder' => 'Payment Status']) */ ?>

    <?php /* echo $form->field($model, 'payment_method')->textInput(['placeholder' => 'Payment Method']) */ ?>

    <?php /* echo $form->field($model, 'tutorix_user_access_token')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'unique_id')->textInput(['maxlength' => true, 'placeholder' => 'Unique']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
