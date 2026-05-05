<?php

use app\modules\admin\models\Campus;
use app\modules\admin\models\WebSetting;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusDetails */
/* @var $form yii\widgets\ActiveForm */

 
?>   

<div class="bus-details-form card">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>
  
    <?= $form->errorSummary($model); ?>
 
    <div class="row">


  
    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>



  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'title')->label('bus name')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>
  </div>

  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'vehicle_number')->textInput(['maxlength' => true, 'placeholder' => 'Vehicle Number']) ?>
  </div>

  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'route_no')->textInput(['placeholder' => 'Route No']) ?>
  </div>





  <div class="col-md-6 col-lg-6 col-sm-12">
    <?= $form->field($model, 'current_status')->dropDownList($model->getCurrentStateOptions()) ?>
 </div>


  <div class="col-md-6 col-lg-6 col-sm-12">
    <?= $form->field($model, 'trip_type')->dropDownList($model->getTripTypeOptions()) ?>
    </div>



<?php if ($model->isNewRecord) { ?>    <?php
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
     

<?php } ?>  
<div class="col-md-12 col-lg-12 col-sm-12">
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>


    
    <?= $form->field($model, 'start_point')->hiddenInput(['maxlength' => true, 'placeholder' => 'Start Point','value'=>'Start Point'])->label(false) ?>
     <?= $form->field($model, 'start_point_coordinates')->hiddenInput(['maxlength' => true, 'placeholder' => 'Start Point','value'=>'17.446366'])->label(false) ?>
     <?= $form->field($model, 'start_point_lat')->hiddenInput(['placeholder' => 'Start Point Lat','value'=>'17.446366'])->label(false) ?>
    <?= $form->field($model, 'start_point_lng')->hiddenInput(['placeholder' => 'Start Point Lng','value'=>'17.446366'])->label(false) ?>
    <?= $form->field($model, 'end_point')->hiddenInput(['maxlength' => true, 'placeholder' => 'End Point','value'=>'End Point'])->label(false) ?>
    <?= $form->field($model, 'end_point_coordinates')->hiddenInput(['maxlength' => true, 'placeholder' => 'End Point','value'=>'17.446366'])->label(false)?>
    <?= $form->field($model, 'end_point_lat')->hiddenInput(['placeholder' => 'End Point Lat','value'=>'17.446366'])->label(false)?>
    <?= $form->field($model, 'end_point_lng')->hiddenInput(['placeholder' => 'End Point Lng','value'=>'17.446366'])->label(false) ?>
 
  
    <?php ActiveForm::end(); ?>

</div>
