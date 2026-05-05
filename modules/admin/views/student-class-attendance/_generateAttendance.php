<?php

use app\models\User;
use app\modules\admin\models\ClassSections;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\StudentClassAttendanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-student-class-attendance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['generate-attendance'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'section_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    ClassSections::find()
                        ->joinWith('studentClass')  // Assuming there is a relation defined in the ClassSections model
                        ->andWhere(['class_sections.campus_id' => (new User())->getCampusId()])
                        ->orderBy('class_sections.id')
                        ->asArray()
                        ->all(),
                    'id',
                    function ($model) {
                        return $model['studentClass']['title'] . ' - ' . $model['section_name']; // Assuming 'name' is the section name
                    }
                ),
                'options' => ['placeholder' => Yii::t('app', 'Choose Class & Section')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>

            <?= $form->field($model, 'date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Date'),
                        'autoclose' => true
                    ]
                ],
            ]); ?>
        </div>
    </div>


    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Generate Attendance'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>