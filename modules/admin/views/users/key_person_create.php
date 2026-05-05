<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\forms\UserForm */

$this->title  = 'Create Key Person';
$this->params['breadcrumbs'][] = ['label' => 'Key Person', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['heading']       = 'Key Person';
$this->params['subheading']    = 'Add New';
?>
<div class="user-create">

	<?= $this->render('key_person_form', [
		'model' => $model,
	]) ?>

</div>
