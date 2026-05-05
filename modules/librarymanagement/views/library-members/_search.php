<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\search\LibraryMembersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-library-members-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true, 'placeholder' => 'Member']) ?>

    <?= $form->field($model, 'library_card_no')->textInput(['maxlength' => true, 'placeholder' => 'Library Card No']) ?>

    <?= $form->field($model, 'admission_no')->textInput(['maxlength' => true, 'placeholder' => 'Admission No']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?php /* echo $form->field($model, 'member_type')->textInput(['maxlength' => true, 'placeholder' => 'Member Type']) */ ?>

    <?php /* echo $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) */ ?>

    <?php /* echo $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
