<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\TutorixSubscriptionItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-tutorix-subscription-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

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

    <?php /* echo $form->field($model, 'class_type')->textInput(['placeholder' => 'Class Type']) */ ?>

    <?php /* echo $form->field($model, 'item_price')->textInput(['placeholder' => 'Item Price']) */ ?>

    <?php /* echo $form->field($model, 'start_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Start Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'expiry_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Expiry Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'is_free_trail')->textInput(['placeholder' => 'Is Free Trail']) */ ?>

    <?php /* echo $form->field($model, 'payment_status')->textInput(['placeholder' => 'Payment Status']) */ ?>

    <?php /* echo $form->field($model, 'tutorix_user_access_token')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'unique_id')->textInput(['maxlength' => true, 'placeholder' => 'Unique']) */ ?>

    <?php /* echo $form->field($model, 'year_id')->textInput(['placeholder' => 'Year']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
