<?php

use app\models\User;
use app\modules\admin\models\Campus;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\modules\admin\models\City;
use app\modules\admin\models\UserHasModules;

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
			'enableAjaxValidation' => false,
		]); ?>


		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

		<!-- <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?> -->

		<?= $form->field($model, 'username', ['enableAjaxValidation' => false]); ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
		<div class="card-footer text-right">
			<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>