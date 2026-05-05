<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;
use app\modules\staffmanagement\models\base\StaffSalary;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\MonthlyPayrolls */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Monthly Payrolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


const COMPONENT_TYPE_EARNING = 1;
const COMPONENT_TYPE_DEDUCTION = 2;

const VALUE_TYPE_FIXED = 1;
const VALUE_TYPE_CTC_PERCENTAGE = 2;
const VALUE_TYPE_BASIC_PERCENTAGE = 3;
const VALUE_TYPE_CTC_VARIABLE = 4;

// Mapping arrays
$componentTypeLabels = [
    COMPONENT_TYPE_EARNING => 'Earning',
    COMPONENT_TYPE_DEDUCTION => 'Deduction'
];

$valueTypeLabels = [
    VALUE_TYPE_FIXED => 'Fixed',
    VALUE_TYPE_CTC_PERCENTAGE => 'CTC Percentage',
    VALUE_TYPE_BASIC_PERCENTAGE => 'Basic Percentage',
    VALUE_TYPE_CTC_VARIABLE => 'CTC Variable'
];
?>
<div class="monthly-payrolls-view">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <?php $staffSalary = StaffSalary::find()->where(['staff_id' => $model->staff_id])->one(); ?>
                    <h4> Total Monthly CTC = ₹ <?= round($staffSalary->ctc / 12, 2) ?> </h4>
                    <h4> Basic Salary = ₹ <?= $model->basic_salary_monthly ?> </h4>
                </div>

                <div class="col-6">
                    <div class="text-center">
                        <a href="<?= Url::toRoute(['print', 'id' => $model->id]) ?>" target="_blank" id="printButton" class="btn btn-success">Print </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="card" id="printableContent">
        <div class="card-body">
            <?php
            if (!empty($model->salary_components)) {

                $salaryComponents = json_decode($model->salary_components, true);

                $fixedAllowance = ($staffSalary->ctc / 12) - ($model->basic_salary_monthly + array_sum(array_column($salaryComponents['components'], 'component_value_monthly')));
            } else {
                $fixedAllowance = ($staffSalary->ctc / 12) - $model->basic_salary_monthly;
            }

            ?>
            <h4>Salary Components</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Component Name</th>
                        <th>Component Type</th>
                        <th>Value Type</th>
                        <th>Monthly Value </th>
                        <th>Monthly Calculated Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($model->salary_components)) {
                        foreach ($salaryComponents['components'] as $component) : ?>
                            <tr>
                                <td><?= $component['component_name'] ?></td>
                                <td><?= $componentTypeLabels[$component['component_type']] ?></td>
                                <td><?= $valueTypeLabels[$component['value_type']] ?></td>
                                <td> <?= number_format($component['component_value_monthly'], 2) ?>/-</td>
                                <td> ₹<?= number_format($component['calculated_value_monthly'], 2) ?>/-</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>

                    <tr>
                        <td colspan="4">Basic Salary</td>
                        <td>₹<?= number_format($model->basic_salary_monthly, 2) ?>/-</td>
                    </tr>
                    <tr>
                        <td colspan="4">Fixed Allowance</td>
                        <td>₹<?= number_format($fixedAllowance, 2) ?>/-</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Deduction</th>
                        <td>₹<?= number_format($salaryComponents['total_deduction'], 2) ?>/-</td>
                    </tr>
                    <tr>
                        <th colspan="4">Total Monthly Earning</th>
                        <td>₹<?= round($model->total_monthly_pay, 2) ?>/-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>





</div>