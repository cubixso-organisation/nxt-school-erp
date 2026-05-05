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

		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'username',); ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'designation_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'password')->passwordInput() ?>

		<?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

		<?= $form->field($model, 'user_role')->dropDownList($model->getRoles()) ?>


		<?php

		if (User::isCampusAdmin()) {
			echo $form->field($model, 'module_id')->checkboxList($model->getActionModeOptions(), [
				'item' => function ($index, $label, $name, $checked, $value) {
					$activationModules = UserHasModules::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
						->andWhere(['module_id' => $value])
						->andWhere(['status' => UserHasModules::STATUS_ACTIVE])
						->one();
					if (!empty($activationModules)) {
						$checked = 'checked';
					} else {
						$checked = '';
					}
					return "<input type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}";
				}
			])->label('Module');
		}
		?>




		<?= $form->field($model, 'status')->dropDownList(User::getStatusesList()) ?>





		<div class="card-footer text-right">
			<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>