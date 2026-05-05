<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Campus;
use app\modules\admin\models\User;
use kartik\depdrop\DepDrop;

?>

<style type="text/css">
    #printable {
        display: none;
    }

    @media print {
        .non-printable {
            display: none;
        }

        #printable {
            display: block;
        }
    }
</style>



<div class="row">

    <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

        <button type="button" class="btn btn-success non-printable" id="print-div-btn">Print</button>

    </div>
</div>

<div class="student-details-form" id="printableArea">



    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <div class="row">







        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
            <?= $form->field($model, 'admission_number')->textInput(['maxlength' => true, 'placeholder' => 'Admission Number']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

            <?= $form->field($model, 'rool_number')->textInput(['maxlength' => true, 'placeholder' => 'Roll Number']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

            <?= $form->field($model, 'profile_photo')->textInput(['maxlength' => true, 'placeholder' => 'Profile Photo']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'student_name')->textInput(['maxlength' => true, 'placeholder' => 'Student Name']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender']) ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">




            <?= $form->field($model, 'date_of_birth')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Date Of Birth'),
                        'autoclose' => true,
                    ]
                ],
            ]); ?>



        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'category')->textInput(['maxlength' => true, 'placeholder' => 'Category']) ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'religion')->textInput(['maxlength' => true, 'placeholder' => 'Religion']) ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'caste')->textInput(['maxlength' => true, 'placeholder' => 'Caste']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) ?>
        </div>



        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->andWhere(['is_agent' => null])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'student-class-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
            <?php
            if (!$model->isNewRecord) {
                $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->andWhere(['student_class_id' => $model->student_class_id])
                    ->orderBy('id')->asArray()->all(), 'id', 'section_name');
            } else {
                $section_data = [];
            }


            ?>

            <?=
            $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                'data' => $section_data,
                'options' => ['id' => 'class-section-id'],
                'pluginOptions' => [
                    'depends' => ['student-class-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/fee-structures/class-section-data'])
                ]

            ]);
            ?>


        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">



            <?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                    ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>



        </div>




        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'admission_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Admission Date'),
                        'autoclose' => true,
                    ]
                ],
            ]); ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

            <?= $form->field($model, 'blood_group_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'student_house')->textInput(['maxlength' => true, 'placeholder' => 'Student House']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'height')->textInput(['maxlength' => true, 'placeholder' => 'Height']) ?>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'weight')->textInput(['maxlength' => true, 'placeholder' => 'Weight']) ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'national_Identification_number')->textInput(['placeholder' => 'National Identification Number']) ?>
        </div>




        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'mother_tongue')->textInput(['maxlength' => true, 'placeholder' => 'Mother Tongue']) ?>
        </div>


        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'identification_marks')->textarea(['rows' => 6]) ?>


        </div>



        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'previous_school')->textInput(['maxlength' => true, 'placeholder' => 'Previous School']) ?>
        </div>



        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


            <?= $form->field($model, 'old_admission_number')->textInput(['maxlength' => true, 'placeholder' => 'Old Admission Number']) ?>
        </div>











        <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
            <?= $form->field($model, "hostal_is_required")->dropDownList($model->getHostelRequiredOptions())->label('Hostel Is Required') ?>
        </div>



        <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
            <?= $form->field($model, "bus_transport_required")->dropDownList($model->getTransportRequiredOptions()) ?>
        </div>

        <div class="row" id="bus-details">


            <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">


                <?= $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()
                        ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                        ->orderBy('id')->asArray()->all(), 'id', 'title'),
                    'options' => ['placeholder' => Yii::t('app', 'Choose Bus details'), 'id' => 'bus-id'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

            </div>




            <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">

                <?= $form->field($model, 'bus_route_id')->widget(DepDrop::classname(), [
                    'data' => [],
                    'options' => ['id' => 'bus-route-id'],
                    'pluginOptions' => [
                        'depends' => ['bus-id'],
                        'placeholder' => 'Select...',
                        'url' => Url::to(['/admin/student-has-bus/bus-route-data'])
                    ]
                ]); ?>
            </div>





            <?php
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
            >

        </div>





        <?php ActiveForm::end(); ?>

    </div>
    <?php echo  $this->render('../parent-details/_form_common_parent', ['model' => $ParentDetails]); ?>

</div>







<script>
    $(window).on("load", function() {
        $("#bus-details").hide();

    });
    $(document).ready(function() {


        $("#studentdetails-bus_transport_required").change(function() {
            $val = $("#studentdetails-bus_transport_required").val();
            if ($val == 1) {
                $("#bus-details").show();

            }


        });




    });

    $(document).on('click', '#print-div-btn', function(e) {
        e.preventDefault();

        var $this = $(this);
        var originalContent = $('body').html();
        var printArea = $this.parents('#printableArea').html();

        $('body').html(printArea);
        window.print();
        $('body').html(originalContent);
    });
</script>