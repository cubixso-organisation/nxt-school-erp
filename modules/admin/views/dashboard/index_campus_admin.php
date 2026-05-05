<?php
/* @var $this \yii\web\View */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\WebSetting;
use yii\helpers\Url;
use app\components\BrevoEmail;

$this->title = 'Dashboard';
$this->params['subheading'] = '';


?>
<style>
    .lesson .date b {

        text-wrap: auto !important;
    }
</style>

<?php if (User::isCampusAdmin()) {
    $id = User::getCampusesByUser(Yii::$app->user->identity->id);

    $campus = Campus::find()->where(['id' => $id])->one();

?>


    <?php
    if (!empty($campus->expiry_date) && $campus->expiry_date != '0000-00-00') {
        $expiryDate = new DateTime($campus->expiry_date);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($expiryDate);

        // Show the notice only if the expiry date is within the next month
        if ($expiryDate > $currentDate && $interval->days <= 30) {
    ?>
            <div class="alert alert-danger text-center mb-4" role="alert" style="border-radius: 8px; font-weight: bold; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <span style="font-size: 16px;">⚠️ Your plan expires on:
                    <?= $expiryDate->format('d F Y') ?>
                </span>
            </div>
    <?php
        }
    }
    ?>
    <div class="list-group mb-3">
        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Your plan expires on : <?= !empty($campus->expiry_date) && $campus->expiry_date != '0000-00-00'
                                                                                                                        ? date('d F Y', strtotime($campus->expiry_date))
                                                                                                                        : "Not Set" ?></a>

    </div>
<?php } ?>
<!-- <?php if (isset($data['formLink'])): ?>
    <p>
        <a href="<?= $data['formLink'] ?>" class="btn btn-primary" target="_blank">
            Generate Admission Form
        </a>
        <button class="btn btn-secondary" onclick="copyLink()">Copy Link</button>
    </p>

    <script>
        function copyLink() {
            // Create a temporary input field to hold the URL
            var tempInput = document.createElement('input');
            tempInput.value = "<?= $data['formLink'] ?>";
            document.body.appendChild(tempInput);
            tempInput.select(); // Select the text in the input field
            document.execCommand('copy'); // Copy the selected text to the clipboard
            document.body.removeChild(tempInput); // Remove the temporary input field

            // Optionally, show a confirmation message (can be a toast or alert)
            alert("Link copied to clipboard!");
        }
    </script>
<?php endif; ?> -->





<!-- <div class="alert alert-info text-center mb-4" role="alert" style="border-radius: 8px; font-weight: bold; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <span style="font-size: 16px;">📢 Hi Sir/Madam,<br>
            Kindly push for the Parent/Teacher mobile app download.<br>
            We request you for the complete product utilisation!
        </span>
    </div> -->
<div class="row">
    <div class="col-lg-12 col-12">
        <?php
        // echo "<pre>\n";
        // print_r($campus);
        // echo "\n</pre>";
        // exit;

        if (!empty($campus->academic_year)) {
            $academic_year = !empty($campus->academicYear->title) ? $campus->academicYear->title : '';
        } else {
            $academic_year = '';
        }

        ?>
        <h4 style="color:#000"><?php
                                if (!empty($academic_year)) {
                                    echo $academic_year;
                                } else {
                                    echo "Please Set Academic year";
                                }


                                ?></h4>
    </div>
</div>
<hr>
<div class="row dashind" style="background:#fff;margin:0px">
    <div class="col-lg-12 col-12" style="margin:12px 0px;">
        <h3>Student Management</h3>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                            <i class="fas fa-user-graduate" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Students</h6>
                            <h3><?= !empty($student_details) ? $student_details : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/student-class']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-chalkboard" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>No Of Classes</h6>
                            <h3><?= !empty($data['no_of_classes']) ? round($data['no_of_classes'], 2) : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/class-sections']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-layer-group" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>No Of Sections</h6>
                            <h3><?= !empty($data['no_of_sections']) ? round($data['no_of_sections'], 2) : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/student-details/parent']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-users" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Total Parents</h6>
                            <h3><?= !empty($data['total_parents']) ? $data['total_parents'] : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/student-class-attendance/index-old']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-user-slash" style="font-size: 24px; color: #f44336;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Absent Students</h6>
                            <h3><?= !empty($data['absent_students']) ? $data['absent_students'] : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/teacher-details/not-marked-teachers']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-chalkboard-teacher" style="font-size: 24px; color: #f44336;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Absent Teachers</h6>
                            <h3><?= !empty($data['absent_teachers']) ? $data['absent_teachers'] : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <div class="card-body">
                <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                        <i class="fas fa-percentage" style="font-size: 24px; color: #000;"></i>
                    </div>
                    <div class="db-info">
                        <h6>Total Discount</h6>
                        <h3><?= !empty($data['total_discount']) ? $data['total_discount'] : 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-comman w-100">
            <a href="<?= Url::toRoute(['/admin/payment-details']) ?>">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle" style="background-color: #f0f4f8; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-money-bill-wave" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Monthly Amount</h6>
                            <h3><?= !empty($data['month_amount']) ? $data['month_amount'] : 0 ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>

