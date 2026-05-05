<?php

use app\models\User;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\Campus;
use kartik\file\FileInput;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentAssessment */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script',
    'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'StudentHasAssessment',
        'relID' => 'student-has-assessment',
        'value' => \yii\helpers\Json::encode($model->studentHasAssessments),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="student-assessment-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]);

    $campus_id = (new Campus())->getCampusId();
    ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => $campus_id, 'style' => 'display:none']); ?>

    <?= $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->where(['campus_id' => $campus_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Teacher'); ?>


    <?= $form->field($model, 'academic_year_id', ['template' => '{input}'])->textInput(['value' => (new Campus())->getCurrentSession((new Campus())->getCampusId()), 'style' => 'display:none']); ?>

    <!-- <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> -->

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(
                \app\modules\admin\models\ClassSections::find()
                    ->joinWith(['studentClass as sc'])
                    ->where(['sc.campus_id' => (new User())->getCampusId()])
                    ->andWhere(['class_sections.status' => ClassSections::STATUS_ACTIVE]) // Assuming the relation is named 'studentClass'
                    ->orderBy(['class_sections.id' => SORT_DESC])
                    ->asArray()
                    ->all(),
                'id',
                function ($model) {
                    return $model['studentClass']['title'] . ' - ' . $model['section_name']; // Assuming class_name is the attribute for class name
                }
            ),
            'options' => [
                'placeholder' => Yii::t('app', 'Choose Class sections'),
                'multiple' => true, // Enable multi-select
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Select Class & Section'); ?>
    <?php } else { ?>

        <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(
                \app\modules\admin\models\ClassSections::find()
                    ->joinWith(['studentClass as sc'])
                    ->where(['sc.campus_id' => (new User())->getCampusId()])
                    ->andWhere(['class_sections.status' => ClassSections::STATUS_ACTIVE]) // Assuming the relation is named 'studentClass'
                    ->orderBy(['class_sections.id' => SORT_DESC])
                    ->asArray()
                    ->all(),
                'id',
                function ($model) {
                    return $model['studentClass']['title'] . ' - ' . $model['section_name']; // Assuming class_name is the attribute for class name
                }
            ),
            'options' => [
                'placeholder' => Yii::t('app', 'Choose Class sections'),
                'multiple' => false, // Enable multi-select
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Select Class & Section'); ?>
    <?php } ?>

    <?= $form->field($model, 'subject_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()->andWhere(['campus_id' => $campus_id])->orderBy('id')->asArray()->all(), 'id', 'subject_name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Subjects')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'assessment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'submission_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Submission Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'document')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => ['image/*']],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'initialPreview' => [
                $model->document
            ],
            'initialPreviewAsData' => true,

            'overwriteInitial' => true,

            'showUpload' => false,
        ]
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>