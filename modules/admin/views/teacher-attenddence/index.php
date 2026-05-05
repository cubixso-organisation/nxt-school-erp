<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Teacher Attendance for the Year';
?>

<?php
$this->registerJsFile('https://code.jquery.com/jquery-3.5.1.min.js');
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js');
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js');
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
$this->registerJsFile('https://code.jquery.com/jquery-3.5.1.min.js');
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

?>

<h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        grid-gap: 20px;
    }

    .calendar-container {
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 10px;
        border-radius: 5px;
        background-color: white;
    }

    .fc {
        height: 400px;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        font-size: 24px;
        color: #007bff;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .modal-body {
        font-size: 1rem;
        line-height: 1.6;
    }

    .modal-footer {
        border-top: none;
    }

    .close {
        color: white;
        opacity: 1;
    }

    .close:hover {
        color: #fff;
        opacity: 0.7;
    }

    .form-control {
        margin-bottom: 15px;
        width: 300px;
    }

    .loading {
        display: none;
    }

    .loading.active {
        display: block;
    }
</style>

<div class="d-flex justify-content-center mb-4">
    <?= Html::dropDownList('teacher', null, \yii\helpers\ArrayHelper::map($teachers, 'id', 'name'), [
        'prompt' => 'Select a Teacher',
        'id' => 'teacher-select',
        'class' => 'form-control',
    ]) ?>

    <?= Html::dropDownList('year', null, [
        '2023' => '2023',
        '2024' => '2024',
        '2025' => '2025',
    ], [
        'prompt' => 'Select a Year',
        'id' => 'year-select',
        'class' => 'form-control',
    ]) ?>
</div>

<div class="calendar-grid">
    <?php for ($i = 1; $i <= 12; $i++): ?>
        <div class="calendar-container" id="calendar-<?= $i ?>">
            <div class="loading-spinner loading">Loading...</div>
        </div>
    <?php endfor; ?>
</div>

<!-- Modal for attendance details -->
<!-- Modal for attendance details -->
<!-- Modal for attendance details -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="attendanceModalLabel">
                    <i class="fas fa-calendar-check"></i> Attendance Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content remains the same as earlier -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6><i class="fas fa-user"></i> <strong>Teacher:</strong></h6>
                        <p id="modal-teacher" class="border rounded p-2 bg-light"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong></h6>
                        <p><strong>Latitude:</strong> <span id="modal-lat" class="border rounded p-2 bg-light"></span></p>
                        <p><strong>Longitude:</strong> <span id="modal-lng" class="border rounded p-2 bg-light"></span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6><i class="fas fa-calendar-day"></i> <strong>Present Date & Time:</strong></h6>
                        <p id="modal-present-date-time" class="border rounded p-2 bg-light"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6><i class="fas fa-calendar-check"></i> <strong>Checkout Date & Time:</strong></h6>
                        <p id="modal-checkout-date-time" class="border rounded p-2 bg-light"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
        Close
    </button>
    <a id="update-btn" href="#" class="btn btn-primary" style="display: none;">Update Attendance</a> <!-- Button is hidden by default -->
            </div>
        </div>
    </div>
</div>



<?php

$userRole = Yii::$app->user->identity->user_role;
$canUpdate = ($userRole == \app\models\User::ROLE_ADMIN || $userRole == \app\models\User::ROLE_CAMPUS_ADMIN || $userRole == \app\models\User::role_campus_sub_admin);
$getAttendanceUrl = Url::to(['get-attendance']);
$updateAttendanceUrl = Url::to(['update']);
$script = <<<JS
$(document).ready(function() {
    var teacherId;
    var year;

    function initializeCalendars() {
        $('.calendar-container').each(function(index) {
            var monthIndex = index + 1;
            var calendarEl = $(this).get(0);
            var calendar = $(calendarEl).find('.loading-spinner');

            $(calendarEl).find('.loading-spinner').addClass('active');

            var fullCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: new Date(year, monthIndex - 1),
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '{$getAttendanceUrl}',
                        data: {
                            teacher_id: teacherId,
                            month: monthIndex,
                            year: year
                        },
                        success: function(data) {
                            successCallback(data);
                            $(calendar).removeClass('active');
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventClick: function(info) {
                    $('#modal-teacher').text(info.event.extendedProps.teacher || 'N/A');
                    $('#modal-lat').text(info.event.extendedProps.lat || 'N/A');
                    $('#modal-lng').text(info.event.extendedProps.lng || 'N/A');
                    $('#modal-present-date-time').text(info.event.extendedProps.teacher_present_date_and_time || 'N/A');
                    $('#modal-checkout-date-time').text(info.event.extendedProps.checkout_date_time || 'N/A');

                    var eventId = info.event.id;  // Retrieve the ID
                    var canUpdate = info.event.extendedProps.canUpdate; // Get the canUpdate flag from backend

                    if (eventId && canUpdate) {
                        $('#update-btn').attr('href', '{$updateAttendanceUrl}?id=' + eventId).show();
                    } else {
                        $('#update-btn').hide(); // Hide the button if the user cannot update
                    }

                    $('#attendanceModal').modal('show');
                }
            });

            fullCalendar.render();
            $(calendarEl).data('fc', fullCalendar);
        });
    }

    $('#teacher-select, #year-select').change(function() {
        teacherId = $('#teacher-select').val();
        year = $('#year-select').val();

        if (teacherId && year) {
            $('.calendar-container').each(function() {
                var calendar = $(this).data('fc');
                if (calendar) {
                    calendar.destroy();
                }
            });
            initializeCalendars();
        }
    });

    // Close modal on button click
    $('.close, .btn-secondary').click(function() {
        $('#attendanceModal').modal('hide');
    });
});
JS;

$this->registerJs($script);

