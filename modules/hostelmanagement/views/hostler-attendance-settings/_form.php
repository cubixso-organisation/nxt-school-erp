<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\HostlerAttendanceSettings */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="hostler-attendance-settings-form">

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

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
            // Your code for the Chef Warden condition goes here
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
        }
    }
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>

    <?= $form->field($model, 'daily_attendance_count')->textInput(['placeholder' => 'Daily Attendance Count']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
