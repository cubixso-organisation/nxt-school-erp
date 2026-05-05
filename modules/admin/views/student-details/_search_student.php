<?php

use app\models\User;
use app\modules\admin\models\Campus;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\StudentDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-student-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['promote-students'],
        'method' => 'get',
    ]); ?>
 
    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <div class="row">


    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


    <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
            ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'),'id'=>'student-class-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    </div>


    <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
    <?php
    if(!$model->isNewRecord) {
        $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
         ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
         ->andWhere(['student_class_id'=>$model->student_class_id])
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


    </div>


 

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    </div>



    <?php ActiveForm::end(); ?>

</div>
