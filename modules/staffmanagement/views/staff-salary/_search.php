<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\search\StaffSalarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-staff-salary-search">

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

    <?= $form->field($model, 'staff_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDetails::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Staff details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ctc')->textInput(['placeholder' => 'Ctc']) ?>

    <?= $form->field($model, 'basic_salary_type')->textInput(['placeholder' => 'Basic Salary Type']) ?>

    <?php /* echo $form->field($model, 'basic_salary_value')->textInput(['placeholder' => 'Basic Salary Value']) */ ?>

    <?php /* echo $form->field($model, 'earnings')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'ctc_monthly')->textInput(['placeholder' => 'Ctc Monthly']) */ ?>

    <?php /* echo $form->field($model, 'ctc_yearly')->textInput(['placeholder' => 'Ctc Yearly']) */ ?>

    <?php /* echo $form->field($model, 'total_deduction_monthly')->textInput(['placeholder' => 'Total Deduction Monthly']) */ ?>

    <?php /* echo $form->field($model, 'total_deduction_yearly')->textInput(['placeholder' => 'Total Deduction Yearly']) */ ?>

    <?php /* echo $form->field($model, 'salary_group_id')->textInput(['placeholder' => 'Salary Group']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <?php /* echo $form->field($model, 'create_user_id')->textInput(['placeholder' => 'Create Uder']) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
