<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RazorpayLinkedAccount */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="razorpay-linked-account-form">
    <div class="row">
        <div class="col-6">
            <h5>Turn On and Off Online Payments</h5>
        </div>
        <div class="col-6 text-end">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="paymentSwitch"
                    <?= $model->status == 1 ? 'checked' : '' ?>>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'formConfig' => ['showErrors' => true],
        'action' => 'javascript:void(0)',
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <!-- Hidden Fields -->
    <?= $form->field($model, 'id', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->hiddenInput(['value' => (new User())->getCampusId()]); ?>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Contact Name']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'legal_business_name')->textInput(['maxlength' => true, 'placeholder' => 'Legal Business Name']) ?>
        </div>

    </div>



    <!-- Address Information -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'street1')->textInput(['maxlength' => true, 'placeholder' => 'Street 1']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'street2')->textInput(['maxlength' => true, 'placeholder' => 'Street 2']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'state')->dropDownList(
                array_combine(array_keys($jsonData), array_keys($jsonData)), // Ensure the state names are used as both keys and values
                [
                    'prompt' => 'Select State',
                    'id' => 'state-dropdown',
                ]
            ) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'city')->dropDownList(
                [],
                [
                    'prompt' => 'Select City',
                    'id' => 'city-dropdown',
                ]
            ) ?>
        </div>

        <?php
        $script = <<< JS
$('#state-dropdown').on('change', function() {
    var state = $(this).val();  // Now this will be the state name
    console.log('Selected state:', state);  // This should log the state name
    if(state) {
        $.ajax({
            url: 'get-cities',
            data: {state: state},
            success: function(data) {
                var cityDropdown = $('#city-dropdown');
                cityDropdown.empty();
                cityDropdown.append('<option value="">Select City</option>');
                var cities = JSON.parse(data);
                $.each(cities, function(index, city) {
                    cityDropdown.append('<option value="' + city + '">' + city + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });
    } else {
        $('#city-dropdown').empty().append('<option value="">Select City</option>');
    }
});
JS;
        $this->registerJs($script, \yii\web\View::POS_READY);
        ?>


        <div class="col-md-4">
            <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true, 'placeholder' => 'Postal Code']) ?>
        </div>
    </div>


    <!-- Tax Information -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'pan')->textInput(['maxlength' => true, 'placeholder' => 'PAN']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'gst')->textInput(['maxlength' => true, 'placeholder' => 'GST']) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'ifsc_code')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'beneficiary_name')->textInput(['maxlength' => true, 'placeholder' => 'Beneficiary Name']) ?>
        </div>
    </div>

    <!-- Status Dropdown -->
    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <!-- Submit Button -->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => "submit-button-id"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Add SweetAlert -->
<?php $random = (rand(100000, 999999));
$ref = "Estudent-" . $random;

?>
<script>
    $(document).ready(function() {
        var $paymentSwitch = $('#paymentSwitch');
        var $formContainer = $('.razorpay-linked-account-form');

        // Initially set the form visibility based on the status
        if (!$paymentSwitch.is(':checked')) {
            $formContainer.find('form').hide();
        }

        $paymentSwitch.on('change', function() {
            var status = $(this).is(':checked') ? 1 : 0;

            $formContainer.find('#razorpaylinkedaccount-status').val(status);

            if (status === 1) {
                $formContainer.find('form').show();
            } else {
                $formContainer.find('form').hide();
            }

            // Optional: Send an AJAX request to update the status immediately
            $.ajax({
                url: 'add-update-account-details', // Replace with your endpoint
                type: 'POST',
                data: {
                    status: status
                },
                success: function(response) {
                    console.log('Switch status:', status, 'Response:', response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        });

        // Handle click event on the submit button
        $('#submit-button-id').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Manually constructing form data
            var formData = {
                campus_id: $('#razorpaylinkedaccount-campus_id').val(),
                email: $('#razorpaylinkedaccount-email').val(),
                phone: $('#razorpaylinkedaccount-phone').val(),
                contact_name: $('#razorpaylinkedaccount-contact_name').val(),
                legal_business_name: $('#razorpaylinkedaccount-legal_business_name').val(),
                street_one: $('#razorpaylinkedaccount-street1').val(),
                street_two: $('#razorpaylinkedaccount-street2').val(),
                state: $('#state-dropdown').val(),
                city: $('#city-dropdown').val(),
                postal_code: $('#razorpaylinkedaccount-postal_code').val(),
                pan: $('#razorpaylinkedaccount-pan').val(),
                gst: $('#razorpaylinkedaccount-gst').val(),
                account_number: $('#razorpaylinkedaccount-account_number').val(),
                ifsc_code: $('#razorpaylinkedaccount-ifsc_code').val(),
                beneficiary_name: $('#razorpaylinkedaccount-beneficiary_name').val(),
                reference_id: "<?= (string)$ref ?>",
                status: $('#razorpaylinkedaccount-status').val()
            };

            console.log(formData);

            // First API call to generate the token
            generateToken({
                "user_id": 1,
                "user_role": "admin",
                "username": "admin@estudent.tech"
            }).then(function(tokenResponse) {
                // After the token is generated, call the create-account API
                if (tokenResponse.success == true) {
                    createAccount(formData, tokenResponse.token);

                } else {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to generate token! Please try again.' + tokenResponse.message
                    });
                }

            }).catch(function(error) {
                console.error('Token generation failed:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate token! Please try again.'
                });
            });
        });

        function generateToken(data) {
            return new Promise(function(resolve, reject) {
                var settings = {
                    url: "https://api.estudent.tech/api/v1/token/generate",
                    // url: "http://localhost/estudent_backend/api/v1/token/generate",
                    method: "POST",
                    timeout: 0,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    data: JSON.stringify(data), // Convert the data object to a JSON string
                };

                $.ajax(settings).done(function(response) {
                    if (response.success == false) {
                        reject(response.message || 'Failed to generate token.');
                    } else {
                        resolve(response);
                    }
                }).fail(function(xhr, status, error) {
                    reject(error);
                });
            });
        }

        function createAccount(formData, token) {
            var settings = {
                // url: "http://localhost/estudent_backend/api/v1/account/create-account",
                url: "https://api.estudent.tech/api/v1/account/create-account",
                method: "POST",
                timeout: 0,
                headers: {
                    "Authorization": "Bearer " + token,
                    "Content-Type": "application/json"
                },
                data: JSON.stringify(formData), // Use the formData object created above
            };

            $.ajax(settings).done(function(response) {
                Swal.close(); // Close the loader on successful API call
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Your account was created successfully!'
                });
                console.log(response);
            }).fail(function(xhr, status, error, response) {
                Swal.close(); // Close the loader on error
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to create account!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage // Show the error message from the API response
                });
                console.error('AJAX error:', error);
            });
        }
    });
</script>