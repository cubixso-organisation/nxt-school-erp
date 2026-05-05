<?php

use yii\helpers\Url;

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $get_fee_structs array */
/* Assuming $get_fee_structs is an array of fee structures */

$this->title = Yii::t('app', 'Fee Structure');
$this->params['breadcrumbs'][] = $this->title;

$totalFee = 0; // Initialize total fee variable
$totalRows = count($get_fee_structs); // Get total number of fee structures
// $url = Url::toRoute(['fee-structures/update-fee']);
?>

<div class="fee-structure-index">

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered fee-structure-table">
                <thead class="thead-light">
                    <tr>
                        <th>Fee Type</th>
                        <th>Fee</th>
                        <th>Maximum Deduction</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_fee_structs as $fee_structure) : ?>
                        <tr>
                            <td class="hidden"> <input type="text" class="form-control fee_id" value="<?= $fee_structure->id ?>" readonly></td>
                            <td><?= Html::encode($fee_structure->feeType->fees_type_name) ?></td>
                            <td>
                                <input type="text" class="form-control fee-input" value="<?= $fee_structure->fee ?>" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control max-deduction-input" value="<?= $fee_structure->maximum_detuction ?>" readonly>
                            </td>
                            <td>
                                <button class="btn btn-outline-secondary edit-btn" type="button">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-outline-secondary save-btn" type="button" style="display: none;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </td>
                        </tr>
                        <?php $totalFee += $fee_structure->fee; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td colspan="2"><?= $totalFee ?></td> <!-- Display total fee -->
                        <td></td> <!-- Empty column for alignment -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('.edit-btn').click(function() {
            var row = $(this).closest('tr');
            row.find('.form-control').prop('readonly', false);
            row.find('.edit-btn').hide();
            row.find('.save-btn').show();
        });

        $('.save-btn').click(function() {
            var row = $(this).closest('tr');
            var fee = parseFloat(row.find('.fee-input').val()); // Parse fee as a float
            var maxDeduction = parseFloat(row.find('.max-deduction-input').val()); // Parse max deduction as a float
            var id = row.find('.fee_id').val();

            $.ajax({
                type: 'get',
                url: '<?= Url::to(['fee-structures/update-fee']) ?>',
                data: {
                    id: id,
                    fee: fee,
                    maxDeduction: maxDeduction
                },
                success: function(response) {
                    var totalFee = 0; // Initialize total fee variable
                    $('.fee-input').each(function() {
                        totalFee += parseFloat($(this).val()); // Add each fee value to totalFee
                    });
                    $('.total-row td:nth-child(2)').text(totalFee.toFixed(2)); // Update total fee in the total row
                    row.find('.form-control').prop('readonly', true);
                    row.find('.edit-btn').show();
                    row.find('.save-btn').hide();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<style>
    .hidden {
        display: none;
    }

    .total-row {
        background-color: #f0f0f0;
        /* Example background color */
        font-weight: bold;
        /* Make text bold */
        /* Add any other styling you prefer to make the total row stand out */
    }
</style>