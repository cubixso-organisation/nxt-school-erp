<?php

use app\models\User;
use app\modules\admin\models\base\Campus;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;

// $campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
// $campusName = Campus::find()->where(['id' => $campus_id])->one();
// $secondary = Yii::$app->user->identity->button_color_preference;
?>

<div class="container bg-light p-5 rounded shadow position-relative animate__animated animate__fadeIn">
<div class="row card shadow-lg p-4 rounded">
    <!-- Campus Title Section -->
    <div class="col-12 text-center mb-4 d-flex flex-wrap align-items-center justify-content-center">
        <!-- Logo -->
        <div class="logo-section me-3 mb-3 mb-md-0">
            <img src="<?= Html::encode($campus->school_logo) ?>" 
                 alt="School Logo" 
                 class="img-fluid rounded-circle border shadow-sm animate__animated animate__zoomIn" 
                 style="max-height: 100px; max-width: 100px;">
        </div>
        <!-- Heading -->
        <div class="heading-section">
            <h1 class="fw-bold animate__animated animate__bounceIn" style="font-size: 2rem; color: green;">
                <?= Html::encode($campus->name_of_the_educational_Institution) ?>
            </h1>
            <p class="text-muted fs-5 animate__animated animate__fadeInUp">
                Welcome! Please fill out the form to explore your next adventure!
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Form Content -->
        <div class="col-12">
            <?php $form = ActiveForm::begin([
                'id' => 'admission-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true,
                'fieldConfig' => ['options' => ['class' => 'form-group']]
            ]); ?>

            <?= $form->errorSummary($model, ['class' => 'alert alert-danger animate__animated animate__shakeX']); ?>
                <div class="row g-4">
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'student_name')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Enter Student Name',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>🧒 Student Name</strong>') ?>
                    </div>
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'parent_name')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Enter Name of Parent',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>👩‍👦 Parent Name</strong>') ?>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'contact_no')->textInput([
                            'maxlength' => 10,
                            'placeholder' => 'Enter Mobile Number',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>📞 Contact Number</strong>') ?>
                    </div>
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'email')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Enter Email',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>📧 Email</strong>') ?>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'next_class')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Enter Next Class',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>🎒 Next Class</strong>') ?>
                    </div>
                    <div class="col-12 col-sm-6">
                        <?= $form->field($model, 'previous_class')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Enter Last Completed Class',
                            'class' => 'form-control shadow-sm'
                        ])->label('<strong>📚 Previous Class</strong>') ?>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-sm-6">
                    <?= $form->field($model, 'dob')->widget(DateControl::classname(), [
    'type' => DateControl::FORMAT_DATE,
    'saveFormat' => 'php:Y-m-d',
    'ajaxConversion' => true,
    'options' => [
        'options' => [
            'placeholder' => 'Select Date of Birth',
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            'clearBtn' => true,
        ],
    ],
])->label('<strong>🎂 Date of Birth</strong>') ?>

                    </div>
                </div>



            </div>
            <div class="row g-4">
                <div class="col-12">
                    <?= $form->field($model, 'address')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Enter Full Address',
                        'class' => 'form-control shadow-sm'
                    ])->label('<strong>🏡 Address</strong>') ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'message')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Enter a message or any additional information',
                        'class' => 'form-control shadow-sm'
                    ])->label('<strong>💌 Message</strong>') ?>
                </div>
            </div>

            <?= $form->field($model, 'status', ['template' => '{input}'])->hiddenInput(['value' => 1])->label(false); ?>

            <!-- Submit Button -->
            <div class="form-group text-center mt-4">
                <?= Html::submitButton(
                    $model->isNewRecord ? '🚀 Submit Enquiry' : 'Update Enquiry',
                    ['class' => 'btn btn-lg btn-gradient px-5 shadow animate__animated animate__pulse animate__infinite']
                ) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php if ($successMessage): ?>
    <div class="alert alert-success mt-4">
        <?= Html::encode($successMessage) ?>
    </div>
    <?php endif; ?>
        </div>



    </div>
</div>

<style>
    .btn-gradient {
        background: linear-gradient(to right, #ff7e5f, #feb47b);
        color: #fff;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(to right, #feb47b, #ff7e5f);
        transform: scale(1.05);
    }

    .form-control:focus {
        box-shadow: 0 0 10px rgba(255, 126, 95, 0.7);
        border-color: #ff7e5f;
    }
</style>