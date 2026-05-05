<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CentralDbSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-central-db-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>

    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true, 'placeholder' => 'School Name']) ?>

    <?php /* echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address']) */ ?>

    <?php /* echo $form->field($model, 'domain')->textInput(['maxlength' => true, 'placeholder' => 'Domain']) */ ?>

    <?php /* echo $form->field($model, 'db_username')->textInput(['maxlength' => true, 'placeholder' => 'Db Username']) */ ?>

    <?php /* echo $form->field($model, 'db_password')->textInput(['maxlength' => true, 'placeholder' => 'Db Password']) */ ?>

    <?php /* echo $form->field($model, 'db_name')->textInput(['maxlength' => true, 'placeholder' => 'Db Name']) */ ?>

    <?php /* echo $form->field($model, 'sub_domain')->textInput(['maxlength' => true, 'placeholder' => 'Sub Domain']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <?php /* echo $form->field($model, 'created_user_id')->textInput(['placeholder' => 'Created User']) */ ?>

    <?php /* echo $form->field($model, 'updated_user_id')->textInput(['placeholder' => 'Updated User']) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
