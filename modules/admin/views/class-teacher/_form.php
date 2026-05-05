<?php

use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Campus;
use app\modules\admin\models\StudentClass;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ClassTeacher */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="class-teacher-form">

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
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
        ->where(['status'=>StudentClass::STATUS_ACTIVE])
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'),'id'=>'student-class-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?> 




<?php
    if(!$model->isNewRecord) {
        $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
         ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])

         ->orderBy('id')->asArray()->all(), 'id', 'section_name');
    } else {
        $section_data = [];
    }


?>

<?= 

$form->field($model, 'section_id')->widget(DepDrop::classname(), [
    'data' => $section_data,
    'options'=>['id'=>'class-section-id'],
    'pluginOptions'=>[
        'depends'=>['student-class-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/fee-structures/class-section-data'])
    ]
]);

?> 


<?php
$isNewRecord = $model->isNewRecord; // This will be true if the model is a new record
?>

<?= $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()
        ->where(['status'=>StudentClass::STATUS_ACTIVE])
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details')],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => $isNewRecord, // Multiple selection if it's a new record
        ],
    ]); ?>




    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
