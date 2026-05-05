<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\RazorpayLinkedAccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-razorpay-linked-account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>

    <?= $form->field($model, 'reference_id')->textInput(['maxlength' => true, 'placeholder' => 'Reference']) ?>

    <?php /* echo $form->field($model, 'legal_business_name')->textInput(['maxlength' => true, 'placeholder' => 'Legal Business Name']) */ ?>

    <?php /* echo $form->field($model, 'business_type')->textInput(['maxlength' => true, 'placeholder' => 'Business Type']) */ ?>

    <?php /* echo $form->field($model, 'contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Contact Name']) */ ?>

    <?php /* echo $form->field($model, 'street1')->textInput(['maxlength' => true, 'placeholder' => 'Street1']) */ ?>

    <?php /* echo $form->field($model, 'street2')->textInput(['maxlength' => true, 'placeholder' => 'Street2']) */ ?>

    <?php /* echo $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'City']) */ ?>

    <?php /* echo $form->field($model, 'state')->textInput(['maxlength' => true, 'placeholder' => 'State']) */ ?>

    <?php /* echo $form->field($model, 'postal_code')->textInput(['maxlength' => true, 'placeholder' => 'Postal Code']) */ ?>

    <?php /* echo $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'Country']) */ ?>

    <?php /* echo $form->field($model, 'pan')->textInput(['maxlength' => true, 'placeholder' => 'Pan']) */ ?>

    <?php /* echo $form->field($model, 'gst')->textInput(['maxlength' => true, 'placeholder' => 'Gst']) */ ?>

    <?php /* echo $form->field($model, 'razorpay_acc_id')->textInput(['maxlength' => true, 'placeholder' => 'Razorpay Acc']) */ ?>

    <?php /* echo $form->field($model, 'account_status')->textInput(['maxlength' => true, 'placeholder' => 'Account Status']) */ ?>

    <?php /* echo $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number']) */ ?>

    <?php /* echo $form->field($model, 'ifsc_code')->textInput(['maxlength' => true, 'placeholder' => 'Ifsc Code']) */ ?>

    <?php /* echo $form->field($model, 'beneficiary_name')->textInput(['maxlength' => true, 'placeholder' => 'Beneficiary Name']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
