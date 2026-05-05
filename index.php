<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
ini_set('memory_limit', '10000M');

defined('COMMING_SOON') or define('COMMING_SOON', false);

defined('STATUS_SUCCESS') or define('STATUS_SUCCESS', '1');
defined('STATUS_FAILURE') or define('STATUS_FAILURE', '0');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/env.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

require __DIR__ . '/themes/jpgraph/src/jpgraph.php';
require __DIR__ . '/themes/jpgraph/src/jpgraph_bar.php';
define('BASE_PATH', __DIR__ . '/');
define('_MPDF_TEMP_PATH', '/vendor/mpdf/mpdf/tmp/mpdf/');
//$config = require __DIR__ . '/config/web.php';
$config = \yii\helpers\ArrayHelper::merge(
	require(__DIR__ . '/config/web.php'),
	require(__DIR__ . '/config/rbac.php')
);
define('UPLOAD_PATH', __DIR__ . '/uploads');
if (! file_exists(UPLOAD_PATH)) {
	mkdir(UPLOAD_PATH, 0777, true);
}



(new yii\web\Application($config))->run();
