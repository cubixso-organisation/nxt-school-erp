<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\search\HostelsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-hostels-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'user_id')->textInput(['placeholder' => 'User']) ?>

    <?= $form->field($model, 'image_file')->textInput(['maxlength' => true, 'placeholder' => 'Image File']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?php /* echo $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) */ ?>

    <?php /* echo $form->field($model, 'name_of_the_hostel')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Hostel']) */ ?>

    <?php /* echo $form->field($model, 'area')->textInput(['maxlength' => true, 'placeholder' => 'Area']) */ ?>

    <?php /* echo $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode']) */ ?>

    <?php /* echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address']) */ ?>

    <?php /* echo $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'Country']) */ ?>

    <?php /* echo $form->field($model, 'state')->textInput(['maxlength' => true, 'placeholder' => 'State']) */ ?>

    <?php /* echo $form->field($model, 'city_id')->textInput(['placeholder' => 'City']) */ ?>

    <?php /* echo $form->field($model, 'lat')->textInput(['placeholder' => 'Lat']) */ ?>

    <?php /* echo $form->field($model, 'lng')->textInput(['placeholder' => 'Lng']) */ ?>

    <?php /* echo $form->field($model, 'coordinates')->textInput(['maxlength' => true, 'placeholder' => 'Coordinates']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
