<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TestUsers */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="test-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Username']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'First Name']) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => 'Last Name']) ?>

    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true, 'placeholder' => 'Contact No']) ?>

    <?= $form->field($model, 'alternative_contact')->textInput(['maxlength' => true, 'placeholder' => 'Alternative Contact']) ?>

    <?= $form->field($model, 'date_of_birth')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Date Of Birth'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'profile_image')->textInput(['maxlength' => true, 'placeholder' => 'Profile Image']) ?>

    <?= $form->field($model, 'status')->textInput(['placeholder' => 'Status']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
