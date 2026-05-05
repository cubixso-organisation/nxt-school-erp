<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\search\LibraryRacksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-library-racks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'rack_number')->textInput(['maxlength' => true, 'placeholder' => 'Rack Number']) ?>

    <?= $form->field($model, 'rack_location')->textInput(['maxlength' => true, 'placeholder' => 'Rack Location']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
