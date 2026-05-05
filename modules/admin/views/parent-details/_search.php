<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\ParentDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-parent-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'name_of_the_father')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Father']) ?>

    <?= $form->field($model, 'name_of_the_mother')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Mother']) ?>

    <?= $form->field($model, 'current_address')->textarea(['rows' => 6]) ?>

    <?php /* echo $form->field($model, 'permanent_address')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number']) */ ?>

    <?php /* echo $form->field($model, 'father_education_qualification')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'mother_education_qualification')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'father_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Father Aadhaar Number']) */ ?>

    <?php /* echo $form->field($model, 'mother_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Mother Aadhaar Number']) */ ?>

    <?php /* echo $form->field($model, 'father_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Father Occupation']) */ ?>

    <?php /* echo $form->field($model, 'mother_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Mother Occupation']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
