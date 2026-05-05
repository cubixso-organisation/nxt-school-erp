<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\search\StaffDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-staff-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => 'Choose Campus'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'designation_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDesignations::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => 'Choose Staff designations'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?php
    // echo $form->field($model, 'payroll_id')->widget(\kartik\widgets\Select2::classname(), [
    //     'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\Payroll::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
    //     'options' => ['placeholder' => 'Choose Payroll'],
    //     'pluginOptions' => [
    //         'allowClear' => true
    //     ],
    // ]);
    ?>

    <?php /* echo $form->field($model, 'contact_no')->textInput(['maxlength' => true, 'placeholder' => 'Contact No']) */ ?>

    <?php /* echo $form->field($model, 'date_of_birth')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Date Of Birth',
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender']) */ ?>

    <?php /* echo $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) */ ?>

    <?php /* echo $form->field($model, 'aadhar_card')->textInput(['maxlength' => true, 'placeholder' => 'Aadhar Card']) */ ?>

    <?php /* echo $form->field($model, 'pan_card')->textInput(['maxlength' => true, 'placeholder' => 'Pan Card']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <?php /* echo $form->field($model, 'created_on')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Created On',
                'autoclose' => true,
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'updated_on')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Updated On',
                'autoclose' => true,
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'create_user_id')->textInput(['placeholder' => 'Create User']) */ ?>

    <?php /* echo $form->field($model, 'update_user_id')->textInput(['placeholder' => 'Update Create']) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>