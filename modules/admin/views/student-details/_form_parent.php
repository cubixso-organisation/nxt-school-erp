<?php

use app\models\User;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

?>

<div class="card">
<div class="card-body">


	<?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
    ]); ?> 
<?= $form->errorSummary($model); ?>


    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label('Parent Name') ?>
		
    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>


		<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList(User::getStatusesList()) : '' ?>


		
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => 'btn btn-primary']) ?>

   
        


	<?php ActiveForm::end(); ?>
	</div>
</div>
