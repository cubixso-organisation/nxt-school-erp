<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\User;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\PaymentDetails;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PayFees */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pay Fees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$campus_details = (new User())->getCampusesByUser(Yii::$app->user->identity->id, 'details');

?>
<style>
  .receipt-container {
    margin: 20px 0;
    padding: 10px;
    border: 1px solid #ddd;
    background-color: #f9f9f9;
  }

  .institution-title {
    font-weight: bold;
    font-size: 34px;
    text-align: center;
    /* Center school name */
  }

  .table-header {
    background-color: #f0f0f0;
    font-weight: bold;
  }

  .divider {
    height: 1px;
    border-bottom: 1px solid black;
    margin: 10px 0;
  }

  /* Center content in both views */
  .text-center {
    text-align: center !important;
  }

  .d-flex-center {
    display: flex;
    justify-content: center;
  }

  /* Center table in both views */
  .centered-table {
    margin-left: auto;
    margin-right: auto;
    width: auto;
  }
</style>
<?php
if ($campus_details->id == 62 || $campus_details->id == 70):
?>
  <div class="pay-fees-view">
    <button id="print_btn" class="btn btn-success">Print</button>

    <div class="card" id="printed_window">
      <div class="card-body">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h3 class="d-flex justify-content-center">
                <img src="<?= $campus_details->school_logo ?>" style="width:75px">
              </h3>
            </div>
            <div class="col-md-12 text-center"> <!-- Centered school name -->
              <h3>
                <?= $campus_details->name_of_the_educational_Institution ?>
              </h3>
            </div>
            <div class="col-md-12 text-center"> <!-- Centered address -->
              <span>
                <?= $campus_details->address ?>-<?= $campus_details->pincode ?>,<?= $campus_details->district->name ?>,<?= $campus_details->state->state_name ?>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <small>
                <h6>PAYMENT RECEIPT – <?= !empty($model->paid_reference_number) ? $model->paid_reference_number : '' ?></h6>
              </small>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
          </div>
          <div class="row">
            <small>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                    <b>Name : <?= $model->student->student_name ?></b>
                  </div>
                  <div class="col-md-12">
                    <?php
                    if ($model->student->gender == 'Female') {
                      $so_d_of = 'D/o';
                    } else {
                      $so_d_of = 'S/o';
                    }
                    ?>
                    <b><?= $so_d_of ?>: <?= !empty($model->student->parent->name_of_the_father) ? $model->student->parent->name_of_the_father : '' ?></b>
                  </div>
                  <div class="col-md-12">
                    <b>Class/Section :<?= !empty($model->student->studentClass->title) ? $model->student->studentClass->title : '' ?> /<?= !empty($model->student->section->section_name) ? $model->student->section->section_name : '' ?></b>
                  </div>
                  <div class="col-md-12">
                    <b>Roll Number : <?= !empty($model->student->rool_number) ? $model->student->rool_number : '' ?></b>
                  </div>
                </div>
              </div>
            </small>
          </div>
          <div class="d-flex-center"> <!-- Centered table container -->
            <small>
              <table class="table table-bordered centered-table" style="font-size:13px"> <!-- Centered table -->
                <tbody>
                  <tr>
                    <td>Paid date</td>
                    <td>Payment type</td>
                    <td>Notes</td>
                    <td>Amount</td>
                    <td>Payment Status</td>
                  </tr>
                  <tr>
                    <td><?= !empty($model->created_on) ? $model->created_on : '' ?></td>
                    <td><?= !empty($model->getPaymentModeOptionsBadges()) ? $model->getPaymentModeOptionsBadges() : '' ?></td>
                    <td><?= !empty($model->remarks) ? $model->remarks : '' ?></td>
                    <td>₹ <?= !empty($model->paid_amount) ? $model->paid_amount : '' ?></td>
                    <td><?= !empty($model->getStateOptionsBadges()) ? $model->getStateOptionsBadges() : '' ?></td>
                  </tr>
                </tbody>
              </table>
            </small>
          </div>
          <div class="row">
            <div class="col-md-4" style="float:left;width:30%">
              <div class="form-group">
                <b><small>Due left : ₹ <?= (new StudentDetails())->getStudentOfBalanceAmount($model->student->id); ?></small></b>
              </div>
            </div>
            <div class="col-md-4" style="float:left;width:50%">
              <small>
                <table style="font-size:small;padding:2px;border:1px solid #f2f2f2">
                  <thead>
                    <tr>
                      <th style="padding:2px;border:1px solid #ddd">Type</th>
                      <th style="padding:2px;border:1px solid #ddd">Total</th>
                      <th style="padding:2px;border:1px solid #ddd">Paid</th>
                      <th style="padding:2px;border:1px solid #ddd">Due</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (!empty($pay_fees)) {
                      foreach ($pay_fees as $pay_fees_data) { ?>
                        <tr>
                          <td style="border:1px solid #ddd"><?= $pay_fees_data->feeStructures->title ?></td>
                          <td style="border:1px solid #ddd">₹ <?= $pay_fees_data->feeStructures->fee - $pay_fees_data->fees_cut ?></td>
                          <td style="border:1px solid #ddd">
                            <?php
                            echo (new PaymentDetails())->getPaidAmount($model->student->id, $model->student->studentClass->id, $model->student->section->id, $pay_fees_data->id);
                            ?>
                          </td>
                          <td style="border:1px solid #ddd">₹ <?= (new PaymentDetails())->getDueAmount($model->student->id, $model->student->studentClass->id, $model->student->section->id, $pay_fees_data->id, $pay_fees_data->fee_structures_id); ?></td>
                        </tr>
                    <?php }
                    }
                    ?>
                  </tbody>
                </table>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script>
    $("#print_btn").on("click", function() {
      var printContents = $('#printed_window').html();
      var printWindow = window.open('', '', 'height=600,width=800');

      printWindow.document.write('<html><head><title>Print</title>');
      printWindow.document.write('<style>@media print { @page { size: portrait; margin: 2cm; } body { font-family: Arial, sans-serif; margin: 0; padding: 0; width: 100%; } .card { border: none; box-shadow: none; } .table { width: 100%; margin-bottom: 1rem; color: #212529; } .table-bordered { border: 1px solid #dee2e6; } .table-bordered td, .table-bordered th { border: 1px solid #dee2e6; padding: 0.3rem; } .container { width: 100%; margin: 0 auto; padding: 0 1cm; } .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; } .col-md-12 { flex: 0 0 100%; max-width: 100%; } .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; } .justify-content-center { justify-content: center !important; } .text-center { text-align: center !important; } .d-flex-center { display: flex; justify-content: center; } .centered-table { margin-left: auto; margin-right: auto; width: auto; display: table; } }</style>');
      printWindow.document.write('</head><body>');
      printWindow.document.write(printContents);
      printWindow.document.write('</body></html>');

      printWindow.document.close();
      printWindow.print();
      printWindow.close();
    });
  </script>
