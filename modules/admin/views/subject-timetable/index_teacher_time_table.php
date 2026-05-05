<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\leavemanagement\models\search\SubjectTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\SubjectTimetable;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', 'Subject Timetables');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
    $('.search-form').toggle(1000);
    return false;
});";
$this->registerJs($search);


?>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<?php $form = ActiveForm::begin([
    'action' => ['search-teacher-time-table'],
    'method' => 'post',
]); ?>
<div class="col-md-4">
    <?= $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->where(['campus_id' => (new User)->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Teacher')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <div class="form-group">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php


    $days = array_unique(array_column($teacherTimeTable, 'day_id'));
    $timeSlots = array_unique(array_map(function ($entry) {
        return $entry['time_from'] . '-' . $entry['time_to'];
    }, $teacherTimeTable));

    echo '<table>';
    echo '<tr><th>Days</th>';
    // Loop through time slots to create table header
    foreach ($timeSlots as $timeSlot) {
        echo '<th>' . $timeSlot . '</th>';
    }
    echo '</tr>';

    foreach ($days as $day) {
        echo '<tr><td>' . $day . '</td>';

        // Loop through time slots for each day
        foreach ($timeSlots as $slot) {
            list($startTime, $endTime) = explode('-', $slot);

            // Find the entry in $teacherTimeTable matching the current day and time slot
            $matchingEntry = array_filter($teacherTimeTable, function ($entry) use ($day, $startTime, $endTime) {
                return $entry['day_id'] === $day && $entry['time_from'] === $startTime && $entry['time_to'] === $endTime;
            });

            $subject = !empty($matchingEntry) ? reset($matchingEntry)['subject']->subject_name : '';
            $subject .= '-';
            $subject .= !empty($matchingEntry) ? reset($matchingEntry)['class']->title : '';
            $subject .= '-';
            $subject .= !empty($matchingEntry) ? reset($matchingEntry)['section']->section_name : '';
            echo '<td>' . $subject . '</td>';
        }
        echo '</tr>';
    }

    echo '</table>';
    ?>