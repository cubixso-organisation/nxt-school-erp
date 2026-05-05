<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

// Create a button to generate PDF


// Your table code remains the same
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Exam</th>
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
                <td><?= $examTimeTable->exam->name_of_exam ?></td>
                <td><?= $examTimeTable->class->title ?></td>
                <td><?= $examTimeTable->subject->subject_name ?></td>
                <td><?= date('Y-m-d', strtotime($examTimeTable->exam_date)) ?></td>
                <td><?= date('H:i', strtotime($examTimeTable->exam_duration)) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>