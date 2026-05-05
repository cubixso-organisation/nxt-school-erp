<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\ItemCategory */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="item-category-form">

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
            <?= $form->field($model, 'item_category')->textInput(['maxlength' => true, 'placeholder' => 'Item Category']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

        </div>
    </div>


    <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
        'editorOptions' => [
            'preset' => 'small',
            'inline' => false,
        ],
    ]) ?>


    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>