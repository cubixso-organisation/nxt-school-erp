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
<?php
$flashMessages = Yii::$app->session->getAllFlashes();

foreach ($flashMessages as $key => $message) {
	if ($key == 'error') {
		echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
	}
}
?>
<div class="user-form card">
	<div class="card-body">
		<?php $form = ActiveForm::begin([
			'layout' => 'horizontal',
			'enableAjaxValidation' => false,
		]); ?>
		<?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']); ?>

		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<div class="form-group">
			<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
			<span style="margin-left: 170px;color:red; ">Password is same as contact no.</span>
		</div>


		<?= $form->field($model, 'designation_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'user_role')->dropDownList(['Librarian' => 'Librarian']) ?>


		<?= $form->field($model, 'status')->dropDownList(User::getStatusesList()) ?>

		<div class="card-footer text-right">
			<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>