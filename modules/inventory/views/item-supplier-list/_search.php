<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\search\ItemSupplierListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-item-supplier-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?php /* echo $form->field($model, 'contact_person_name')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Name']) */ ?>

    <?php /* echo $form->field($model, 'contact_person_phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Phone']) */ ?>

    <?php /* echo $form->field($model, 'contact_person_email')->textInput(['maxlength' => true, 'placeholder' => 'Contact Person Email']) */ ?>

    <?php /* echo $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false, 
                ],
            ]) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
