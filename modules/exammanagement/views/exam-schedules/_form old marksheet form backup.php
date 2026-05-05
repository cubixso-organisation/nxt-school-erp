<?php

use app\modules\admin\models\User;
use app\modules\childassessment\models\base\ChildMerit;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamSchedules */
/* @var $form yii\widgets\ActiveForm */

$out = [];
?>

<div class="exam-schedules-form">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']],
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'session_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Choose Academic Year'); ?>

    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['style' => 'display:none', 'value' => (new User())->getCampusId()]); ?>

    <?= $form->field($model, 'exam_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'name_of_exam'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Exams')],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Choose Exam'); ?>

    <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'class-id'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Class'); ?>

    <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
        'data' => $out,
        'options' => ['id' => 'section-id'],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true, 'multiple' => false, 'closeOnSelect' => true]],
        'pluginOptions' => [
            'placeholder' => 'Select...',
            'depends' => ['class-id'],
            'url' => \yii\helpers\Url::to('get-section'),
        ],
    ])->label('Section'); ?>

    <?= $form->field($model, 'subject_id')->widget(DepDrop::classname(), [
        'data' => $out,
        'options' => ['id' => 'subcat-id'],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true, 'multiple' => false, 'closeOnSelect' => true]],
        'pluginOptions' => [
            'placeholder' => 'Select...',
            'depends' => ['section-id'],
            'url' => \yii\helpers\Url::toRoute(['teacher-class-and-subjects/get-subjects']),
        ],
    ]); ?>

    <?= $form->field($model, 'room_no')->textInput(['placeholder' => 'Room No']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'max_marks')->textInput(['placeholder' => 'Max Marks']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'min_marks')->textInput(['placeholder' => 'Min Marks']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'exam_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Exam Date'),
                        'autoclose' => true,
                    ]
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'exam_duration')->widget(\kartik\datecontrol\DateControl::className(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                'displayFormat' => 'php:H:i',
                'saveFormat' => 'php:H:i',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Exam Duration'),
                        'autoclose' => true
                    ]
                ]
            ])->label('Exam Durations (Hours)'); ?>
        </div>
    </div>
    <?php
    $this->registerJs("
$(document).ready(function(){
                $('#dropdown_merits').hide();

    $('#add_child_merit').change(function(){
        if($(this).is(':checked')){

            $('#dropdown_merits').show();
        } else {
            $('#dropdown_merits').hide();
        }
    });
});
");
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox" style="margin-left: 30px;margin-top:30px;">

                    <input type="checkbox" id="add_child_merit" name="add_child_merit" value=" 1">
                    <label>

                        <span class="checkbox-text">Add as Child Assessments</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="dropdown_merits">
            <label for="custom_dropdown">Merits</label>
            <?= \kartik\select2\Select2::widget([
                'name' => 'merit_values',
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\childassessment\models\ChildMerit::find()->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['multiple' => true, 'placeholder' => 'Choose Child merit', 'id' => 'merit_values'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>



    </div>



    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>