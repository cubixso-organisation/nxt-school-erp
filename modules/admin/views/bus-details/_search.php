<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\BusDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-bus-details-search">

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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>

    <?= $form->field($model, 'vehicle_number')->textInput(['maxlength' => true, 'placeholder' => 'Vehicle Number']) ?>

    <?= $form->field($model, 'route_no')->textInput(['placeholder' => 'Route No']) ?>

    <?php /* echo $form->field($model, 'start_point')->textInput(['maxlength' => true, 'placeholder' => 'Start Point']) */ ?>

    <?php /* echo $form->field($model, 'end_point')->textInput(['maxlength' => true, 'placeholder' => 'End Point']) */ ?>

    <?php /* echo $form->field($model, 'start_point_lat')->textInput(['placeholder' => 'Start Point Lat']) */ ?>

    <?php /* echo $form->field($model, 'start_point_lng')->textInput(['placeholder' => 'Start Point Lng']) */ ?>

    <?php /* echo $form->field($model, 'start_point_coordinates')->textInput(['maxlength' => true, 'placeholder' => 'Start Point Coordinates']) */ ?>

    <?php /* echo $form->field($model, 'end_point_lat')->textInput(['placeholder' => 'End Point Lat']) */ ?>

    <?php /* echo $form->field($model, 'end_point_lng')->textInput(['placeholder' => 'End Point Lng']) */ ?>

    <?php /* echo $form->field($model, 'end_point_coordinates')->textInput(['maxlength' => true, 'placeholder' => 'End Point Coordinates']) */ ?>

    <?php /* echo $form->field($model, 'type')->textInput(['placeholder' => 'Type']) */ ?>

    <?php /* echo $form->field($model, 'status')->textInput(['placeholder' => 'Status']) */ ?>

    <?php /* echo $form->field($model, 'session_key')->textInput(['maxlength' => true, 'placeholder' => 'Session Key']) */ ?>

    <?php /* echo $form->field($model, 'status_direction')->textInput(['placeholder' => 'Status Direction']) */ ?>

    <?php /* echo $form->field($model, 'current_stop')->textInput(['placeholder' => 'Current Stop']) */ ?>

    <?php /* echo $form->field($model, 'next_stop')->textInput(['placeholder' => 'Next Stop']) */ ?>

    <?php /* echo $form->field($model, 'route_details')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'current_status')->textInput(['placeholder' => 'Current Status']) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
