<?php

namespace app\modules\childassessment\controllers;

use yii\web\Controller;

/**
 * Default controller for the `childassessment` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
