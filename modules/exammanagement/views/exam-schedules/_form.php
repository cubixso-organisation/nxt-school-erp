<?php

use app\models\User;
use app\modules\exammanagement\models\base\MarksDivition;
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
        'select2Options' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true, 'closeOnSelect' => true]],
        'pluginOptions' => [
            'placeholder' => 'Select...',
            'depends' => ['section-id'],
            'url' => \yii\helpers\Url::toRoute(['teacher-class-and-subjects/get-subjects']),
        ],
    ]); ?>

    <?= $form->field($model, 'room_no')->textInput(['placeholder' => 'Room No']) ?>

    <div id="marks-division-container" class="card bg-light p-4">
        <div class="row marks-division-row">
            <div class="col-md-4">
                <?= $form->field($model, 'marks_division[]')->dropDownList(
                    \yii\helpers\ArrayHelper::map(MarksDivition::find()->where(['campus_id' => (new User())->getCampusId()])->all(), 'id', 'title'),
                    ['prompt' => 'Select Marks Division']
                )->label('Marks Division') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'max_marks_devision[]')->textInput(['placeholder' => 'Max Marks'])->label('Max Marks') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'min_marks_devision[]')->textInput(['placeholder' => 'Min Marks'])->label('Min Marks') ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="button" id="add-more" class="btn btn-success">Add More</button>
    </div>

    <?php
    $this->registerJs("
$('#add-more').click(function(){
    var newRow = $('.marks-division-row:first').clone();
    newRow.find('input').val(''); // Clear input values
    newRow.find('select').val(''); // Clear dropdown values
    $('#marks-division-container').append(newRow);
});
");
    ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'exam_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                // 'ajaxConversion' => false,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Exam Date'),
                        'autoclose' => true,
                    ]
                ],
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'start_time')->widget(\kartik\datecontrol\DateControl::className(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                'displayFormat' => 'php:H:i',
                'saveFormat' => 'php:H:i',
                // 'ajaxConversion' => false, 
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Exam start Time'),
                        'autoclose' => true,
                    ]
                ]
            ])->label('Exam start time (Hours)'); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'end_time')->widget(\kartik\datecontrol\DateControl::className(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                'displayFormat' => 'php:H:i',
                'saveFormat' => 'php:H:i',
                // 'ajaxConversion' => false, // Disable AJAX conversion to prevent unexpected time zone issues
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Exam End Time'),
                        'autoclose' => true,
                    ]
                ]
            ])->label('Exam End Time (Hours)'); ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox" style="margin-left: 30px;margin-top:30px;">
                    <input type="checkbox" id="add_child_merit" name="add_child_merit" value="1">
                    <label>
                        <span class="checkbox-text">Add as Child Assessments</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="dropdown_merits" style="display:none;">
            <label for="custom_dropdown">Merits</label>
            <?= \kartik\select2\Select2::widget([
                'name' => 'merit_values',
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\childassessment\models\ChildMerit::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'name'),
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