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
<?php if (User::isDynamicUser()) { ?>
    <div class="row">
        <div class="col-lg-12 col-12">
            <?php
            $id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $campus = Campus::find()->where(['id' => $id])->one();
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
            <h4 style="color:#005413"><?php
                                        if (!empty($academic_year)) {
                                            echo $academic_year;
                                        } else {
                                            echo "Please Set Academic year";
                                        }


                                        ?></h4>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-12">
            <h3>Student Management</h3>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon avatar-img rounded-circle">
                                <!-- <i class="fas">&#xf19d;</i> -->
                                <img class="rounded-circle" alt="Total Image" src="../themes/school-management/assets/img/dashimage/students.png">
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
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-classes.png">
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
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-section.png">
                                <!-- <i class="fas">&#xf0db;</i> -->
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
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">
                                <!-- <i class="fas">&#xf0c0;</i> -->
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
                <a href="<?= Url::toRoute(['/admin/student-class-attendance']) ?>">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">
                                <!-- <i class="fas">&#xf0c0;</i> -->
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
                <a href="<?= Url::toRoute(['/admin/teacher-attenddence']) ?>">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">
                                <!-- <i class="fas">&#xf0c0;</i> -->
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
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">
                                <!-- <i class="fas">&#xf0c0;</i> -->
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
                            <div class="db-icon avatar-img rounded-circle">
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">
                                <!-- <i class="fas">&#xf0c0;</i> -->
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
    <div class="row">
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Fee Paid and Pending</h5>
                </div>
                <div class="card-body">
                    <div id="s-col"></div>
                </div>
            </div>

        </div>
        <div class="col-md-12 col-lg-4">
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
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Statistics</h5>
                </div>
                <div class="card-body">
                    <div id="donut-chart"></div>
                </div>
            </div>
        </div>
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
                                                    <p><?= $request->user->first_name ?></p>
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
    </div>
    <div class="content container-fluid">
        <div class="row">

            <div class="col-xl-8 d-flex">

                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center" style="justify-content: space-between;">
                        <h5 class="card-title">Available teacher for replacement</h5>
                        <span class="float-end view-link"><a href="<?= Yii::$app->urlManager->createUrl(['admin/temporary-assign-teacher']) ?>">View List</a></span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($teachers)) : ?>
                            <h3 style="text-align: center;">No teachers found to replace time table .</h3>
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
            <div class="col-md-4">
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
    </div>
    </div>
    <!-- Below Card Is Model for Replacing teacher form from Dashboard Starts Here  -->
    <div class="card">
        <div class="card-body">
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
        </div>
    </div>

    <!-- Below Card Is Model for Replacing teacher form from Dashboard Ends Here  -->
    <div class="row">
        <div class="col-12 col-lg-12 col-xl-12 d-flex">
            <div class="card flex-fill comman-shadow">
                <div class="card-header d-flex align-items-center" style="justify-content: space-between;">
                    <h5 class="card-title">Notice Board</h5>
                    <span class="float-end view-link"><a href="<?= Yii::$app->urlManager->createUrl(['admin/notice-boards']) ?>">Notice period history</a></span>
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
                                            <span class="feed-text1"><a><?= $notice->title ?></a></span>
                                            <b><?= $notice['description'] ?></b>
                                            <ul class="teacher-date-list">
                                                <li><i class="fas fa-clock me-2"></i>Ending On</li>
                                                <li>|</li>
                                                <li><i class="fas fa-calendar-alt me-2"></i><?= date('F d, Y', strtotime($notice->expiry_date)) ?></li>
                                            </ul>
                                        </div>
                                        <div class="activity-btns ms-auto">
                                            <!-- Use notice status to determine the button status -->
                                            <button type="submit" class="btn btn-info">
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




<?php } ?>

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