<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

// Create a button to generate PDF


// Your table code remains the same

?>

<h3><?= $studentTimeTable->exam->name_of_exam ?> Exam DateSheet</h3>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">Day & Date</th>
            <th scope="col">Time</th>
            <th scope="col">Subject </th>
            <th scope="col">Duration</th>
            <th scope="col">Name of Exam</th>
            <th scope="col">Max Marks</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($studentTimeTable as $examTimeTable) { ?>
        <?php
            // Assuming $examTimeTable->exam_date contains the date in Y-m-d format
            $examTimestamp = strtotime($examTimeTable->exam_date);
            $formattedDate = date('l, jS F Y', $examTimestamp);
            ?>
            <tr>
                <td><?= $formattedDate ?></td>
                <td><?= date('H:i', strtotime($examTimeTable->exam_date)) ?></td>
                <td><?= $examTimeTable->subject->subject_name ?></td>
                <td><?= date('H:i', strtotime($examTimeTable->exam_duration)) ?> H</td>
                <td><?= $examTimeTable->exam->name_of_exam ?></td>
                <td><?= $examTimeTable->max_marks ?></td>
                
            </tr>
        <?php } ?>
    </tbody>
</table>
