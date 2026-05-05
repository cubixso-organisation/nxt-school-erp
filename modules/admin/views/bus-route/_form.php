<?php

use app\models\User;
use app\modules\admin\models\BusDetails;
use app\modules\admin\models\Campus;
use app\modules\admin\models\WebSetting;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\web\JsExpression;

$getCampusId =   User::getCampusesByUser(Yii::$app->user->identity->id);

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusRoute */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'StudentHasBus',
        'relID' => 'student-has-bus',
        'value' => \yii\helpers\Json::encode($model->studentHasBuses),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ] 
]);
?>  
<div class="row">
<div class="col-md-6">
<div class="bus-route-form">

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

    <div class="row">
   
  
    <div class="col-md-4 col-lg-4 col-sm-12">


    <?php


echo $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(BusDetails::find()
    ->where(['campus_id'=>$getCampusId])
    // ->andWhere(['status'=>BusDetails::current_status_active])

    ->orderBy('id')->asArray()->all(), 'id', 'title'),
    'options' => ['placeholder' => Yii::t('app', 'Choose Bus details')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
    <div class="col-md-4 col-lg-4 col-sm-12">
    <?= $form->field($model, 'point_name')->textInput(['maxlength' => true, 'placeholder' => 'Point Name']) ?>
    </div>





    <div class="col-md-4 col-lg-4 col-sm-12">

    <?= $form->field($model, 'short_order')->textInput(['placeholder' => 'Route Order'])->label('Route Order') ?>

    
    <?= $form->field($model, 'coordinates')->hiddenInput(['placeholder' => 'Lat','value'=>'17.446366,17.446366'])->label(false)?>


    <?= $form->field($model, 'lat')->hiddenInput(['placeholder' => 'Lat','value'=>'17.446366'])->label(false) ?>



    <?= $form->field($model, 'lng')->hiddenInput(['placeholder' => 'Lng','value'=>'17.446366'])->label(false) ?>
    
    </div>


<?php if ($model->isNewRecord) { ?>    <?php
    $forms = [

    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => false,
            'sideways' => false,
            'enableCache' => false,
        ],
    ]);
    ?>
<?php } ?>  
<div class="col-md-12 col-lg-12 col-sm-12">

      <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
  
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
<div class="col-md-6">


<?php echo $this->render('index_routs', ['searchModel' => $searchModel,'dataProvider'=>$dataProvider]); ?>


</div>
</div>




