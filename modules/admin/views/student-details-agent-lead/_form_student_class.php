<?php

use app\modules\admin\models\Campus;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;


?>  

<div class="student-class-form">

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

 

    <?php
    if(User::isAdmin()){
        echo   $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Campus'),'prompt'=>'Select Campus'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    }else if(User::isInstituteAdmin()){


        echo   $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()
            ->where(['id','id',(new Campus())->getInstituteHasCampusIds()])
            ->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Campus'),'prompt'=>'Select School Or College'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    }
  
     
     ?>



    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>

    <?php if(!$model->isNewRecord){ ?>

<?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
<?php } ?>

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
