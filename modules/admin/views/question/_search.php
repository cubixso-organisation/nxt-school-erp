<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\QuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'assessment_id')->textInput(['placeholder' => 'Assessment']) ?>

    <?= $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) ?>

    <?= $form->field($model, 'question_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->textInput(['placeholder' => 'Type']) ?>

    <?php /* echo $form->field($model, 'marks')->textInput(['placeholder' => 'Marks']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
