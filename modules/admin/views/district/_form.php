<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\District */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Campus', 
        'relID' => 'campus', 
        'value' => \yii\helpers\Json::encode($model->campuses),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]); 
?> 

<div class="district-form">

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

    <?= $form->field($model, 'country_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Country::find()->orderBy('id')->asArray()->all(), 'id', 'country_name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Country'),'id'=>'country-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>



<?= $form->field($model, 'state_id')->widget(DepDrop::classname(), [
    'options'=>['id'=>'state-id'],
    'pluginOptions'=>[
        'depends'=>['country-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/district/state-data'])
    ]
]);

?>





    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?>    <?php
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
<?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
