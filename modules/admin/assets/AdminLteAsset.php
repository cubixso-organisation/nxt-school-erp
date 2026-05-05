<?php

namespace app\modules\admin\assets;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\jui\JuiAsset;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

class AdminLteAsset extends \yii\web\AssetBundle
{
	public $sourcePath = 'themes/school-management';

	public $css = [
	
		'assets/plugins/bootstrap/css/bootstrap.min.css',
		'assets/plugins/feather/feather.css',
		'assets/plugins/icons/flags/flags.css',
		'assets/plugins/fontawesome/css/fontawesome.min.css',
		'assets/plugins/fontawesome/css/all.min.css',
		'assets/css/style.css',
		'assets/css/admin-style.css',
		'assets/css/admin.css',




	];

	public $js = [
		'assets/plugins/bootstrap/js/bootstrap.bundle.min.js',
		'assets/js/feather.min.js',
		'assets/plugins/slimscroll/jquery.slimscroll.min.js',
		'assets/js/script.js'

  
	
	];

	public $depends = [
		JqueryAsset::class,
	

	];
}
