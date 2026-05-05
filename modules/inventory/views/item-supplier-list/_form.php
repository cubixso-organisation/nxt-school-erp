<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemSupplierList */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="item-supplier-list-form">

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
    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(),'style' => 'display:none']); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'contact_person_phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Phone']) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'contact_person_name')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Name']) ?>

        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'contact_person_email')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Email']) ?>



        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'address')->textarea(['rows' => 1, 'style' => 'height: 20px;']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
                'editorOptions' => [
                    'preset' => 'small',
                    'inline' => false,
                ],
            ]) ?>
        </div>
        
    </div>



    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>