<?php

use yii\helpers\Html;
use yii\helpers\Url;

// Assuming $model->salary_components contains the provided JSON data
$salaryComponents = json_decode($model->salary_components, true);

// Function to round off values to a maximum of two decimal digits
function roundValue($value)
{
    return number_format($value, 2);
}

// Calculate the sum of all earning components
$earningComponentsTotal = 0;
foreach ($salaryComponents['components'] as $component) {
    if ($component['component_type'] == 1) {
        $earningComponentsTotal += $component['calculated_value_monthly'];
    }
}

// Calculate the Fixed Allowance
$fixedAllowance = roundValue($model->staffSalary->ctc / 12 - ($model->basic_salary_monthly + $earningComponentsTotal));
?>

<style>
</style>

<div class="payroll">
    <div class="header">
        <img class="logo" src="<?= isset($model->campus->school_logo) ? $model->campus->school_logo : "https://estudent.anxion.co.in/web/logo.png" ?>" alt="School Logo">
        <h2><?= $model->campus->name_of_the_educational_Institution ?? "" ?></h2>
    </div>

    <table class="employee-details">
        <tr>
            <td style="padding-right:5px"><strong>Name:</strong> <?= Html::encode($model->staff->name) ?></td>
            <td style="padding-right:5px"><strong>Employee ID:</strong> <?= Html::encode($model->staff->id) ?></td>
            <td style="padding-right:5px"><strong>Annual CTC:</strong> <?= Html::encode(roundValue($model->staffSalary->ctc)) ?></td>
            <td style="padding-right:5px"><strong>Monthly CTC:</strong> <?= Html::encode(roundValue($model->staffSalary->ctc / 12)) ?></td>
        </tr>
    </table>

    <div class="earnings-and-deductions" style="display: flex;">

        <div class="column" style="padding-right: 20px;">
            <h3>Earnings</h3>
            <ul>
                <li>Basic Salary : <?= roundValue($model->basic_salary_monthly) ?></li>

                <?php foreach ($salaryComponents['components'] as $component) : ?>
                    <?php if ($component['component_type'] == 1) : ?>
                        <li><?= Html::encode($component['component_name']) ?>: <?= roundValue($component['calculated_value_monthly']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li>Fixed Allowance: <?= $fixedAllowance ?></li>
            </ul>
        </div>
        <div class="column" style="padding-left: 20px;">
            <h3>Deductions</h3>
            <ul>
                <?php foreach ($salaryComponents['components'] as $component) : ?>
                    <?php if ($component['component_type'] == 2) : ?>
                        <li><?= Html::encode($component['component_name']) ?>: <?= roundValue($component['calculated_value_monthly']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="total-earnings">
        <h3>Total Earnings for the Month: <?= roundValue($salaryComponents['total_monthly_earning']) ?></h3>
    </div>
</div>