<?php

use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AttendanceTimeTables */
/* @var $form yii\widgets\ActiveForm */
$campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

?>
 
<div class="attendance-time-tables-form">

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

   echo  $form->field($model, 'attendance_settings_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AttendanceSettings::find()
        ->where(['campus_id'=>$campusId])
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Attendance settings')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    
    
    ?>
 




<?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
       
    ->orderBy('id')->asArray()->all(), 'id', 'title'),
    'options' => ['placeholder' => Yii::t('app', 'Choose  class'),'id'=>'class-id'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>




<?php
if(!$model->isNewRecord) {
    $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
     ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
     ->andWhere(['id'=>$model->section_id])
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
    'depends'=>['class-id'],
    'placeholder'=>'Select...',
    'url'=>Url::to(['/admin/fee-structures/class-section-data'])
]

]); 
?>

<?php 

   echo  $form->field($model, 'day_id')
    ->dropDownList(array_merge(['' => 'Select Day'], $model->getDaysOptions()));

    
    ?>







<?php
if(!$model->isNewRecord) {
    $subject_timetable_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AttendanceTimeTables::find()
     ->andWhere(['id'=>$model->id])
     ->orderBy('id')->asArray()->all(), 'id', 'id');
} else {
    $subject_timetable_data = [];
}
?>







<?= $form->field($model, 'subject_timetable_id')->widget(DepDrop::classname(), [
    'data' => $subject_timetable_data,

    'options' => ['id' => 'subject-timetable-id'],
    'pluginOptions' => [
        'depends' => ['attendancetimetables-day_id','class-id', 'class-section-id'],
        'placeholder' => 'Select...',
        'url' => Url::to(['/admin/attendance-time-tables/subject-timetable-data']),
    ]
]); ?>









    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