<?php
$campusId = (new User())->getCampusId(\Yii::$app->user->identity->id);
if ($campusId != 68) {
?>
    <div class="row dashind" style="background:#fff;border-radius:15px;padding:10px 0px;margin:18px 0px">
        <div class="col-lg-12 col-12">
            <h3>Fee Management</h3>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                            <i class="fas fa-dollar-sign" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Total Fee</h6>
                            <h3><span style="color:#2448cb">₹<?= !empty($data['total_fee']) ? round($data['total_fee'], 2) : 0 ?></span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                            <i class="fas fa-wallet" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Total Fee Collection</h6>
                            <h3><span style="color:#2448cb">₹<?= !empty($data['total_fee_collection']) ? round($data['total_fee_collection'], 2) : 0 ?></span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                            <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Pending Fee</h6>
                            <h3><span style="color:#2448cb">₹<?= !empty($data['pending_fee']) ? round($data['pending_fee'], 2) : 0 ?></span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                            <i class="fas fa-calendar-day" style="font-size: 24px; color: #000;"></i>
                        </div>
                        <div class="db-info">
                            <h6>Today Fee</h6>
                            <h3><span style="color:#2448cb">₹<?= !empty($data['payment_details_today']) ? round($data['payment_details_today'], 2) : 0 ?></span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- <h3>Campus Fee Summary by Academic Year</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Academic Year</th>
            <th>Total Fee</th>
            <th>Collected Fee</th>
            <th>Pending Fee</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($academicFeeData as $year => $fee): ?>
            <tr>
                <td><?= $year ?></td>
                <td>₹<?= number_format($fee['total_fee'], 2) ?></td>
                <td>₹<?= number_format($fee['collected_fee'], 2) ?></td>
                <td>₹<?= number_format($fee['pending_fee'], 2) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table> -->

<h3>Campus Fee Summary by Academic Year</h3>

<div class="row">
    <?php foreach ($academicFeeData as $year => $fee): ?>
        <div class="col-12 mb-4">
            <h5 style="color: #000;"><?= $year ?></h5>
            <div class="row">
                <!-- Total Fee -->
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-icon avatar-img rounded-circle">
                                    <i class="fas fa-wallet" style="font-size: 24px; color: #000;"></i>
                                </div>
                                <div class="db-info">
                                    <h6>Total Fee</h6>
                                    <h4><span style="color:#2448cb">₹<?= number_format($fee['total_fee'], 2) ?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collected Fee -->
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-icon avatar-img rounded-circle">
                                    <i class="fas fa-rupee-sign" style="font-size: 24px; color: green;"></i>
                                </div>
                                <div class="db-info">
                                    <h6>Collected Fee</h6>
                                    <h4><span style="color:green">₹<?= number_format($fee['collected_fee'], 2) ?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Fee -->
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-icon avatar-img rounded-circle">
                                    <i class="fas fa-hourglass-half" style="font-size: 24px; color: orange;"></i>
                                </div>
                                <div class="db-info">
                                    <h6>Pending Fee</h6>
                                    <h4><span style="color:orange">₹<?= number_format($fee['pending_fee'], 2) ?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-md-12 col-lg-6">
        <div class="card flex-fill comman-shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="card-title">Leave Request</h5>
                    </div>
                    <?php if (!empty($leaveRequest)) : ?>
                        <span class="float-end view-link"><a href="<?= Yii::$app->urlManager->createUrl(['admin/leave-management/staff-leave-applied']) ?>">View All Request</a></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="pt-3 pb-3">
                <div class="table-responsive lesson">
                    <?php if (empty($leaveRequest)) : ?>

                        <h5 class="card-title text-center mt-150" style="padding-top: 68%;">No leave Applied Today</h5>
                    <?php else : ?>
                        <table class="table table-center">
                            <tbody>
                                <?php foreach ($leaveRequest as $request) : ?>
                                    <tr>
                                        <td>
                                            <div class="date">
                                                <b><?= $request['leave_reason'] ?></b>
                                                <p><?= isset($request->user->first_name) ? $request->user->first_name : '' ?></p>
                                                <ul class="teacher-date-list">
                                                    <li><i class="fas fa-calendar-alt me-2"></i><?= date('Y-m-d', strtotime($request['from_date'])) ?></li>
                                                    <li>|</li>
                                                    <li><i class="fas fa-calendar me-2"></i><?= date('Y-m-d', strtotime($request['to_date'])) ?></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="lesson-confirm">
                                                <a href="#"><?= $request->leaveType->title ?></a>
                                            </div>
                                            <a href="<?= Url::toRoute(['/admin/leave-management/staff-leave-applied/leave-approve', 'id' => $request->id]) ?>" class="btn btn-info">Approve</a>
                                            <a href="<?= Url::toRoute(['/admin/leave-management/staff-leave-applied/leave-rejected', 'id' => $request->id]) ?>" class="btn btn-danger">Reject</a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 d-flex">

        <div class="card flex-fill student-space comman-shadow">
            <div class="card-header d-flex align-items-center" style="justify-content: space-between;">
                <h5 class="card-title">Available teacher for replacement</h5>
                <span class="float-end view-link"><a href="<?= Yii::$app->urlManager->createUrl(['admin/temporary-assign-teacher']) ?>">View List</a></span>
            </div>
            <div class="card-body">
                <?php if (empty($teachers)) : ?>
                    <h3 style="text-align: center;font-size:20px;">No teachers found to replace time table .</h3>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table star-student table-hover table-center table-borderless table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Class</th>
                                    <th class="text-center">Period</th>
                                    <th class="text-center">Section</th>
                                    <th class="text-center">Subject</th>
                                    <th class="text-end">Assign Substitute</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $teacher) : ?>
                                    <tr>
                                        <td class="text-nowrap"><a href="profile.html"><?= $teacher->teacherDetail->name ?></a></td>
                                        <td class="text-center"><?= $teacher->day_id ?></td>
                                        <td class="text-center"><?= $teacher->class->title ?></td>
                                        <td class="text-center"><?= $teacher->period ?></td>
                                        <td class="text-center"><?= $teacher->section->section_name ?></td>
                                        <td class="text-center"><?= $teacher->subject->subject_name ?></td>
                                        <td class="text-end">
                                            <div>
                                                <button type="button" class="btn btn-success waves-effect waves-light mt-1 replace-button" data-bs-toggle="modal" data-bs-target="#con-close-modal" data-teacher-id="<?= $teacher->teacher_detail_id ?>" data-id="<?= $teacher->id ?>">Replace</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
