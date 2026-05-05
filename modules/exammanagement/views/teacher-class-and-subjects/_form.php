<?php

use app\models\User;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\ClassSections;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\TeacherClassAndSubjects */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="teacher-class-and-subjects-form">

    <?php $form = ActiveForm::begin([
        'id' => 'teacher-class-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'fieldConfig' => ['options' => ['class' => 'form-group col-lg-12']],
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->hiddenInput()->label(false); ?>

    <?php
    $user = new User();
    $campusId = $user->getCampusId() ?? $user->getCampusesByUser(\Yii::$app->user->identity->id);

    echo $form->field($model, 'campus_id')->textInput([
        'value' => $campusId,
        'readonly' => true,
    ]);
    ?>

    <?= $form->field($model, 'teacher_detail_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => ArrayHelper::map(
            TeacherDetails::find()->where(['campus_id' => $campusId, 'status' => TeacherDetails::STATUS_ACTIVE])->asArray()->all(),
            'id',
            'name'
        ),
        'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details')],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => ArrayHelper::map(
            ClassSections::find()->where(['campus_id' => $campusId, 'status' => ClassSections::STATUS_ACTIVE])
                ->with('studentClass')
                ->asArray()
                ->all(),
            'id',
            function ($model) {
                return $model['studentClass']['title'] . ' - ' . $model['section_name'];
            }
        ),
        'options' => ['placeholder' => Yii::t('app', 'Choose Class sections'), 'id' => 'section-id'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?php
    echo $form->field($model, 'subject_id')->widget(DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'options' => ['id' => 'subcat-id'],
        'pluginOptions' => [
            'placeholder' => Yii::t('app', 'Select Subject(s)...'),
            'depends' => ['section-id'],
            'url' => \yii\helpers\Url::to(['get-subjects']),
            'initialize' => true, // Initializes the widget with preloaded data
        ],
        'select2Options' => [
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => $model->isNewRecord,
            ],
        ],
    ]);
    
    ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions(), [
        'prompt' => Yii::t('app', 'Select Status'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
