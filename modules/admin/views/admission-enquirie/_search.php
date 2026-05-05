<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\AdmissionEnquirieSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-admission-enquirie-search">

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

    <?= $form->field($model, 'student_name')->textInput(['maxlength' => true, 'placeholder' => 'Student Name']) ?>

    <?= $form->field($model, 'parent_name')->textInput(['maxlength' => true, 'placeholder' => 'Parent Name']) ?>

    <?= $form->field($model, 'contact_no')->textInput(['placeholder' => 'Contact No']) ?>

    <?php /* echo $form->field($model, 'next_class')->textInput(['maxlength' => true, 'placeholder' => 'Next Class']) */ ?>

    <?php /* echo $form->field($model, 'previous_class')->textInput(['maxlength' => true, 'placeholder' => 'Previous Class']) */ ?>

    <?php /* echo $form->field($model, 'dob')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Dob'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'address')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
