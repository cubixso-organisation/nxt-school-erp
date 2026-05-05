<?php

use app\modules\staffmanagement\models\base\SalaryComponents;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\StaffSalary */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="content container-fluid">

    <div class="page-header invoices-page-header">
        <div class="row align-items-center">
            <div class="col">
                <ul class=" float-left">
                    <li class="breadcrumb-item invoices-breadcrumb-item">
                        <a href="#">
                            <?= $model->staff->id ?? "" ?>
                            Name : <?= $model->staff->name ?? "" ?>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card invoices-add-card">
                <div class="card-body">
                    <form action="javascript:void(0)" class="invoices-form">
                        <div class="invoices-main-form">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Annual CTC (₹) </label>
                                        <input id="annual-ctc" class="form-control" type="text" name="ctc" value="<?= isset($model->ctc) ? $model->ctc : 0 ?>" placeholder="CTC (₹)">

                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="invoice-add-table">
                            <h4>Earnings</h4>
                            <div class="table-responsive">
                                <table class="table table-center add-table-items">
                                    <thead>
                                        <tr>
                                            <th>Salary Components</th>
                                            <th>Calculation Type</th>
                                            <th>Monthly Amount</th>
                                            <th>Annual Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-form-control add-row">
                                            <td>
                                                Basic Salary
                                            </td>
                                            <td>
                                                <input type="text" id="basic_salary_value" value="<?php
                                                                                                    if (!empty($model->basic_salary_type_value) || $model->basic_salary_type_value != 0) {
                                                                                                        echo $model->basic_salary_type_value;
                                                                                                    } else {
                                                                                                        echo 40;
                                                                                                    }
                                                                                                    ?>" name="basic_salary_type" class="form-control" placeholder="Basic Salary Cal. Type"> % of CTC
                                            </td>
                                            <td>
                                                <input type="text" name="basic_salary_monthly" id="basic-salary-monthly" value="<?= isset($model->monthly_basic_salary) ? $model->monthly_basic_salary : 0 ?>" class="form-control" readonly placeholder="Basic Salary Monthly">
                                            </td>
                                            <td>

                                                <input type="text" name="basic_salary_yearly" id="basic-salary-yearly" value="<?= isset($model->annual_basic_salary) ? $model->annual_basic_salary : 0 ?>" class="form-control" readonly placeholder="Basic Salary Yearly">

                                            </td>


                                        </tr>

                                        <?php if (!empty($salaryGroupComponents)) {
                                            foreach ($salaryGroupComponents as $components) {
                                                $i = 1;
                                        ?>


                                                <tr class="table-form-control add-row">
                                                    <td>
                                                        <?= $components->component->name ?>
                                                    </td>
                                                    <td>
                                                        <?= $components->component->getComponentTypeOptionsBadges() ?>

                                                        <?= $components->component->getValueTypeOptionsBadges() ?>

                                                        <p>Component Value: <?= $components->component->component_value_monthly ?></p>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="earning_components_<?= $i ?>" id="earning_components_<?= $i ?>" class="form-control" data-type="<?= $components->component->component_type ?>" data-valuetype="<?= $components->component->value_type ?>" data-value="<?= $components->component->component_value_monthly ?>" placeholder="Monthly Value" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" placeholder="Yearly Value" readonly>
                                                    </td>


                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>

                                        <tr class="table-form-control add-row">
                                            <td>
                                                Fixed Allowance
                                            </td>
                                            <td>
                                                Annual CTC - Sum of all other components
                                            </td>
                                            <td>
                                                <p id="fixed-allowance-monthly"></p>
                                            </td>
                                            <td>
                                                <p id="fixed-allowance-yearly"></p>


                                            </td>


                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <h4 class="bg-light p-4 text-center">Cost To Company:- ₹<span id="costToCompany">0.00</span></h4>
                        <div class="invoice-add-table">
                            <h4>Deductions</h4>
                            <div class="table-responsive">
                                <table class="table table-center add-table-items">
                                    <thead>
                                        <!-- <tr>
                                            <th>Salary Components</th>
                                            <th>Calculation Type</th>
                                            <th>Monthly Amount</th>
                                            <th>Annual Amount</th>
                                        </tr> -->
                                    </thead>
                                    <tbody>


                                        <?php if (!empty($salaryGroupComponentsDeduction)) {
                                            foreach ($salaryGroupComponentsDeduction as $deduction) {
                                                $i = 1;
                                        ?>


                                                <tr class="table-form-control deduction-row" id="deductions">
                                                    <td>
                                                        <?= $deduction->component->name ?>
                                                    </td>
                                                    <td>
                                                        <?= $deduction->component->getComponentTypeOptionsBadges() ?>

                                                        <?= $deduction->component->getValueTypeOptionsBadges() ?>

                                                        <p>Component Value: <?= $deduction->component->component_value_monthly ?></p>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="deduction_components_<?= $i ?>" id="deduction_components_<?= $i ?>" class="form-control" data-type="<?= $deduction->component->component_type ?>" data-valuetype="<?= $deduction->component->value_type ?>" data-value="<?= $deduction->component->component_value_monthly ?>" placeholder="Deduction Monthly Value" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" placeholder="Deduction Yearly Value" readonly>
                                                    </td>


                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>


                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="submit-button"> Submit
                                <div id="loadingSpinner" class="spinner-border d-none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="staff-salary-form card">
    <h3>Staff Name : <?= $model->staff->name ?? "" ?></h3>
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


    <?= $form->field($model, 'staff_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDetails::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Staff details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ctc')->textInput(['placeholder' => 'Ctc']) ?>

    <?= $form->field($model, 'basic_salary_type')->textInput(['placeholder' => 'Basic Salary Type']) ?>

    <?= $form->field($model, 'basic_salary_value')->textInput(['placeholder' => 'Basic Salary Value']) ?>

    <?= $form->field($model, 'earnings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ctc_monthly')->textInput(['placeholder' => 'Ctc Monthly']) ?>

    <?= $form->field($model, 'ctc_yearly')->textInput(['placeholder' => 'Ctc Yearly']) ?>

    <?= $form->field($model, 'total_deduction_monthly')->textInput(['placeholder' => 'Total Deduction Monthly']) ?>

    <?= $form->field($model, 'total_deduction_yearly')->textInput(['placeholder' => 'Total Deduction Yearly']) ?>

    <?= $form->field($model, 'salary_group_id')->textInput(['placeholder' => 'Salary Group']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?= $form->field($model, 'create_user_id')->textInput(['placeholder' => 'Create Uder']) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div> -->




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(async function() {
        const COMPONENT_TYPE_EARNING = 1;
        const COMPONENT_TYPE_DEDUCTION = 2;

        const VALUE_TYPE_FIXED = 1;
        const VALUE_TYPE_CTC_PERCENTAGE = 2;
        const VALUE_TYPE_BASIC_PERCENTAGE = 3;
        const VALUE_TYPE_BASIC_VARIABLE = 4;
        const calculateValues = async function() {
            try {

                var annualCtc = parseFloat($('#annual-ctc').val());
                var salaryType = parseFloat($('#basic_salary_value').val());

                if (isNaN(annualCtc) || isNaN(salaryType)) {
                    throw new Error('Invalid input');
                }

                var monthlyCtc = annualCtc / 12;
                var basicSalaryYearly = (annualCtc * salaryType) / 100;
                var basicSalaryMonthly = ((annualCtc * salaryType) / 100) / 12;

                basicSalaryYearly = roundToTwoDecimals(basicSalaryYearly);
                basicSalaryMonthly = roundToTwoDecimals(basicSalaryMonthly);

                $('#basic-salary-monthly').val(basicSalaryMonthly);
                $('#basic-salary-yearly').val(basicSalaryYearly);

                var componentSumMonthly = 0;
                var componentSumYearly = 0;
                var fixedAllwanceMonthly = 0;
                var fixedAllwanceYearly = 0;
                $('.add-row').each(async function(index) {
                    var componentType = $(this).find('input[name^="earning_components_"]').data('type');
                    var componentValueType = $(this).find('input[name^="earning_components_"]').data('valuetype');
                    var componentMonthlyValue = $(this).find('input[name^="earning_components_"]').data('value');

                    var originalBasicSalaryMonthly = basicSalaryMonthly;
                    var originalBasicSalaryYearly = basicSalaryYearly;

                    var montlyValue = 0;
                    var yearlyValue = 0;

                    if (componentType == COMPONENT_TYPE_EARNING) {
                        if (componentValueType == VALUE_TYPE_BASIC_PERCENTAGE) {
                            var basicPerc = (basicSalaryMonthly * componentMonthlyValue) / 100;
                            montlyValue = basicPerc;
                            yearlyValue = basicPerc * 12;

                            $(this).find('input[placeholder="Monthly Value"]').val(roundToTwoDecimals(montlyValue));
                            $(this).find('input[placeholder="Yearly Value"]').val(roundToTwoDecimals(yearlyValue));
                        } else if (componentValueType == VALUE_TYPE_CTC_PERCENTAGE) {
                            var basicPerc = (monthlyCtc * componentMonthlyValue) / 100;
                            montlyValue = basicPerc;
                            yearlyValue = basicPerc * 12;

                            $(this).find('input[placeholder="Monthly Value"]').val(roundToTwoDecimals(montlyValue));
                            $(this).find('input[placeholder="Yearly Value"]').val(roundToTwoDecimals(yearlyValue));
                        }
                    }

                    componentSumMonthly += montlyValue;
                    componentSumYearly += yearlyValue;

                    basicSalaryMonthly = originalBasicSalaryMonthly;
                    basicSalaryYearly = originalBasicSalaryYearly;
                });
                var totalComponentSumMonthly = basicSalaryMonthly + componentSumMonthly;
                var totalComponentSumYearly = componentSumYearly + basicSalaryYearly;
                console.log(totalComponentSumMonthly);
                console.log(monthlyCtc);

                var fixedAllwanceMonthly = monthlyCtc - totalComponentSumMonthly;
                var fixedAllwanceYearly = annualCtc - totalComponentSumYearly;
                console.log("dasdasdasdasdasdsad......", fixedAllwanceMonthly);
                console.log("dasdasdasdasdasdsad.....", fixedAllwanceYearly);
                $("#fixed-allowance-monthly").text(roundToTwoDecimals(fixedAllwanceMonthly).toFixed(2));
                $("#fixed-allowance-yearly").text(roundToTwoDecimals(fixedAllwanceYearly).toFixed(2));
                $("#costToCompany").text(roundToTwoDecimals(monthlyCtc).toFixed(2));


                // Deducation calculations
                $('.deduction-row').each(function(index) {
                    var componentType = $(this).find('input[name^="deduction_components_"]').data('type');
                    var componentValueType = $(this).find('input[name^="deduction_components_"]').data('valuetype');
                    var componentMonthlyValue = parseFloat($(this).find('input[name^="deduction_components_"]').data('value'));

                    var montlyValue = 0;
                    var yearlyValue = 0;

                    if (componentType == COMPONENT_TYPE_DEDUCTION) {
                        if (componentValueType == VALUE_TYPE_BASIC_PERCENTAGE) {
                            montlyValue = (basicSalaryMonthly * componentMonthlyValue) / 100;
                            yearlyValue = montlyValue * 12;
                        } else if (componentValueType == VALUE_TYPE_CTC_PERCENTAGE) {
                            montlyValue = (monthlyCtc * componentMonthlyValue) / 100;
                            yearlyValue = montlyValue * 12;
                        }

                        $(this).find('input[placeholder="Deduction Monthly Value"]').val(roundToTwoDecimals(montlyValue));
                        $(this).find('input[placeholder="Deduction Yearly Value"]').val(roundToTwoDecimals(yearlyValue));
                    }
                });


            } catch (error) {
                console.error(error.message);
                // Handle error here, e.g., show an error message to the user
            }
        }
        calculateValues();
        $('#annual-ctc, #basic_salary_value').on('input', async function() {
            calculateValues();
        });

        function roundToTwoDecimals(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        }
    });
</script>

<!-- Form Submit -->

<script>
    $(document).ready(async function() {
        // Function to submit the form with specific values
        function submitForm() {
            $('#loadingSpinner').removeClass('d-none');
            var annualCtc = $('#annual-ctc').val();
            var basicSalaryType = $('#basic_salary_value').val();
            var monthlyBasicSalary = $('#basic-salary-monthly').val();
            var annualBasicSalary = $('#basic-salary-yearly').val();

            // Create a form data object with only the required values
            var formData = new FormData();
            formData.append('annual_ctc', annualCtc);
            formData.append('basic_salary_type', basicSalaryType);
            formData.append('monthly_basic_salary', monthlyBasicSalary);
            formData.append('annual_basic_salary', annualBasicSalary);
            formData.append('staff_id', <?= $model->staff_id ?>);

            // AJAX submission of the form data
            $.ajax({
                url: 'update-salary',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle success response
                    var parseResponse = JSON.parse(response);
                    console.log(parseResponse.status);

                    if (parseResponse.status == "OK") {
                        $('#loadingSpinner').addClass('d-none');

                        // Display success message using SweetAlert
                        swal.fire({
                            title: "Success!",
                            text: "Changes have been saved successfully.",
                            icon: "success",
                            button: false, // No close button
                            timer: 3000 // Auto close after 3 seconds
                        }).then(function() {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        });

                    } else {
                        // Hide loading spinner
                        $('#loadingSpinner').addClass('d-none');

                        // Display error message using SweetAlert
                        swal.fire({
                            title: "Error!",
                            text: "An error occurred while saving changes.",
                            icon: "error",
                            button: false, // No close button
                            timer: 3000 // Auto close after 3 seconds
                        }).then(function() {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        });

                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        }

        // Call the submitForm function when needed
        // For example, you can call it on a button click
        $('#submit-button').click(function() {
            submitForm();
        });
    });
</script>