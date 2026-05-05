<?php

use app\models\User;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \app\modules\admin\forms\UserForm
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="user-form card">
<div class="card-body">
	<?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
    ]); ?>

	
		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
		
		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
		
		<?=$form->field($model, 'username', ['enableAjaxValidation' => true]); ?>
		
		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'user_role')->hiddenInput(['maxlength' => true,'value'=>User::role_key_person])->label(false) ?>

		
		<?= $form->field($model, 'status')->dropDownList(User::getStatusesList()) ?>

	
		
		
	
	<div class="card-footer text-right">
		<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
	</div>
</div>
