<?php

use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Campus;
use app\modules\admin\models\DriverHasBus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

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


    <?php
    if ($model->isNewRecord) {
        $designation_data = [];
    } else {
        $designation_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Designation::find()->orderBy('id')->asArray()->all(), 'id', 'title');
    }

    ?>


    <div class="row">




        <div class="col-md-6">
            <?= $form->field($model, 'employ_name')->textInput(['maxlength' => true, 'placeholder' => 'employ name'])->label('Driver Name') ?>
        </div>

        <div class="col-md-6">

            <?= $form->field($model, 'license_number')->textInput(['maxlength' => true, 'placeholder' => 'license number']) ?>
            <?= $form->field($model, 'is_driver')->hiddenInput(['value' => 1])->label(false) ?>

        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'aadhar_number')->textInput(['maxlength' => true, 'placeholder' => 'aadhar number'])->label('aadhar number') ?>
        </div>


        <?php $form->field($model, 'email')->label(false)->hiddenInput(['maxlength' => true, 'placeholder' => 'Email', "Value" => !empty($model->email) ? $model->email : rand(111111, 999999) . '@example.com']) ?>


        <div class="col-md-6">


            <?= $form->field($model, 'employee_id')->label(false)->hiddenInput(['maxlength' => true, 'placeholder' => 'Employee', 'value' => !empty($model->employee_id) ? $model->employee_id : rand(111111, 999999)]) ?>
        </div>

        <div class="col-md-6">

            <?= $form->field($model, 'age')->textInput(['placeholder' => 'Age']) ?>
        </div>

        <div class="col-md-6">


            <?= $form->field($model, 'gender')->dropDownList($model->getGender()) ?>
        </div>




        <?php $form->field($model, 'blood_group_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
            'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>



        <div class="col-md-6">


            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
        </div>






        <?php
        $driver_has_bus = DriverHasBus::find()->all();
        foreach ($driver_has_bus  as $driver_has_bus_data) {
            $busId[] = $driver_has_bus_data->bus_id;
        }

        if ($model->isNewRecord) {
            if (!empty($busId)) {
                $form->field($model, 'bus_id')->label('Assign Bus')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()->orderBy('id')
                        ->Where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['NOT IN', 'id', $busId])
                        ->asArray()->all(), 'id', 'title'),
                    'options' => ['placeholder' => Yii::t('app', 'Choose eBus')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]);
            }
        }

        ?>


        <div class="col-md-6">



            <?php



            echo $form->field($model, 'profile_picture')->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => ['image/*']],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [
                        $model->profile_picture
                    ],
                    'initialPreviewAsData' => true,

                    'overwriteInitial' => true,

                    'showUpload' => false,
                ]
            ]);




            ?>





        </div>



        <div class="row">

            <div class="col-md-6">



                <?php



                echo $form->field($model, 'aadhar_image')->widget(FileInput::classname(), [
                    'options' => ['multiple' => false, 'accept' => ['image/*']],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'initialPreview' => [
                            $model->aadhar_image
                        ],
                        'initialPreviewAsData' => true,

                        'overwriteInitial' => true,

                        'showUpload' => false,
                    ]
                ]);




                ?>





            </div>



            <div class="col-md-6">



                <?php





                echo $form->field($model, 'aadhar_back')->widget(FileInput::classname(), [
                    'options' => ['multiple' => false, 'accept' => ['image/*']],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'initialPreview' => [
                            $model->aadhar_back
                        ],
                        'initialPreviewAsData' => true,

                        'overwriteInitial' => true,

                        'showUpload' => false,
                    ]
                ]);






                ?>



            </div>






            <div class="col-md-6">



                <?php

                echo $form->field($model, 'licence_front')->widget(FileInput::classname(), [
                    'options' => ['multiple' => false, 'accept' => ['image/*', 'pdf']],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'initialPreview' => [
                            $model->licence_front,
                        ],
                        'initialPreviewAsData' => true,

                        'overwriteInitial' => true,

                        'showUpload' => false,
                    ],
                ]);

                ?>



            </div>


            <div class="col-md-6">



                <?php

                echo $form->field($model, 'licence_back')->widget(FileInput::classname(), [
                    'options' => ['multiple' => false, 'accept' => ['image/*', 'pdf']],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'initialPreview' => [
                            $model->licence_back,
                        ],
                        'initialPreviewAsData' => true,

                        'overwriteInitial' => true,

                        'showUpload' => false,
                    ],
                ]);

                ?>



            </div>







        </div>




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
        <?php } ?>

        <div class="col-md-12">

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>