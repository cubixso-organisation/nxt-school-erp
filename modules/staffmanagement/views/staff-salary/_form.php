<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffSalary */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="staff-salary-form">

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

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'staff_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDetails::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Staff details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ctc')->textInput(['placeholder' => 'Ctc']) ?>

    <?= $form->field($model, 'basic_salary_type')->textInput(['placeholder' => 'Basic Salary Type']) ?>

    <?= $form->field($model, 'basic_salary_value')->textInput(['placeholder' => 'Basic Salary Value']) ?>

    <?= $form->field($model, 'earnings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ctc_monthly')->textInput(['placeholder' => 'Ctc Monthly']) ?>

    <?= $form->field($model, 'ctc_yearly')->textInput(['placeholder' => 'Ctc Yearly']) ?>

    <?= $form->field($model, 'total_deduction_monthly')->textInput(['placeholder' => 'Total Deduction Monthly']) ?>

    <?= $form->field($model, 'total_deduction_yearly')->textInput(['placeholder' => 'Total Deduction Yearly']) ?>

    <?= $form->field($model, 'salary_group_id')->textInput(['placeholder' => 'Salary Group']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?= $form->field($model, 'create_user_id')->textInput(['placeholder' => 'Create Uder']) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