<?php else: ?>
  <p class="pay-fees-view">
    <button id="print_btn" class="btn btn-success" style="margin-bottom: 20px;">Print</button>

  <div class="card receipt-container" id="printed_window">
    <div class="card-body">
      <p><b></b> <?= !empty($model->created_on) ? $model->created_on : '' ?></p>

      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-3 d-flex">
            <img src="<?= $campus_details->school_logo ?>" style="width: 200px;">
          </div>
          <div class="col-md-9 text-center"> <!-- Centered school name -->
            <h3 class="institution-title"><?= $campus_details->name_of_the_educational_Institution ?></h3>
            <p class="institution-info"><?= $campus_details->address ?>, <?= $campus_details->district->name ?>, <?= $campus_details->state->state_name ?>, <?= $campus_details->pincode ?></p>
          </div>
        </div>

        <div class="divider"></div>

        <h5 class="text-center" style="color:#000">PAYMENT RECEIPT</h5> <!-- Centered title -->

        <div style="width: 100%; margin-top: 20px;">
          <table style="width: 100%; border-spacing: 10px 15px;">
            <tr>
              <td><b>Transaction ID</b></td>
              <td>: <?= !empty($model->paid_reference_number) ? $model->paid_reference_number : '' ?></td>
              <td><b>Transaction Date</b></td>
              <td>: <?= !empty($model->created_on) ? date('d-m-Y', strtotime($model->created_on)) : '' ?></td>
            </tr>
            <tr>
              <td><b>Name</b></td>
              <td>: <?= $model->student->student_name ?></td>
              <td><b>Class/Section</b></td>
              <td>: <?= !empty($model->student->studentClass->title) ? $model->student->studentClass->title : '' ?> / <?= !empty($model->student->section->section_name) ? $model->student->section->section_name : '' ?></td>
            </tr>
            <tr>
              <td><b><?= $model->student->gender == 'Female' ? 'D/o' : 'S/o' ?></b></td>
              <td>: <?= $model->student->parent->name_of_the_father ?></td>
              <td><b>Roll Number</b></td>
              <td>: <?= !empty($model->student->rool_number) ? $model->student->rool_number : '' ?></td>
            </tr>
          </table>
        </div>

        <div class="divider"></div>

        <div class="row d-flex-center"> <!-- Centered row container -->
          <div class="col-md-10"> <!-- Wider column for better centering -->
            <table class="table table-bordered centered-table" style="font-size: 12px;"> <!-- Centered table -->
              <thead class="table-header">
                <tr>
                  <th>S.No</th>
                  <th>Payment Type</th>
                  <th>Total</th>
                  <th>Paid</th>
                  <th>Due</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $serialNo = 1;
                $totalPaid = 0;
                $totalDue = 0;
                $totalFeeAfterCut = 0;

                if (!empty($pay_fees)) {
                  foreach ($pay_fees as $pay_fees_data) {
                    $paidAmount = (new PaymentDetails())->getPaidAmount($model->student->id, $model->student->studentClass->id, $model->student->section->id, $pay_fees_data->id, $academic_year_id);
                    $dueAmount = (new PaymentDetails())->getDueAmount($model->student->id, $model->student->studentClass->id, $model->student->section->id, $pay_fees_data->id, $pay_fees_data->fee_structures_id, $academic_year_id);
                    $totalPaid += $paidAmount;
                    $totalDue += $dueAmount;
                    $feeAfterCut = $pay_fees_data->feeStructures->fee - $pay_fees_data->fees_cut;
                    $totalFeeAfterCut += $feeAfterCut;
                ?>
                    <tr>
                      <td><?= $serialNo++; ?></td>
                      <td><?= $pay_fees_data->feeStructures->title ?></td>
                      <td>₹ <?= $feeAfterCut ?></td>
                      <td>₹ <?= $paidAmount; ?></td>
                      <td>₹ <?= $dueAmount; ?></td>
                    </tr>
                <?php
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" style="text-align: start;"><b>Total</b></td>
                  <td><b>₹ <?= $totalFeeAfterCut; ?></b></td>
                  <td><b>₹ <?= $totalPaid; ?></b></td>
                  <td><b>₹ <?= $totalDue; ?></b></td>
                </tr>
              </tfoot>
            </table>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
              <h5>Payment Status :<?= !empty($model->getStateOptionsBadges()) ? $model->getStateOptionsBadges() : '' ?></h5>
              <h5>Payment Mode :<?= !empty($model->getPaymentModeOptionsBadges()) ? $model->getPaymentModeOptionsBadges() : '' ?></h5>
            </div>
          </div>
        </div>
        <div style="text-align: right; margin-top: 30px;">
          <p style="margin: 0;">Cashier/Manager</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    $("#print_btn").on("click", function() {
      var printContents = $('#printed_window').html();
      var printWindow = window.open('', '', 'height=600,width=800');

      printWindow.document.write("<html><head><title>Print</title>");
      printWindow.document.write("<style>");
      printWindow.document.write("@media print {");
      printWindow.document.write("@page { size: A5 portrait; margin: 1.5cm; }");
      printWindow.document.write(
        "body { font-family: Arial, sans-serif; margin: 0; padding: 20; background-color: #fff; color: #000; }"
      );
      printWindow.document.write(".text-center { text-align: center !important; }");
      printWindow.document.write(".d-flex-center { display: flex; justify-content: center; }");
      printWindow.document.write(".centered-table { margin-left: auto; margin-right: auto; width: auto; display: table; }");
      printWindow.document.write(
        ".row { display: flex; align-items: center; margin-bottom: 20px; }"
      );
      printWindow.document.write(".col-md-3 { flex: 0 0 25%; max-width: 25%; }");
      printWindow.document.write(".col-md-9 { flex: 0 0 75%; max-width: 75%; }");
      printWindow.document.write(
        "img { max-width: 100%; height: auto; display: block; margin: 0 auto; }"
      );
      printWindow.document.write(
        ".institution-title { font-size: 20px; font-weight: bold; text-align: center; }"
      );
      printWindow.document.write(
        ".institution-info { font-size: 14px; color: #333; text-align: center; }"
      );
      printWindow.document.write('.table { width: auto; border-collapse: collapse; margin: 0 auto; }');
      printWindow.document.write('.table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; }');
      printWindow.document.write('.table th { background-color: #f2f2f2; color: #000; font-weight: bold; }');
      printWindow.document.write('}</style></head>');
      printWindow.document.write('<body>');
      printWindow.document.write(printContents);
      printWindow.document.write('</body></html>');

      printWindow.document.close();
      printWindow.print();
    });
  </script>
<?php endif; ?>