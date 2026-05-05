<?php

use app\modules\admin\models\ActivationModules;
use app\modules\admin\models\EducationalInstitutionTypes;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\web\JsExpression;
use app\modules\admin\models\WebSetting;



/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Institutes */
/* @var $form yii\widgets\ActiveForm */



?>



<div class="institutes-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model); ?>
    <?php
    if (!empty($invalidEmail) && $invalidEmail == 1) {
        echo "Invalid Email or Email Not sent to user please try again";
    }
    ?>

    <div class="row">

        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'name_of_the_educational_Institution')->textInput(['maxlength' => true, 'placeholder' => 'Title']) ?>
        </div>



        <div class="col-md-6  col-sm-6 col-xs-12">
            <?= $form->field($model, 'subscription_type')->dropDownList($model->getSubscriptionTypeOptions(), ['prompt' => 'Select Subscription Type..', 'id' => 'subscription-type-id']) ?>
        </div>




        <div class="col-md-6  col-sm-6 col-xs-12">


            <?php
            if ($model->isNewRecord) {
                $user_id_data =  [];
            } else {
                $user_id_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username');
            }

            ?>



            <?php $form->field($model, 'user_id')->widget(DepDrop::classname(), [
                'data' => $user_id_data,
                'options' => ['id' => 'user-id'],
                'pluginOptions' => [
                    'depends' => ['subscription-type-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/users/user-data'])
                ]
            ]);

            ?>
        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">





            <?= $form->field($model, 'educational_institution_type_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(EducationalInstitutionTypes::find()->where(['institute_id' => null])->andWHere(['status' => EducationalInstitutionTypes::STATUS_ACTIVE])->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Educational institution types')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


            <?= $form->field($model, 'onboarding_date')->widget(\kartik\date\DatePicker::classname(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose onboarding date'),
                    'id' => 'onboarding_date',
                    'value' => !empty($model->onboarding_date) && $model->onboarding_date != '0000-00-00' ? $model->onboarding_date : date('Y-m-d'),
                ],
                'pluginOptions' => [
                    'autoclose' => true,

                    'format' => 'yyyy-mm-dd',
                ]
            ]); ?>

