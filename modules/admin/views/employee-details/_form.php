<?php

use app\modules\admin\models\Campus;
use app\modules\admin\models\DriverHasBus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;



?>

<div class="employee-details-form">

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


    <?php  echo $this->render('../bus-route/_campus_form', ['model' => $model,'form'=>$form]); ?>





    <?php
    if ($model->isNewRecord) {
        $designation_data = [];
    } else {
        $designation_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Designation::find()->orderBy('id')->asArray()->all(), 'id', 'title');
    }

    ?>



    <?php $form->field($model, 'designation_id')->widget(DepDrop::classname(), [
        'data' => $designation_data,
        'options' => ['id' => 'designation-id'],
        'pluginOptions' => [
            'depends' => ['campus-id'],
            'placeholder' => 'Select...',
            'url' => Url::to(['/admin/employee-details/employ-designation-data'])
        ]
    ]); ?>

<?= $form->field($model, 'designation_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Designation::find()
        ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose title')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


  
 

    <?= $form->field($model, 'employ_name')->textInput(['maxlength' => true, 'placeholder' => 'employ name']) ?>

    <?= $form->field($model, 'profile_picture')->textInput(['maxlength' => true, 'placeholder' => 'profile picture']) ?>

    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'Employee']) ?>

    <?= $form->field($model, 'age')->textInput(['placeholder' => 'Age']) ?>

    <?= $form->field($model, 'gender')->textInput(['placeholder' => 'Gender']) ?>

    <?= $form->field($model, 'blood_group_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>


    <?= $form->field($model, 'role')->dropDownList($model->getRoles()) ?>

    <!-- Select Bus Field -->


    <?php
        $driver_has_bus = DriverHasBus::find()->all();
        foreach($driver_has_bus  as $driver_has_bus_data){
            $busId[] =$driver_has_bus_data-> bus_id; 
        }
    echo $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()->orderBy('id')
        
        ->Where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['not in','id',$busId])
        ->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

 
    <?php if ($model->isNewRecord) { ?> <?php
                                        $forms = [];
                                        echo kartik\tabs\TabsX::widget([
                                            'items' => $forms,
                                            'position' => kartik\tabs\TabsX::POS_ABOVE,
                                            'encodeLabels' => false,
                                            'pluginOptions' => [
                                                'bordered' => true,
                                                'sideways' => true,
                                                'enableCache' => false,
                                            ],
                                        ]);
                                        ?>
    <?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(window).on("load", function() {
        $(".field-employeedetails-bus_id").hide();
    });
    $(document).ready(function() {

        $("#employeedetails-role").change(function() {
            $val = $("#employeedetails-role").val();
            if ($val == 'BusDriver') {
                $(".field-employeedetails-bus_id").show();
            } else {
                $(".field-employeedetails-bus_id").hide();
            }

        });
    });
</script>