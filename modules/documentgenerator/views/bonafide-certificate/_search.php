<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\search\BonafideCertificateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-bonafide-certificate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'certificate_name')->textInput(['maxlength' => true, 'placeholder' => 'Certificate Name']) ?>

    <?= $form->field($model, 'header_left_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'header_center_text')->textarea(['rows' => 6]) ?>

    <?php /* echo $form->field($model, 'header_right_text')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'body_text')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'footer_right_text')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'right_sig')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'certificate_design')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'header_height')->textInput(['placeholder' => 'Header Height']) */ ?>

    <?php /* echo $form->field($model, 'footer_height')->textInput(['placeholder' => 'Footer Height']) */ ?>

    <?php /* echo $form->field($model, 'body_height')->textInput(['placeholder' => 'Body Height']) */ ?>

    <?php /* echo $form->field($model, 'body_width')->textInput(['placeholder' => 'Body Width']) */ ?>

    <?php /* echo $form->field($model, 'background_image')->textInput(['maxlength' => true, 'placeholder' => 'Background Image']) */ ?>

    <?php /* echo $form->field($model, 'template_type')->textInput(['placeholder' => 'Template Type']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