</div>


<div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Replace Substitute Teacher</h4>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="teacher-select" class="form-label">Available Teachers</label>
                            <select class="form-control" id="teacher-select">
                                <!-- Options will be populated dynamically via AJAX -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitTeacher" class="btn btn-info waves-effect waves-light" id="assign-teacher-button">Assign Teachers</button>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Fee Paid and Pending</h5>
            </div>
            <div class="card-body">
                <div id="s-col"></div>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Statistics</h5>
            </div>
            <div class="card-body">
                <div id="pichart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-6">
        <div class="card card-chart">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="card-title">Overview</h5>
                    </div>
                    <div class="col-6">
                        <ul class="chart-list-out">
                            <li><span class="circle-blue"></span>Teacher</li>
                            <li><span class="circle-green"></span>Student</li>
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="apexcharts-area"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Student's and Teacher's Attendance</h5>
            </div>
            <div class="card-body">
                <div id="attendance-col"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">


</div>
<div class="content container-fluid">
    <div class="row">



    </div>
</div>
</div>
<!-- Below Card Is Model for Replacing teacher form from Dashboard Starts Here  -->


<!-- Below Card Is Model for Replacing teacher form from Dashboard Ends Here  -->
<div class="row">
    <div class="col-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill comman-shadow">
            <div class="card-header d-flex align-items-center justify-content-between  text-white">
                <h5 class="card-title mb-0">Notice Board</h5>
                <a href="<?= Yii::$app->urlManager->createUrl(['admin/notice-boards']) ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-history me-1"></i> Notice History
                </a>
            </div>
            <div class="card-body">
                <div class="teaching-card">
                    <?php if (empty($noticeBoard)) : ?>

                        <h5 class="card-title text-center">No Notice Posted</h5>

                    <?php else : ?>
                        <ul class="steps-history">
                            <!-- If you have dates to display, you can iterate through them -->
                            <?php foreach ($noticeBoard as $notice) : ?>
                                <li><?= date('M d', strtotime($notice->created_on)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <ul class="activity-feed">
                            <!-- Iterate through each notice to display -->
                            <?php foreach ($noticeBoard as $notice) : ?>
                                <li class="feed-item d-flex align-items-center">
                                    <div class="dolor-activity">
                                        <i class="fas fa-bullhorn me-2 text-warning"></i> <?= $notice->title ?>
                                        <b><?= $notice['description'] ?></b>
                                        <ul class="teacher-date-list">
                                            <li><i class="fas fa-clock me-2"></i>Ending On</li>
                                            <li>|</li>
                                            <li><i class="fas fa-calendar-alt me-2"></i><?= date('F d, Y', strtotime($notice->expiry_date)) ?></li>
                                        </ul>
                                    </div>
                                    <div class="activity-btns ms-auto">
                                        <!-- Use notice status to determine the button status -->
                                        <button class="btn btn-<?= (strtotime($notice->expiry_date) < time()) ? 'success' : 'warning' ?> btn-sm">
                                            <?= (strtotime($notice->expiry_date) < time()) ? 'Completed' : 'In Progress' ?>
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>




<?php  ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('.replace-button').click(function() {
            var teacherId = $(this).data('teacher-id');
            var id = $(this).data('id');

            $('#con-close-modal').attr('data-id', id);
            $.ajax({
                type: 'GET',
                url: '<?php echo Url::toRoute(["leave-management/staff-leave-applied/replace"]) ?>',
                data: {
                    id: id,
                    teacherId: teacherId
                },
                success: function(response) {
                    var teachers = JSON.parse(response);

                    // Clear existing options
                    $('#teacher-select').empty();

                    // Iterate over the response data and create options
                    teachers.forEach(function(teacher) {
                        $('#teacher-select').append('<option value="' + teacher.teacher_detail_id + '">' + teacher.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                }
            });
        });

        $('#submitTeacher').click(function() {
            var selectedTeacherId = $('#teacher-select').val();
            var id = $('#con-close-modal').data('id');

            // Show loading indicator using SweetAlert
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make a separate AJAX request to save the selected teacher ID
            $.ajax({
                type: 'GET',
                url: '<?php echo Url::toRoute(["leave-management/staff-leave-applied/replaced-teacher"]) ?>',
                data: {
                    teacherId: selectedTeacherId,
                    id: id,
                },
                success: function(response) {
                    // Close loading indicator
                    Swal.close();

                    // Show success message using SweetAlert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Teacher Replaced successfully.',
                        icon: 'success'
                    }).then(() => {
                        // Reload the page upon success
                        location.reload();
                    });

                    // Handle any further actions upon success if needed
                },
                error: function(xhr, status, error) {
                    // Close loading indicator
                    Swal.close();

                    // Show error message using SweetAlert
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while Replacing teacher.',
                        icon: 'error'
                    }).then(() => {
                        // Reload the page upon success
                        location.reload();
                    });
                }
            });
        });

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('#pichart')) {
            // Make an AJAX request to fetch data for the pie chart
            $.ajax({
                url: 'dashboard/fetch-donut-data', // Endpoint to fetch data
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Example structure from backend: { teachers: 24.9, students: 31.1, librarian: 24.3, warden: 7.3, hostlers: 12.4 }
                    var seriesData = [
                        data.teachers,
                        data.students,
                        data.librarian,
                        data.warden,
                        data.hostlers,
                    ];
                    var labels = [
                        'Teachers',
                        'Students',
                        'Librarian',
                        'Warden',
                        'Hostlers',
                    ];

                    // Configure and render the chart
                    var options = {
                        chart: {
                            height: 350,
                            type: 'pie',
                        },
                        series: seriesData,
                        labels: labels,
                        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0'], // Custom colors
                        legend: {
                            position: 'right',
                            fontSize: '14px',
                            markers: {
                                shape: 'circle',
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val.toFixed(1) + '%';
                            },
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                            },
                        },
                        tooltip: {
                            enabled: true,
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(1) + '';
                                },
                            },
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 300
                                },
                                legend: {
                                    position: 'bottom'
                                },
                            },
                        }, ],
                    };

                    var chart = new ApexCharts(document.querySelector("#pichart"), options);
                    chart.render();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pie chart data:", error);
                },
            });
        }
    });
</script>