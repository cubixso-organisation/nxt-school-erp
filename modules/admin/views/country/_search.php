<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\CountrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-country-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'country_name')->textInput(['maxlength' => true, 'placeholder' => 'Country Name']) ?>

    <?= $form->field($model, 'country_code')->textInput(['maxlength' => true, 'placeholder' => 'Country Code']) ?>

    <?= $form->field($model, 'std_code')->textInput(['maxlength' => true, 'placeholder' => 'Std Code']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
