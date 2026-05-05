<?php

use app\models\User;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\NoticeBoards */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="notice-boards-form">

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


    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ],
            ]) ?>
        </div>

        <div class="col-md-6 col-lg-6 col-sm-12">

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

        </div>
    </div>


    <?= $form->field($model, 'student_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(
            \app\modules\admin\models\StudentDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status' => StudentDetails::STATUS_ACTIVE])
                ->orderBy('id')
                ->asArray()
                ->all(),
            'id',
            function ($model) {
                return $model['student_name'];
            }
        ),
        'options' => ['placeholder' => Yii::t('app', 'Choose Students'),'multiple' => true],
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



    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>