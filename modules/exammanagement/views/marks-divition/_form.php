<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\modules\admin\models\User;
use app\modules\admin\models\Campus;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\MarksDivition */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="marks-divition-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>
    <?= $form->field($model, 'short_hand')->textInput(['maxlength' => true, 'placeholder' => 'Short Form']) ?>

    <?php
    // Fetch the campus ID for the current user
    $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

    // If there could be multiple campuses, adjust to fetch a single campus ID or handle appropriately
    if (is_array($campusId) && !empty($campusId)) {
        $campusId = $campusId[0];
    }
    $campus = Campus::findOne($campusId);

    // Display the name of the educational institution as a read-only text input

    echo $form->field($model, 'campus_id')->hiddenInput([
        'value' => $campusId,
    ])->label(false);
    ?>



    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>