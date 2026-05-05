<?php

use app\models\User;
use app\modules\admin\models\base\TeacherDetails;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveApplied */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="staff-leave-applied-form">

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
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12 d-none'> <?= $form->field($model, 'id')->textInput(['style' => 'display:none']) ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12 d-none'> <?= $form->field($model, 'id')->textInput(['style' => 'display:none'])  ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(
                                                                    TeacherDetails::find()
                                                                        ->joinWith('user') // Assuming there is a relation named 'user' in TeacherDetails model
                                                                        ->where(['user.user_role' => User::role_teacher, 'teacher_details.campus_id' => (new User())->getCampusId()])
                                                                        ->orderBy('id')
                                                                        ->asArray()
                                                                        ->all(),
                                                                    'user_id',
                                                                    'name'
                                                                ),
                                                                'options' => ['placeholder' => 'Choose User'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]); ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'leave_type_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(\app\modules\leavemanagement\models\StaffLeaveTypes::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
                                                                'options' => ['placeholder' => 'Choose Staff leave types'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]);  ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'leave_reason')->textarea(['rows' => 6])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'from_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                                                'saveFormat' => 'php:Y-m-d H:i:s',
                                                                'ajaxConversion' => true,
                                                                'options' => [
                                                                    'pluginOptions' => [
                                                                        'placeholder' => 'Choose From Date',
                                                                        'autoclose' => true,
                                                                    ]
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'to_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                                                'saveFormat' => 'php:Y-m-d H:i:s',
                                                                'ajaxConversion' => true,
                                                                'options' => [
                                                                    'pluginOptions' => [
                                                                        'placeholder' => 'Choose To Date',
                                                                        'autoclose' => true,
                                                                    ]
                                                                ],
                                                            ]);  ?> </div>


        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'user_role')->textInput(['maxlength' => true, 'placeholder' => 'User Role'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

    </div> <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


    <?php ActiveForm::end(); ?>

</div>