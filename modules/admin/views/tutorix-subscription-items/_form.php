<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptionItems */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tutorix-subscription-items-form">

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

    <?= $form->field($model, 'subscription_id')->textInput(['placeholder' => 'Subscription']) ?>

    <?= $form->field($model, 'student_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'class_id')->textInput(['placeholder' => 'Class']) ?>

    <?= $form->field($model, 'parent_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ParentDetails::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Parent details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'class_type')->textInput(['placeholder' => 'Class Type']) ?>

    <?= $form->field($model, 'item_price')->textInput(['placeholder' => 'Item Price']) ?>

    <?= $form->field($model, 'start_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Start Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'expiry_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Expiry Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'is_free_trail')->textInput(['placeholder' => 'Is Free Trail']) ?>

    <?= $form->field($model, 'payment_status')->textInput(['placeholder' => 'Payment Status']) ?>

    <?= $form->field($model, 'tutorix_user_access_token')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'unique_id')->textInput(['maxlength' => true, 'placeholder' => 'Unique']) ?>

    <?= $form->field($model, 'year_id')->textInput(['placeholder' => 'Year']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
