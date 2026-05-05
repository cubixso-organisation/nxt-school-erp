<?php

use app\widgets\Block;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\admin\Module as AdminModule;

/* @var $this \yii\web\View */
?>

<?php Block::begin(['id' => 'content-header']) ?>
<div class="page-header">
<div class="row">
<div class="col">
<h3 class="page-title"><?= $this->title ?></h3>
<ul class="breadcrumb">
<?= Breadcrumbs::widget([
						'homeLink' => [
							'label' => 'Dashboard',
							'url' => ['/admin/dashboard'],
							'encode' => false,
						],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
						'tag' => 'ol',
						'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
						'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
						'options' => [
							'class' => 'breadcrumb float-sm-right',
						],
			]) ?>
							


</ul>
</div>
</div>
</div>
<?php Block::end(); ?>
