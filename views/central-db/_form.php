<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CentralDb */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="central-db-form">

    <div class="row">
        <div class="col-md-6 col-lg-6">
            <!-- Image Section -->
            <img src="your-image-url.jpg" alt="Your Image" class="img-fluid">
        </div>
        <div class="col-md-6 col-lg-6">
            <!-- Form Section -->
            <?php $form = ActiveForm::begin([
                'id' => 'login-form-inline',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true,
                'fieldConfig' => ['options' => ['class' => 'form-group']], // remove column classes
                'formConfig' => ['showErrors' => true],
            ]); ?>

            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>
            <?= $form->field($model, 'school_name')->textInput(['maxlength' => true, 'placeholder' => 'School Name']) ?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address']) ?>

            <?php if ($model->isNewRecord) { ?><?php } ?>

            <div class="form-group">
                <?= $form->field($model, 'domain', [
                    'addon' => ['append' => ['content' => '.estudent.com']],
                ])->textInput(['maxlength' => true, 'placeholder' => 'Domain']) ?>
            </div>

            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
