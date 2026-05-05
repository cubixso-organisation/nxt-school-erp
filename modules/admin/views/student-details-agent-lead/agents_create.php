<?php

use app\models\User;
use app\modules\admin\models\Campus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

?> 

<div class="card employee-details-form">

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

    <div class="col-md-4">

    <?= $form->field($model, 'employee_id')->label('Employee')->textInput(['maxlength' => true, 'readonly'=>true, 'placeholder' => 'Employee','value'=>!empty($model->employee_id) ? $model->employee_id : rand(11111111, 99999999)]) ?>
    </div>

<div class="col-md-4">

<?= $form->field($model, 'is_agent')->hiddenInput(['value'=>1])->label(false) ?>

    <?= $form->field($model, 'employ_name')->textInput(['maxlength' => true, 'placeholder' => 'employ name']) ?>
    </div>


    <div class="col-md-4">

    <?= $form->field($model, 'age')->textInput(['placeholder' => 'Age']) ?>
    </div>

    <div class="col-md-4">

    <?= $form->field($model, 'gender')->dropDownList($model->getGender()) ?>

    </div>

    <div class="col-md-4">

    <?= $form->field($model, 'blood_group_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
        </div>
        <div class="col-md-4">


    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) ?>
    </div>
    <div class="col-md-4">


    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
    </div>


    <div class="col-md-4">



<?php

echo $form->field($model, 'id_proof')->widget(FileInput::classname(), [
'options' => ['multiple' => false, 'accept' => ['image/*', 'pdf']],
'pluginOptions' => [
    'previewFileType' => ['image','pdf'], 'initialPreview' => [
        $model->id_proof,
    ],
    'initialPreviewAsData' => true,

    'overwriteInitial' => true,

    'showUpload' => false,
],
]);

?>

 
</div>


<div class="col-md-4">


<?= $form->field($model, 'agent_type')->dropDownList($model->getAgentTypePaymentType()) ?>

 
</div>
 





<div class="col-md-4">


<?php

echo $form->field($model, 'qr_code_file')->widget(FileInput::classname(), [
'options' => ['multiple' => false, 'accept' => ['image/*', 'pdf']],
'pluginOptions' => [
    'previewFileType' => ['image','pdf'], 'initialPreview' => [
        $model->qr_code_file,
    ],
    'initialPreviewAsData' => true,

    'overwriteInitial' => true,

    'showUpload' => false,
],
]);

?>

 
</div>
 

       <?php if ($model->isNewRecord) { ?> <?php
                                        $forms = [];
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
    <div class="col-md-12">

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>

