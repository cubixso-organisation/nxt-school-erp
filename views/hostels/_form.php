<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Hostels */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="hostels-form">

    <?php $form = ActiveForm::begin([
    'id' => 'login-form-inline',
    'type' => ActiveForm::TYPE_VERTICAL,
    'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
    'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
    'formConfig' => ['showErrors' => true],
    // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>
    <div class="row grid-margin stretch-card"> 
         <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

 </div> <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']);  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'user_id')->textInput(['placeholder' => 'User'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'image_file')->textInput(['maxlength' => true, 'placeholder' => 'Image File'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'name_of_the_hostel')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Hostel'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'area')->textInput(['maxlength' => true, 'placeholder' => 'Area'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'Country'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'state')->textInput(['maxlength' => true, 'placeholder' => 'State'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'city_id')->textInput(['placeholder' => 'City'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'lat')->textInput(['placeholder' => 'Lat'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'lng')->textInput(['placeholder' => 'Lng'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'coordinates')->textInput(['maxlength' => true, 'placeholder' => 'Coordinates'])  ?> </div>

 <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>  <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

 </div> <?php if($model->isNewRecord){ ?><?php } ?>
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                  

    <?php ActiveForm::end(); ?>

</div> 