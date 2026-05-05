<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\forms\UserForm */
/* @var $roles array */

$this->title = "Update {$model->fullName}";
$this->params['breadcrumbs'][] = ['label' => 'Key Person', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['update', 'id' => $model->id]];
$this->params['heading'] = 'Key Person';
$this->params['subheading'] = $model->fullName;
?>
<div class="user-update">
	
	<?= $this->render('key_person_form', [
		'model' => $model,
	]) ?>

</div>
