<?php

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\StudentClass;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ClassSections */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'FeeStructures',
        'relID' => 'fee-structures',
        'value' => \yii\helpers\Json::encode($model->feeStructures),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]); 
?>

<div class="class-sections-form">

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
    <?php  echo $this->render('../bus-route/_campus_form', ['model' => $model,'form'=>$form]); ?>
    

    <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['status'=>StudentClass::STATUS_ACTIVE])
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Class'); ?>

    <?= $form->field($model, 'section_name')->textInput(['maxlength' => true, 'placeholder' => 'Section Name']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?> <?php
                                        $forms = [
                                           
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