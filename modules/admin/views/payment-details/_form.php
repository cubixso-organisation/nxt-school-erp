<?php

use app\models\User;
use app\modules\admin\models\Campus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PaymentDetails */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="payment-details-form">

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

 
    
<?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
           ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Student class'),
            'id'=>'student-class-id',
            'disabled'=>!$model->isNewRecord ? true : false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    </div>



<?php
if (!$model->isNewRecord) {
    $class_section = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
    ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->orderBy('id')->asArray()->all(), 'id', 'section_name');
} else {
    $class_section = [];
}


?>

<div class="col-md-6 col-lg-6 col-sm-12">



<?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
    'data' => $class_section,
    'options'=>['id'=>'class-section-id','disabled'=>!$model->isNewRecord ? true : false],
    'pluginOptions'=>[
        'depends'=>['student-class-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/fee-structures/class-section-data'])
    ]
]);

?>
</div>

<?php
if (!$model->isNewRecord) {
    $student_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
    ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->orderBy('id')->asArray()->all(), 'id', 'student_name');
} else {
    $student_data = [];
}


?>
  <div class="col-md-6 col-lg-6 col-sm-12">

<?= $form->field($model, 'student_id')->widget(DepDrop::classname(), [
    'data' => $student_data,
    'options'=>['id'=>'student_id','multiple'=>false,'disabled'=>!$model->isNewRecord ? true : false],
    'pluginOptions'=>[
        'depends'=>['class-section-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/student-details/student-data-by-class-section'])
    ]
]);

?>
  </div>






  <div class="col-md-6 col-lg-6 col-sm-12">
<?php
if (!$model->isNewRecord) {
    $pay_fees =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\PayFees::find()
       ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

    ->orderBy('id')->asArray()->all(), 'id', 'id');
} else {
    $pay_fees = [];
}

?>


<?= $form->field($model, 'pay_fees_id')->widget(DepDrop::classname(), [
    'data' => $pay_fees,
    'options'=>['id'=>'pay_fees_id','disabled'=>!$model->isNewRecord ? true : false],
    'pluginOptions'=>[
        'depends'=>['student_id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/payment-details/pay-fee-id-data'])
    ]
]);

?>
  </div>



  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'paid_reference_number')->textInput(['maxlength' => true, 'placeholder' => 'Paid Reference Number','value'=>!empty($mode->paid_reference_numberl) ? $mode->paid_reference_numberl : rand(11111111, 99999999),'disabled'=>!$model->isNewRecord ? true : false]) ?>
  </div>


  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'payment_mode')->dropDownList($model->gePaymentModeOptions()) ?>

  </div>

  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'paid_amount')->textInput(['placeholder' => 'Amount'])->label('Amount') ?>
  </div>
 

  <div class="col-md-6 col-lg-6 col-sm-12">
    <?php $form->field($model, 'balance_amount')->textInput(['placeholder' => 'Balance Amount','readonly'=>true]) ?>
  </div>
  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
  </div>


  <div class="col-md-6 col-lg-6 col-sm-12">

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
  </div>

<?php if ($model->isNewRecord) { ?><?php } ?>   
<div class="col-md-12 col-lg-12 col-sm-12">

<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

    </div>

  
    <?php ActiveForm::end(); ?>

</div>
