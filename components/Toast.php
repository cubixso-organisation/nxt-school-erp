<?php

namespace app\components;

use app\modules\admin\models\AuthSession;
use app\modules\admin\models\WebSetting;
use Yii;
use yii\base\Component;

class Toast extends Component
{

    public static function error($message)
    {
        Yii::$app->session->setFlash('error', $message);
    }

    public static function success($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }
}
