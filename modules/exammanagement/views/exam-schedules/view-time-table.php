<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

// Create a button to generate PDF
if (!empty($post)) {
    echo Html::a('Generate PDF', ['generate-pdf', 'academic_year' => $post['ExamSchedules']['session_id'], 'exam_id' => $post['ExamSchedules']['exam_id'], 'class' => $post['ExamSchedules']['class_id']], ['class' => 'btn btn-primary', 'target' => '_blank']);
} else {
    echo Html::a('Generate PDF', ['generate-pdf'], ['class' => 'btn btn-primary', 'target' => '_blank']);
}

// Your table code remains the same
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Session</th>
            <th scope="col">Exam Type</th>
            <th scope="col">Class</th>
            <th scope="col">Subject</th>
            <th scope="col">Date</th>
            <th scope="col">Duration</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($studentTimeTable as $examTimeTable) { ?>
            <tr>
                <td><?= $examTimeTable->session->title ?></td>
                <td><?= $examTimeTable->exam->name_of_exam ?></td>
                <td><?= $examTimeTable->class->title ?></td>
                <td><?= $examTimeTable->subject->subject_name ?></td>
                <td><?= date('Y-m-d', strtotime($examTimeTable->exam_date)) ?></td>
                <td><?= date('H:i', strtotime($examTimeTable->exam_duration)) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>