<?= $form->field($model, 'expiry_date')->widget(\kartik\date\DatePicker::classname(), [
    'options' => [
        'placeholder' => Yii::t('app', 'Choose Expiry date'),
        'id' => 'expiry_date',
    ],
    'value' => !empty($model->expiry_date) && $model->expiry_date != '0000-00-00' ? $model->expiry_date : date('Y-m-d'),
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
    ]
]); ?>



        </div>




        <div class="col-md-6  col-sm-6 col-xs-12">


            <?= $form->field($model, 'registration_number')->textInput(['maxlength' => true, 'placeholder' => 'Registration Number']) ?>
        </div>




        <div class="col-md-6  col-sm-6 col-xs-12">





            <?= $form->field($model, 'activation_modules')->checkboxList($model->getActionModeOptions(), [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $activationModules = ActivationModules::find()->where(['institute_id' => isset($_GET['id']) ? $_GET['id'] : ''])
                        ->andWhere(['activation_modules' => $value])
                        ->andWhere(['status' => ActivationModules::STATUS_ACTIVE])
                        ->one();
                    if (!empty($activationModules)) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }
                    return "<input type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}";
                }
            ]) ?>



        </div>



        <div class="col-md-6  col-sm-6 col-xs-12">





            <?php

            echo $form->field($model, 'registration_document')->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => ['image/*', 'pdf']],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [
                        $model->registration_document
                    ],
                    'initialPreviewAsData' => true,

                    'overwriteInitial' => true,

                    'showUpload' => false,
                ]
            ]);


            ?>




        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">
            <?= $form->field($model, 'institute_code')->textInput(['maxlength' => true, 'placeholder' => 'institute code', 'value' => !empty($model->institute_code) ? $model->institute_code : rand(11111111, 99999999)])  ?>
        </div>





        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'country_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Country::find()->orderBy('id')->asArray()->all(), 'id', 'country_name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Country'), 'id' => 'country-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">

            <?php
            if ($model->isNewRecord) {
                $state_data =  [];
            } else {
                $state_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state_name');
            }

            ?>



            <?= $form->field($model, 'state_id')->widget(DepDrop::classname(), [
                'data' => $state_data,
                'options' => ['id' => 'state-id'],
                'pluginOptions' => [
                    'depends' => ['country-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/district/state-data'])
                ]
            ]);

            ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12">


            <?php
            if ($model->isNewRecord) {
                $district_data = [];
            } else {
                $district_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\District::find()
                    ->where(['state_id' => $model->state_id])
                    ->orderBy('id')->asArray()->all(), 'id', 'name');
            }

            ?>



            <?= $form->field($model, 'district_id')->widget(DepDrop::classname(), [
                'data' => $district_data,
                'options' => ['id' => 'district-id'],
                'pluginOptions' => [
                    'depends' => ['state-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/district/district-data'])
                ]
            ]);

            ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode']) ?>
        </div>






        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
        </div>


        <div class="col-md-12  col-sm-12 col-xs-12">

            <?php
            $setting = new WebSetting();
            $map_key = $setting->getSettingBykey('google_map_api_key');

            ?>
            <?= $form->field($model, 'coordinates')->widget('\pigolab\locationpicker\CoordinatesPicker', [
                'key' => $map_key, // optional , Your can also put your google map api key
                'options' => [
                    'style' => 'width: 100%; height: 400px', // map canvas width and height
                ],

                'enableSearchBox' => true, // Optional , default is true
                'searchBoxOptions' => [ // searchBox html attributes
                    'style' => 'width: 300px;', // Optional , default width and height defined in css coordinates-picker.css
                ],
                'mapOptions' => [
                    // set google map optinos
                    'rotateControl' => true,
                    'scaleControl' => false,
                    'streetViewControl' => true,
                    'mapTypeId' => new JsExpression('google.maps.MapTypeId.SATELLITE'),
                    'heading' => 90,
                    'tilt' => 45,

                    'mapTypeControl' => true,
                    'mapTypeControlOptions' => [
                        'style' => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
                        'position' => new JsExpression('google.maps.ControlPosition.TOP_CENTER'),
                    ],
                ],
                'clientOptions' => [
                    'location' => [

                        'latitude' => isset($model->latitude) ? $model->latitude : '17.446366',
                        'longitude' => isset($model->longitude) ? $model->longitude : '78.392414',
                    ],
                    'radius' => 3000,
                    'addressFormat' => 'street_number',
                    'onchanged' => new JsExpression('function(currentLocation, radius, isMarkerDropped) {
                    var addressComponents = $(this).locationpicker("map").location.addressComponents;
                    console.log(addressComponents);
                    function addressUpdated(addressComponents) {
                        var street = addressComponents.addressLine1;
                        var city = addressComponents.city;
                        var state = addressComponents.stateOrProvince;
                        var country = addressComponents.country;

                      }
                      document.getElementById("store-street").value = addressComponents.addressLine1;
                      document.getElementById("store-state").value = addressComponents.stateOrProvince;
                      document.getElementById("store-country_code").value = addressComponents.country;
                      document.getElementById("store-city").value = addressComponents.city;
                      document.getElementById("store-post_code").value = addressComponents.postalCode;
                      //addressUpdated(addressComponents);


                }'),
                    'oninitialized' => new JsExpression('function(component) {
                    var addressComponents = $(component).locationpicker("map").location.addressComponents;
                    console.log(addressComponents);
                    //addressUpdated(addressComponents);
                }'),

                    'inputBinding' => [
                        'latitudeInput' => new JsExpression("$('#" . Html::getInputId($model, "lat") . "')"),
                        'longitudeInput' => new JsExpression("$('#" . Html::getInputId($model, "lng") . "')"),
                        'radiusInput' => new JsExpression("$('#" . Html::getInputId($model, "delivery_radius") . "')"),


                    ],

                ],
            ]); ?>
        </div>







        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'lat')->textInput(['placeholder' => 'Lat']) ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'lng')->textInput(['placeholder' => 'Lng']) ?>

        </div>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'coordinates')->textInput(['placeholder' => 'Coordinates']) ?>
        </div>






        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'name_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Authorized']) ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12">
            <?= $form->field($model, 'designation_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Designation Of The Authorized']) ?>
        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'contact_number_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number Of The Authorized']) ?>
        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'email_id_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Email Id Of The Authorized']) ?>
        </div>



        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'aadhaar_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Aadhaar Of The Authorized']) ?>
        </div>






        <div class="col-md-6  col-sm-6 col-xs-12" id="name_of_the_contact_id">

            <?= $form->field($model, 'name_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Contact']) ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12" id="designation_of_the_contact_id">

            <?= $form->field($model, 'designation_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Designation Of The Contact']) ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12" id="contact_number_of_the_contact_id">

            <?= $form->field($model, 'contact_number_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number Of The Contact']) ?>
        </div>





        <?php $form->field($model, 'fee_receipt_content')->textarea(['rows' => 6]) ?>





        <div class="col-md-6  col-sm-6 col-xs-12">





            <?php







            echo $form->field($model, 'school_logo')->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => ['image/*']],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'initialPreview' => [
                        $model->school_logo
                    ],
                    'initialPreviewAsData' => true,

                    'overwriteInitial' => true,

                    'showUpload' => false,
                ]
            ]);









            ?>


        </div>











        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
        </div>
        <div class="col-md-12  col-sm-12 col-xs-12">

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    $(window).on("load", function() {
        $(".field-institutes-educational_institution_type_id").hide();
        $("#name_of_the_contact_id").hide();
        $("#designation_of_the_contact_id").hide();
        $("#contact_number_of_the_contact_id").hide();
        $(".field-expiry_date").hide();
        $(".field-onboarding_date").hide();

        // Check if the subscription type is already "individual institution"
        $val = $("#subscription-type-id").val();
        if ($val == "<?= Institutes::subscription_type_individual_institution ?>") {
            $(".field-institutes-educational_institution_type_id").show();
            $("#name_of_the_contact_id").show();
            $("#designation_of_the_contact_id").show();
            $("#contact_number_of_the_contact_id").show();
            $("#institutes-fee_receipt_content").show();
            $(".field-expiry_date").show();
            $(".field-onboarding_date").show();
        } else {
            $(".field-expiry_date").show();
            $(".field-onboarding_date").show();
        }
    });

    $(document).ready(function() {
        $("#subscription-type-id").change(function() {
            $val = $("#subscription-type-id").val();
            if ($val == "<?= Institutes::subscription_type_individual_institution ?>") {
                $(".field-institutes-educational_institution_type_id").show();
                $("#name_of_the_contact_id").show();
                $("#designation_of_the_contact_id").show();
                $("#contact_number_of_the_contact_id").show();
                $("#institutes-fee_receipt_content").show();
                $(".field-expiry_date").show();
                $(".field-onboarding_date").show();
            } else {
                $(".field-institutes-educational_institution_type_id").hide();
                $("#name_of_the_contact_id").hide();
                $("#designation_of_the_contact_id").hide();
                $("#contact_number_of_the_contact_id").hide();
                $("#institutes-fee_receipt_content").hide();
                $(".field-expiry_date").hide();
                $(".field-onboarding_date").hide();
            }
        });
    });
</script>