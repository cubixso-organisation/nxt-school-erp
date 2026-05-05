<?php

use app\models\User;
use app\modules\admin\models\Campus;

 echo  $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()
    ->where(['id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
    'options' => [
        // 'placeholder' => Yii::t('app', 'name of the educational Institution'),
        'id'=>'campus-id',
        'readonly' => true,
        'selected' => true

    
    ],
    'pluginOptions' => [
        'allowClear' => true,
        

    ],
]); 

 

?>