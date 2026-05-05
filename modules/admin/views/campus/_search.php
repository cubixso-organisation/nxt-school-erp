<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\CampusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-campus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'institute_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Institutes')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'educational_institution_type_id')->textInput(['placeholder' => 'Educational Institution Type']) ?>

    <?= $form->field($model, 'name_of_the_educational_Institution')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Educational Institution']) ?>

    <?= $form->field($model, 'user_id')->textInput(['placeholder' => 'User']) ?>

    <?php /* echo $form->field($model, 'country_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Country::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Country')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'state_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose State')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'district_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose District')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode']) */ ?>

    <?php /* echo $form->field($model, 'address')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'campus_code')->textInput(['maxlength' => true, 'placeholder' => 'Campus Code']) */ ?>

    <?php /* echo $form->field($model, 'registration_number')->textInput(['maxlength' => true, 'placeholder' => 'Registration Number']) */ ?>

    <?php /* echo $form->field($model, 'registration_document')->textInput(['maxlength' => true, 'placeholder' => 'Registration Document']) */ ?>

    <?php /* echo $form->field($model, 'name_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Authorized']) */ ?>

    <?php /* echo $form->field($model, 'designation_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Designation Of The Authorized']) */ ?>

    <?php /* echo $form->field($model, 'contact_number_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number Of The Authorized']) */ ?>

    <?php /* echo $form->field($model, 'name_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Contact']) */ ?>

    <?php /* echo $form->field($model, 'designation_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Designation Of The Contact']) */ ?>

    <?php /* echo $form->field($model, 'contact_number_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number Of The Contact']) */ ?>

    <?php /* echo $form->field($model, 'email_id_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Email Id Of The Authorized']) */ ?>

    <?php /* echo $form->field($model, 'aadhaar_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Aadhaar Of The Authorized']) */ ?>

    <?php /* echo $form->field($model, 'lat')->textInput(['placeholder' => 'Lat']) */ ?>

    <?php /* echo $form->field($model, 'lng')->textInput(['placeholder' => 'Lng']) */ ?>

    <?php /* echo $form->field($model, 'coordinates')->textInput(['maxlength' => true, 'placeholder' => 'Coordinates']) */ ?>

    <?php /* echo $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'City']) */ ?>

    <?php /* echo $form->field($model, 'status')->textInput(['placeholder' => 'Status']) */ ?>

    <?php /* echo $form->field($model, 'school_logo')->textInput(['maxlength' => true, 'placeholder' => 'School Logo']) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
