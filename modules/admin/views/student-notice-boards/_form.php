<?php

use app\models\User;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentNoticeBoards;
use app\modules\admin\models\TeacherDetails;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;

?>

<div class="student-notice-boards-form">

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

    <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ],
    ]) ?>
    <?= $form->field($model, 'notice_image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', "id" => 'center_sig_id'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'initialPreview' => $model->notice_image ? [$model->notice_image] : [],
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'showUpload' => false,
        ],
    ])->label('Notice Image/Doc'); ?>



    <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])
            ->orderBy('id')->asArray()->all(), 'id', function ($model) {
            $section_name = $model['section_name'];
            $student_class_id  = $model['student_class_id'];
            $student_class = StudentClass::find()->where(['id' => $student_class_id])->one();
            $class_and_section = $student_class->title . '-' . $section_name;
            return $class_and_section;
        }),
        'options' => ['placeholder' => Yii::t('app', 'Choose Class sections'), 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'expiry_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Expiry Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'is_global')->dropDownList($model->getIsGlobalOptions(), [
        'id' => 'is-global-dropdown',
        'onchange' => 'toggleTeacherDetailsField(this);',
    ]) ?>

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])
                ->orderBy('id')->asArray()->all(), 'id', 'name'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details'), 'id' => "teacher-details-field"],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    } else {
        if ($model->is_global == StudentNoticeBoards::is_global_yes) {
            echo $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()
                    ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])
                    ->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details'), 'id' => "teacher-details-field"],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        }
    }



    ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function toggleTeacherDetailsField() {
        var isGlobalValue = $('#is-global-dropdown').val();
        var teacherDetailsField = $('#teacher-details-field').closest('.form-group');
        if (isGlobalValue == '1') {
            teacherDetailsField.hide();

        } else {
            teacherDetailsField.show();

        }
    }
</script>