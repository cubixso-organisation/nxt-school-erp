<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

?>
 
<div class="parent-details-form">

<?php $form = ActiveForm::begin(); ?>



    <div class="row">

    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number']) ?>
    </div>


    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'name_of_the_father')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Father']) ?>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'name_of_the_mother')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Mother']) ?>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'current_address')->textarea(['rows' => 6]) ?>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

    <?= $form->field($model, 'permanent_address')->textarea(['rows' => 6]) ?>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

    <?= $form->field($model, 'father_education_qualification')->textarea(['rows' => 6]) ?>
    </div>


<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

    <?= $form->field($model, 'mother_education_qualification')->textarea(['rows' => 6]) ?>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

    <?= $form->field($model, 'father_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Father Aadhaar Number']) ?>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'mother_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Mother Aadhaar Number']) ?>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'father_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Father Occupation']) ?>
</div>

<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'mother_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Mother Occupation']) ?>
</div>


</div>

<?php ActiveForm::end(); ?>




</div>
