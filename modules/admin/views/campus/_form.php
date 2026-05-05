<?php

use app\modules\admin\models\Campus;
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
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Campus */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="campus-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>



    <div class="row">

        <div class="col-12">
            <?= $form->errorSummary($model); ?>
        </div>
        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>



        <div class="col-md-6  col-sm-6 col-xs-12">
            <?php

            if (User::isAdmin()) {
                echo  $form->field($model, 'institute_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->where(['subscription_type' => Institutes::subscription_type_group_of_institutions])->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'options' => ['placeholder' => Yii::t('app', 'Choose Institutes'), 'id' => 'institute-id'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            } elseif (User::isInstituteAdmin()) {
                echo  $form->field($model, 'institute_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->where(['subscription_type' => Institutes::subscription_type_group_of_institutions])
                        ->andWhere(['id' => (new Institutes())->getInstituteIdOfUser()])
                        ->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'options' => ['id' => 'institute-id', 'selected' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            }




            ?>
        </div>






        <div class="col-md-6  col-sm-6 col-xs-12">




<?= $form->field($model, 'educational_institution_type_id')->textInput(['maxlength' => true, 'placeholder' => 'Educational Institution Type ID']) ?>




        </div>


        <div class="col-md-6  col-sm-6 col-xs-12">


            <?= $form->field($model, 'name_of_the_educational_Institution')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Educational Institution']) ?>
        </div>

        <div class="col-md-6  col-sm-6 col-xs-12">

            <?php

            $users = Campus::find()->all();
            foreach ($users as $usersData) {
                $users_id[] = $usersData->user_id;
            }
            if (!empty($users_id)) {
                $user_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
                    ->where(['user_role' => User::ROLE_CAMPUS_ADMIN, 'status' => User::STATUS_ACTIVE])
                    ->andWhere(['NOT IN', 'id', $users_id])
                    ->andWhere(['create_user_id' => Yii::$app->user->identity->id])

                    ->orderBy('id')->asArray()->all(), 'id', 'username');
            } else {
                $user_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
                    ->where(['user_role' => User::ROLE_CAMPUS_ADMIN, 'status' => User::STATUS_ACTIVE])
                    ->andWhere(['create_user_id' => Yii::$app->user->identity->id])
                    ->orderBy('id')->asArray()->all(), 'id', 'username');
            }

            $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [

                'data' => $user_data,



                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
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
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'registration_number')->textInput(['maxlength' => true, 'placeholder' => 'Registration Number']) ?>
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

            <?= $form->field($model, 'campus_code')->textInput(['maxlength' => true, 'placeholder' => 'campus_code', 'value' => !empty($model->campus_code) ? $model->campus_code : rand(11111111, 99999999)]) ?>
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

            <?= $form->field($model, 'name_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Contact']) ?>
        </div>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'designation_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Designation Of The Contact']) ?>
        </div>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'contact_number_of_the_contact')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number Of The Contact']) ?>
        </div>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'email_id_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Email Id Of The Authorized']) ?>
        </div>
        <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'aadhaar_of_the_authorized')->textInput(['maxlength' => true, 'placeholder' => 'Aadhaar Of The Authorized']) ?>
        </div>
        <?php
        $loginUserFrom = Yii::$app->session->get('loginUserFrom');

        if (!empty($loginUserFrom)) { ?>

            <?= $form->field($model, 'admin_commision_percentage')->textInput(['maxlength' => true, 'placeholder' => 'Admin Commission Percentage']) ?>

        <?php } ?>


        <?php if (Yii::$app->user->identity->user_role != User::role_campus_sub_admin) { ?>
    <div class="col-md-6 col-sm-6 col-xs-12">

        <?= $form->field($model, 'onboarding_date')->widget(\kartik\date\DatePicker::classname(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose onboarding date'),
                'value' => !empty($model->onboarding_date) && $model->onboarding_date != '0000-00-00' ? $model->onboarding_date : date('Y-m-d'), // Set default to current date if empty or invalid
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
            ]
        ]); ?>

    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">

        <?= $form->field($model, 'expiry_date')->widget(\kartik\date\DatePicker::classname(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Choose expiry date'),
                'id' => 'expiry_date',
                'value' => !empty($model->expiry_date) && $model->expiry_date != '0000-00-00' ? $model->expiry_date : date('Y-m-d'), // Set default to current date if empty or invalid
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]); ?>

    </div>
<?php } ?>
        




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

                <?= $form->field($model, 'radius')->textInput(['placeholder' => ''])->label('Radius(In Meters)') ?>
            </div>
            <div class="col-md-6  col-sm-6 col-xs-12">

                <?= $form->field($model, 'coordinates')->textInput(['placeholder' => 'Coordinates']) ?>
            </div>

            <div class="col-md-6  col-sm-6 col-xs-12">

            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

</div>





            <?php $form->field($model, 'fee_receipt_content')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],

            ]); ?>






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
            <div class="col-md-12  col-sm-12 col-xs-12">

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
    </div>







    <?php ActiveForm::end(); ?>

</div>