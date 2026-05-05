<?php

use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Campus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\file\FileInput;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TeacherDetails */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'StudentClassAttendance', 
        'relID' => 'student-class-attendance', 
        'value' => \yii\helpers\Json::encode($model->studentClassAttendances),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'TeacherHasStudents', 
        'relID' => 'teacher-has-students', 
        'value' => \yii\helpers\Json::encode($model->teacherHasStudents),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);  
?>
 
<div class="teacher-details-form">

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

 

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>


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







    <?= $form->field($model, 'class_id')->label('Grade')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
        ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
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
         ->andWhere(['student_class_id'=>$model->class_id])
         ->orderBy('id')->asArray()->all(), 'id', 'section_name');
    } else {
        $section_data = [];
    }


?>

<?=
  $form->field($model, 'section_id')->label('Section')->widget(DepDrop::classname(), [
    'data' => $section_data,
    'options'=>['id'=>'class-section-id'],
    'pluginOptions'=>[
        'depends'=>['student-class-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/fee-structures/class-section-data'])
    ]

]);  
?>


    <?= $form->field($model, 'id_number')->textInput(['maxlength' => true, 'placeholder' => 'Id Number']) ?>

    <?= $form->field($model, 'date_of_birth')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Date Of Birth'),
                'autoclose' => true
            ]
        ],
    ]); ?>




    <?= $form->field($model, 'gender')->dropDownList($model->getGenderOptions()) ?>


    <?= $form->field($model, 'blood_group_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'father_name')->textInput(['maxlength' => true, 'placeholder' => 'Father Name']) ?>

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
    <div class="row d-flex">
 
</div>
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
 