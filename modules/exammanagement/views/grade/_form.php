<?php

use app\models\User;
use app\modules\admin\models\ClassSections;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\Grade */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script',
    'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'GradeDefination',
        'relID' => 'grade-defination',
        'value' => \yii\helpers\Json::encode($model->gradeDefinations),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="grade-form">

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

    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['style' => 'display:none', 'value' => (new User)->getCampusId()]); ?>
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

    <?= $form->field($model, 'maximum_exam_marks')->textInput(['placeholder' => 'Maximum Exam Marks']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord || !$model->isNewRecord) { ?> <?php
                                        $forms = [
                                            [
                                                'label' => '<i class="fa fa-book"></i> ' . Html::encode(Yii::t('app', 'GradeDefination')),
                                                'content' => $this->render('_formGradeDefination', [
                                                    'row' => \yii\helpers\ArrayHelper::toArray($model->gradeDefinations),
                                                ]),
                                            ],
                                        ];
                                        echo kartik\tabs\TabsX::widget([
                                            'items' => $forms,
                                            'position' => kartik\tabs\TabsX::POS_ABOVE,
                                            'encodeLabels' => false,
                                            'pluginOptions' => [
                                                'bordered' => true,
                                                'sideways' => true,
                                                'enableCache' => false,
                                            ],
                                        ]);
                                        ?>
    <?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>