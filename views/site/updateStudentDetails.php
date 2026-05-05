<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Campus;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

    .error-summary {
        color: red;
    }

    .help-block {
        color: red;
    }
</style>

<div class="container card mt-5 p-5">
    <h3 class="text-center mt-2">Update Student Profile Image</h3>
    <div class="row"></div>
    <div class="student-details-form" id="printableArea">
        <?php $form = ActiveForm::begin(['id' => 'student-profile-form']); ?>
        <?= $form->errorSummary($model, ['class' => 'error-summary']) ?>
        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['id' => 'student-id', 'style' => 'display:none']); ?>
        <div class="row">

            <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['id' => 'campus-id', 'value' => Yii::$app->request->get('campusId'), 'readonly' => true, 'style' => 'display:none']); ?>
            <?= $form->field($model, 'campus')->textInput(['id' => 'campus-name', 'value' => $campus->name_of_the_educational_Institution, 'readonly' => true]); ?>

            <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    ->andWhere(['campus_id' => $campus->id])
                    ->andWhere(['is_agent' => null])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['id' => 'student-class-id', 'placeholder' => Yii::t('app', 'Choose Student class')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

            <?php
            $section_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                ->andWhere(['campus_id' => $campus->id])
                ->andWhere(['student_class_id' => $model->student_class_id])
                ->orderBy('id')->asArray()->all(), 'id', 'section_name');
            ?>

            <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                'data' => $section_data,
                'options' => ['id' => 'class-section-id'],
                'pluginOptions' => [
                    'depends' => ['student-class-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/site/class-section-data'])
                ]
            ]); ?>

            <?= $form->field($model, 'student_name')->widget(DepDrop::classname(), [
                'options' => ['id' => 'student-name'],
                'pluginOptions' => [
                    'depends' => ['student-class-id', 'class-section-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/site/student-data'])
                ]
            ]); ?>

            <?= $form->field($model, 'permanent_address')->textarea(['id' => 'permanent-address', 'rows' => 3]); ?>
            <?= $form->field($model, 'phone_number')->textInput(['id' => 'phone-number', 'maxlength' => true]); ?>


            <div class="row">
                <div class="col-11">
                    <?= $form->field($model, 'profile_photo')->widget(FileInput::classname(), [
                        'options' => ['id' => 'profile-photo', 'multiple' => false, 'accept' => 'image/*'],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'initialPreview' => [$model->profile_photo],
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => true,
                            'showUpload' => false,
                        ]
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <button type="button" id="custom-submit-btn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log("Script loaded and ready");

            $('#custom-submit-btn').on('click', function(e) {
                e.preventDefault(); // Prevent default form submission
                console.log("Submit button clicked");

                // Display Swal loader
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Manually extract each field value
                var id = $('#student-id').val();
                var campus_id = $('#campus-id').val();
                var student_class_id = $('#student-class-id').val();
                var section_id = $('#class-section-id').val();
                var student_name = $('#student-name').val();
                var profile_photo = $('#profile-photo')[0].files[0];
                var permanent_address = $('#permanent-address').val();
                var phone_number = $('#phone-number').val();

                // Log each value to the console
                console.log("ID:", id);
                console.log("Campus ID:", campus_id);
                console.log("Student Class ID:", student_class_id);
                console.log("Section ID:", section_id);
                console.log("Student Name:", student_name);
                console.log("Profile Photo:", profile_photo);

                // Create a new FormData object
                var formData = new FormData();
                formData.append('StudentProfile[id]', id);
                formData.append('StudentProfile[campus_id]', campus_id);
                formData.append('StudentProfile[student_class_id]', student_class_id);
                formData.append('StudentProfile[section_id]', section_id);
                formData.append('StudentProfile[student_name]', student_name);
                formData.append('StudentProfile[permanent_address]', permanent_address);
                formData.append('StudentProfile[phone_number]', phone_number);

                // Append the file only if it exists
                if (profile_photo) {
                    formData.append('StudentProfile[profile_photo]', profile_photo);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Photo Is Required.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }

                // Custom URL for the form submission
                var customURL = '<?= Url::toRoute(['/site/update-student-profile-image']); ?>'; // Replace with your custom URL

                // Send the form data via AJAX
                $.ajax({
                    url: customURL, // Use the custom URL
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your request has been successfully processed.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page after success
                            location.reload();
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an issue processing your request. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>