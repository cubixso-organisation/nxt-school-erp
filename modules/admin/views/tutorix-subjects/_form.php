<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubjects */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'TutorixLectures', 
        'relID' => 'tutorix-lectures', 
        'value' => \yii\helpers\Json::encode($model->tutorixLectures),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'TutorixSections', 
        'relID' => 'tutorix-sections', 
        'value' => \yii\helpers\Json::encode($model->tutorixSections),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'TutorixSubjects', 
        'relID' => 'tutorix-subjects', 
        'value' => \yii\helpers\Json::encode($model->tutorixSubjects),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="tutorix-subjects-form">

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

    <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TutorixSubjects::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Tutorix subjects')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'subject_id')->textInput(['placeholder' => 'Subject']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

<?php if($model->isNewRecord){ ?>    <?php
    $forms = [
        [
            'label' => '<i class="fa fa-book"></i> ' . Html::encode(Yii::t('app', 'TutorixLectures')),
            'content' => $this->render('_formTutorixLectures', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->tutorixLectures),
            ]),
        ],
        [
            'label' => '<i class="fa fa-book"></i> ' . Html::encode(Yii::t('app', 'TutorixSections')),
            'content' => $this->render('_formTutorixSections', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->tutorixSections),
            ]),
        ],
        [
            'label' => '<i class="fa fa-book"></i> ' . Html::encode(Yii::t('app', 'TutorixSubjects')),
            'content' => $this->render('_formTutorixSubjects', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->tutorixSubjects),
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
<?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
