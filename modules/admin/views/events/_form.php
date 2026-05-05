<?php

use app\models\User;
use app\modules\admin\models\base\ClassSections;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<div class="events-form">

    <?php $form = ActiveForm::begin([
        'id' => 'event-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'fieldConfig' => ['options' => ['class' => 'form-group']],
        'formConfig' => ['showErrors' => true],
        // 'options' => ['onsubmit' => 'return false;'],
        
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList($model->getTypeOptions(), ['prompt' => 'Select Type'])->label("Select Type") ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'is_global')->dropDownList($model->getScopeOptions(), ['prompt' => 'Select Scope'])->label("Select Scope") ?>
        </div>
    </div>
    <?php
    $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
    echo $form->field($model, 'campus_id')->hiddenInput(['value' => $campusId])->label(false);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'event_name')->textInput(['maxlength' => true, 'placeholder' => 'Event Name'])->label("Title") ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*', 'placeholder' => 'Upload Image']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'placeholder' => 'Event Description']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="venue">
            <?= $form->field($model, 'venue')->textInput(['maxlength' => true, 'placeholder' => 'Venue']) ?>
        </div>

        <div class="col-md-6" id="section">
    <?= $form->field($model, 'section[]')->widget(Select2::classname(), [ // Note the change here
        'data' => ArrayHelper::map(
            ClassSections::find()
                ->joinWith('studentClass')
                ->andWhere(['class_sections.campus_id' => (new User())->getCampusId()])
                ->orderBy('class_sections.id')
                ->asArray()
                ->all(),
            'id',
            function ($model) {
                return $model['studentClass']['title'] . ' - ' . $model['section_name'];
            }
        ),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Class & Section'),
            'multiple' => true, // Enable multiple selection
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>
</div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'start_time')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => 'Select Start Time',
                        'autoclose' => true,
                    ],
                ],
            ])->label("Start Date"); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'end_time')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => 'Select End Time',
                        'autoclose' => true,
                    ],
                ],
            ])->label("End Date"); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="busRequired">
            <?= $form->field($model, 'bus_required')->radioList($model->getBusOptions())->label("Is Bus Required?") ?>
        </div>

        <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList($model->getStateOptions(), [
    'prompt' => 'Select Status',
])->label("Status") ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function() {
        $("#venue").hide();
        $("#section").hide();
        $("#busRequired").hide();

        $("#events-type").on('change', function() {
            const typeVal = $(this).val();
            if (typeVal == 1) {
                $("#venue").show();
                $("#busRequired").show();
            }else{
                $("#venue").hide();
                $("#busRequired").hide();
            }
        });

        $("#events-is_global").on('change', function() {
            const golbalVal = $(this).val();
            if (golbalVal == 2) {
                $("#section").show();
            } else {
                $("#section").hide();

            }
        });
    });
</script>