<?php

use app\models\User;
use app\modules\admin\models\Institutes;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CampusWebSettings */
/* @var $form yii\widgets\ActiveForm */

?>
 
<div class="campus-web-settings-form">

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
    if(User::isInstituteAdmin()){
    echo  $form->field($model, 'type_id')->checkBox();
    }
    
    ?> 

   

    <?php 
    if(User::isInstituteAdmin()){
    echo     $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')
            ->where(['in','id',(new Institutes())->getCampusByInstituteId()])
            ->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Campus'),'id'=>'campus-id'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); 
    }else if(User::isCampusAdmin()){

       echo $this->render('../bus-route/_campus_form', ['model' => $model,'form'=>$form]);

  

    }

    
    
    ?>



    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'setting_key')->dropDownList($model->getSettingKeyOptions(), ['prompt' => 'Select Key...']) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true, 'placeholder' => 'Value']) ?>


    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>


<script>
    $("#campuswebsettings-type_id").on("click", function() {

        if($("#campuswebsettings-type_id").is(':checked')){
            $(".field-campus-id").hide();

        }else{
            $(".field-campus-id").show();

        }
   
    });

 
</script>
