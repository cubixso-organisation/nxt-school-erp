<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Campus;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;




/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\PayFeesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-pay-fees-search">

    <?php $form = ActiveForm::begin([
        'action' => ['pay-old-fee'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <div class="col-md-6 col-lg-6 col-sm-12">


    <?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
           ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Academic Year'),
            'disabled'=>!$model->isNewRecord ? true : false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Academic Year'); ?>
    </div>











    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
