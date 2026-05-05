<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ParentDetails */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'ParentHasCampus', 
        'relID' => 'parent-has-campus', 
        'value' => \yii\helpers\Json::encode($model->parentHasCampuses),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]); 
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'StudentDetails', 
        'relID' => 'student-details', 
        'value' => \yii\helpers\Json::encode($model->studentDetails),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>  
 
<div class="parent-details-form">

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
    <div class="row">



    <div class="col-md-6 col-lg-6 col-sm-12">
    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number']) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'name_of_the_father')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Father']) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'name_of_the_mother')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Mother']) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">


    <?php

echo $form->field($model, 'profile_image')->widget(FileInput::classname(), [
    'options' => ['multiple' => false, 'accept' => ['image/*']],
    'pluginOptions' => [
        'previewFileType' => 'image', 'initialPreview' => [
            $model->profile_image
        ],
        'initialPreviewAsData' => true,

        'overwriteInitial' => true,

        'showUpload' => false,
    ]
]);


?> 
    </div>




    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'current_address')->textarea(['rows' => 6]) ?>
    </div>


    <div class="col-md-6 col-lg-6 col-sm-12">


    <?= $form->field($model, 'permanent_address')->textarea(['rows' => 6]) ?>
    </div>

 

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'father_education_qualification')->textarea(['rows' => 6]) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'mother_education_qualification')->textarea(['rows' => 6]) ?>
    </div>


    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'father_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Father Aadhaar Number']) ?>

    </div>


    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'mother_aadhaar_number')->textInput(['maxlength' => true, 'placeholder' => 'Mother Aadhaar Number']) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">


    <?= $form->field($model, 'father_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Father Occupation']) ?>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'mother_occupation')->textInput(['maxlength' => true, 'placeholder' => 'Mother Occupation']) ?>
    </div>

 

    

    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'blood_group_father')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'title', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    </div>


    <div class="col-md-6 col-lg-6 col-sm-12">


    <?= $form->field($model, 'blood_group_mother')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'title', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    </div>






    <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
    </div>

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

<?php } ?>   
<div class="col-md-12 col-lg-12 col-sm-12">

     <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        </div>



        </div>




  
    <?php ActiveForm::end(); ?>

</div>
