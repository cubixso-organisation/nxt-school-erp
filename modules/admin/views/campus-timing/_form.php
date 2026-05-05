<?php

use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CampusTiming */
/* @var $form yii\widgets\ActiveForm */

$campus_id = (new Campus())->getCampusId();

?>

<div class="campus-timing-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]);


    ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => $campus_id, 'style' => 'display:none']); ?>


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
    <?= $form->field($model, 'academic_year_id', ['template' => '{input}'])->textInput(['value' => (new Campus())->getCurrentSession((new Campus())->getCampusId()), 'style' => 'display:none']); ?>

    <?= $form->field($model, 'start_time')->widget(\kartik\datecontrol\DateControl::className(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
        'saveFormat' => 'php:H:i:00', // Set seconds to 00 when saving
        'displayFormat' => 'H:i',
        'ajaxConversion' => true,

        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Start Time'),
                'autoclose' => true,

                'defaultTime' => false, // Ensures no default seconds value is shown
            ],

        ]
    ]); ?>

    <?= $form->field($model, 'end_time')->widget(\kartik\datecontrol\DateControl::className(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
        'saveFormat' => 'php:H:i:00', 
        'displayFormat' => 'H:i',// Set seconds to 00 when saving
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose End Time'),
                'autoclose' => true,
                'showSeconds' => false, // Do not show seconds in the picker
                'minuteStep' => 1,      // Adjust step for minutes selection
                'defaultTime' => false, // Ensures no default seconds value is shown
            ]

        ]
    ]); ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>