<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\SalaryComponents */
/* @var $form yii\widgets\ActiveForm */

// \mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
//     'viewParams' => [
//         'class' => 'SalaryGroupComponents', 
//         'relID' => 'salary-group-components', 
//         'value' => \yii\helpers\Json::encode($model->salaryGroupComponents),
//         'isNewRecord' => ($model->isNewRecord) ? 1 : 0
//     ]
// ]);
?>

<div class="salary-components-form">

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
    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['style' => 'display:none', 'value' => (new User())->getCampusId()]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'component_type')->textInput(['placeholder' => 'Component Type'])->dropDownList($model->getComponentTypeOptions()) ?>

    <?= $form->field($model, 'value_type')->textInput(['placeholder' => 'Value Type'])->dropDownList($model->getValueTypeOptions()) ?>

    <?= $form->field($model, 'component_value_monthly')->textInput(['placeholder' => 'Component Value Monthly']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>