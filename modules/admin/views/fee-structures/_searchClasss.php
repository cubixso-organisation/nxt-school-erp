<?php

use app\modules\admin\models\Campus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
 
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\FeeStructuresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-fee-structures-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


 

    <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere((['is_agent'=>null]))
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Student class'),
            'id'=>'student-class-id',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>



<?= $form->field($model, 'class_section_id')->widget(DepDrop::classname(), [
    // 'data' => $state_data,
    'options'=>['id'=>'class-section-id'],
    'pluginOptions'=>[
        'depends'=>['student-class-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/admin/fee-structures/class-section-data'])
    ]
]);?>







   

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Get'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
