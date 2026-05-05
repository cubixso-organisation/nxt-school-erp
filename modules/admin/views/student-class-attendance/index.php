<?php

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

// Encode the initial attendance data as JSON
$attendanceDataJson = \yii\helpers\Json::encode([]); // Will fetch real data via Ajax
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 20px;
  }

  h1 {
    text-align: center;
    margin-bottom: 20px;
  }

  #calendarContainer {
    display: grid;
    /* Change to 3 columns */
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin: 0 auto;
    max-width: 1600px;
  }

  .calendar-month {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
  }

  .month-title {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
  }

  .fc-daygrid-day {
    padding: 10px;
    border-radius: 8px;
    position: relative;
  }

  .fc-daygrid-day:hover {
    background-color: #e0f7fa;
  }

  .attendance-entry {
    background-color: #4caf50;
    border-left: 4px solid #4caf50;
  }

  .attendance-entry.absent {
    background-color: #f44336;
    border-left-color: #f44336;
  }

  .fc-daygrid-day-number {
    font-weight: bold;
    font-size: 14px;
    position: absolute;
    top: 8px;
    left: 8px;
  }

  #loadingIndicator {
    text-align: center;
    font-size: 18px;
    display: none;
  }


  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 24px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  .modal-header {
    font-size: 24px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
  }

  .modal-body {
    margin-bottom: 20px;
    font-size: 24px;

  }

  .modal-footer {
    text-align: right;
  }

  .modal-footer button {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
</style>

<h1>Student Attendance Calendar - Full Year View</h1>

<!-- Student Selection Form -->
<?php $form = ActiveForm::begin([
  'id' => 'student-selection-form',
  'method' => 'get', // Use 'get' method to avoid form data loss on page refresh
]); ?>

<div class="student-selection-form">

  <!-- Class Dropdown (Select Class Title) -->
  <?= $form->field($searchModel, 'class_id')->dropDownList(
    ArrayHelper::map($classes, 'id', 'title'), // Map 'id' => 'class_title' for dropdown
    [
      'id' => 'class-id',
      'prompt' => 'Select Class',
    ]
  ) ?>

  <!-- Section Dropdown (Depends on Class) -->
  <?= $form->field($searchModel, 'section_id')->widget(DepDrop::classname(), [
    'options' => ['id' => 'section-id'],
    'pluginOptions' => [
      'depends' => ['class-id'],
      'placeholder' => 'Select Section',
      'url' => Url::to(['/admin/student-class-attendance/student-sections']),
    ]
  ]); ?>

  <!-- Student Dropdown (Depends on Section) with Search -->
  <?= $form->field($searchModel, 'student_id')->widget(DepDrop::classname(), [
    'type' => DepDrop::TYPE_SELECT2,
    'options' => ['id' => 'student-id'],
    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
    'pluginOptions' => [
      'depends' => ['section-id'],
      'placeholder' => 'Search and Select Student',
      'url' => Url::to(['/admin/student-class-attendance/students']),
    ],
  ]); ?>

  <!-- Submit Button -->
  <div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'submitBtn']) ?>
  </div>
</div>

<?php ActiveForm::end(); ?>

<!-- Loading Indicator -->
<div id="loadingIndicator">Loading Attendance Data...</div>

<!-- Calendar Container -->
<div id="calendarContainer"></div>
<!-- Modal for event details -->
<!-- Modal for event details -->
<div id="eventModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <!-- <span class="close">&times;</span> -->
      <!-- <h2>Attendance Details</h2> -->
    </div>
    <div class="modal-body">
      <p id="eventDetails"></p>
    </div>
    <div class="modal-footer">
      <button id="closeModalBtn">Close</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<script>
  $(document).ready(function() {
    const $yearDropdown = $('<select id="yearDropdown"></select>');
    const $studentDropdown = $('#student-id');
    const $calendarContainer = $('#calendarContainer');
    const $loadingIndicator = $('#loadingIndicator');
    const $eventModal = $('#eventModal');
    const $eventDetails = $('#eventDetails');
    const $modalCloseBtn = $('.close');
    const $closeModalBtn = $('#closeModalBtn');
    const $submitBtn = $('#submitBtn');
    let currentYear = new Date().getFullYear();

    // Populate year dropdown
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
      $yearDropdown.append(`<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`);
    }
    $('.student-selection-form').append($yearDropdown);

    // Function to render the calendar for the selected year and student
    function renderYearCalendar(year, studentId) {
      $calendarContainer.hide();
      $loadingIndicator.show();
      $calendarContainer.empty();

      fetchAttendanceData(year, studentId).then(attendanceData => {
        $loadingIndicator.hide();
        $calendarContainer.show();

        const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        months.forEach((month, index) => {
          const $monthDiv = $('<div class="calendar-month"></div>');

          const $monthTitle = $('<div class="month-title"></div>');
          $monthTitle.text(new Date(year, index).toLocaleString('default', {
            month: 'long'
          }) + ' ' + year);
          $monthDiv.append($monthTitle);

          const $calendarEl = $('<div></div>');
          $monthDiv.append($calendarEl);
          $calendarContainer.append($monthDiv);

          const calendar = new FullCalendar.Calendar($calendarEl[0], {
            initialView: 'dayGridMonth',
            initialDate: `${year}-${month}-01`,
            events: attendanceData.filter(event => event.date.startsWith(`${year}-${month}`)).map(event => ({
              title: event.title,
              start: event.date, // Ensure this matches the correct date format
              extendedProps: {
                date: event.date,
                starttime: event.starttime,
                endtime: event.endtime,
                getTimeOfDay: event.getTimeOfDay,
                teacher: event.teacher

              },
              backgroundColor: event.title.includes('Present') ? 'green' : 'red',
              borderColor: event.title.includes('Present') ? 'green' : 'red',
              textColor: '#fff'
            })),
            headerToolbar: false,

            eventClick: function(info) {
              $eventDetails.html(`
              <b>Teacher</b>(${info.event.extendedProps.teacher}) <br>
         ${info.event.title} 
         ${info.event.extendedProps.date}(${info.event.extendedProps.getTimeOfDay}) 
         ${info.event.extendedProps.starttime}  
         ${info.event.extendedProps.endtime} 
    `);
              $eventModal.show();
            },

            eventContent: function(arg) {
              let $attendanceDiv = $('<div></div>');
              $attendanceDiv.addClass(arg.event.classNames);
              $attendanceDiv.text(arg.event.title);
              return {
                domNodes: [$attendanceDiv[0]]
              };
            }


          });
          calendar.render();
        });
      });
    }

    function fetchAttendanceData(year, studentId) {
      return $.ajax({
        url: `<?= \yii\helpers\Url::to(['fetch-attendance-data']) ?>`,
        data: {
          studentId,
          year
        },
        dataType: 'json'
      });
    }

    // Render calendar only on submit button click
    $submitBtn.on('click', function(e) {
      e.preventDefault();
      renderYearCalendar($yearDropdown.val(), $studentDropdown.val());
    });

    $modalCloseBtn.on('click', function() {
      $eventModal.hide();
    });

    $closeModalBtn.on('click', function() {
      $eventModal.hide();
    });

    $(window).on('click', function(event) {
      if ($(event.target).is($eventModal)) {
        $eventModal.hide();
      }
    });
  });
</script>