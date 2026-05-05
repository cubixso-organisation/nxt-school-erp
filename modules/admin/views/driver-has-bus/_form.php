<?php

use app\modules\admin\models\BusDetails;
use app\modules\admin\models\Campus;
use app\modules\admin\models\DriverHasBus;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DriverHasBus */
/* @var $form yii\widgets\ActiveForm */





 
?>

 
<div class="driver-has-bus-form">
    
    <div class="row">
    <div class="col-md-5">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>
  
    <?= $form->errorSummary($model); ?>
  
    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?php   $this->render('../bus-route/_campus_form', ['model' => $model,'form'=>$form]); ?>
    <div class="row">

    <div class="col-md-4">

    <?php 
$userId = [];
$busId = [];
$driver_has_bus = DriverHasBus::find()->all();
foreach ($driver_has_bus  as $driver_has_bus_data) {
    $userId[] =$driver_has_bus_data-> driver_id;
}
if($model->isNewRecord){
    if(!empty($userId)){
        echo $form->field($model, 'driver_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
            ->andWhere(['user_role'=>User::ROLE_BUS_DRIVER])
            ->andWhere(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['not in','id',$userId])
            
            ->orderBy('id')->asArray()->all(), 'id', 'first_name'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Driver')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Driver Name');
        
    }else{
        echo $form->field($model, 'driver_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
            ->andWhere(['user_role'=>User::ROLE_BUS_DRIVER])
            ->andWhere(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])
            
            ->orderBy('id')->asArray()->all(), 'id', 'first_name'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Driver')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Driver Name');
    }
    
}else{


    echo $form->field($model, 'driver_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
        ->andWhere(['user_role'=>User::ROLE_BUS_DRIVER])
        ->andWhere(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['id'=>$model->driver_id])
        ->orderBy('id')->asArray()->all(), 'id', 'first_name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Driver')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Driver Name');

}

    
    
    ?>

</div>

<div class="col-md-4">

<?php
    $driver_has_bus = DriverHasBus::find()->all();
foreach ($driver_has_bus  as $driver_has_bus_data) {
    $busId[] =$driver_has_bus_data-> bus_id;
}

if ($model->isNewRecord) {
    echo $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(BusDetails::find()->orderBy('id')
        ->Where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['not in','id',$busId])
        // ->andWhere(['status'=>BusDetails::current_status_active])
        ->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose eBus')],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ])->label('Assign Bus');
}else{

    echo $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()->orderBy('id')
        ->Where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        // ->andWhere(['id'=>$model->bus_id])
        ->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose eBus')],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ])->label('Assign Bus');

}

    ?>
</div>


<div class="col-md-4">

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
</div>

<?php if($model->isNewRecord){ ?><?php } ?>
<div class="col-md-12">

        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
  

    </div>

    <?php ActiveForm::end(); ?>




    </div>

    <div class="col-md-7">
    <?=  $this->render('index_form', ['searchModel' => $searchModel,'dataProvider'=>$dataProvider]); ?>

    </div>





    </div>







</div>





