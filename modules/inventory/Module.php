<?php

namespace app\modules\inventory;
use Yii;

/**
 * inventory module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $layout = '@app/modules/admin/views/layouts/main';
    public $controllerNamespace = 'app\modules\inventory\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::configure($this, require(__DIR__ . '/config/main.php'));

		// reconfigure service container.
		Yii::configure(Yii::$container, require(__DIR__ . '/config/container.php'));

		// set bootstrap version to 4 for Kartik widgets.
		Yii::$app->params['bsVersion'] = 4;
		
		// change error action to match admin styles.
		Yii::$app->errorHandler->errorAction = 'admin/dashboard/error';
    }
}
