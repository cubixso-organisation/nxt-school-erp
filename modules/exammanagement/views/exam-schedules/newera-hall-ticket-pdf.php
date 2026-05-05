<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

// Create a button to generate PDF (optional, if you want to include a button)
// echo Html::a('Download PDF', ['download-pdf'], ['class' => 'btn btn-success']);

?>


<h2 style="text-align:center;"><b><?= $examHallTicketStudent->campus->name_of_the_educational_Institution ?? "" ?></b></h2>
<h4 style="text-align:center;"><b><?= $examHallTicketStudent->campus->address ?? "" ?></b></h4>
<h4 style="text-align:center;"><b>Contact No:<?= $examHallTicketStudent->campus->contact_number_of_the_authorized ?? "" ?> , Email:<?=$examHallTicketStudent->campus->email_id_of_the_authorized?></b></h4>

<h4 style="text-align:center;">
    <b>
        <?php if (!empty($studentTimeTable)) : ?>
            <?= $studentTimeTable[0]->exam->name_of_exam ?? "" ?>
        <?php endif; ?> 
        - HallTicket - <?= $examHallTicketStudent->academicYear->title ?? "" ?>
    </b>
</h4>

<div style="text-align:left;margin-top:50px;">
    <p>Class: <b><?= $examHallTicketStudent->studentClass->title ?? "" ?></b></p>
    <p>Roll No: <b><?= $examHallTicketStudent->rool_number ?? "" ?></b></p>
</div>
<div style="text-align:center;padding-top:-70px;">
    
    <p>Section: <b><?= $examHallTicketStudent->section->section_name ?? "" ?></b></p>
    <p>Admission No: <b><?= $examHallTicketStudent->admission_number ?? "" ?></b></p>
</div>
<div style="text-align:right;padding-top:-70px;">
    <p>Name: <b><?= $examHallTicketStudent->student_name ?? "" ?></b></p>
    <p>Father Name: <b><?= $examHallTicketStudent->parent->name_of_the_father ?? "" ?></b></p>
</div>
<!-- <hr style="text-align: right; position: absolute; top: 0px; width: 100%;"> -->
<!-- Horizontal Table for Subjects -->
<table class="table table-striped" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px; text-align: center;">Subjects</th>
            <?php foreach ($studentTimeTable as $examTimeTable) { ?>
                <th style="border: 1px solid black; padding: 8px; text-align: center;"><?= $examTimeTable->subject->subject_name ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 8px; text-align: center;">Timing</td>
            <?php foreach ($studentTimeTable as $examTimeTable) { ?>
                <td style="border: 1px solid black; padding: 8px; text-align: center;">
                    <?= date('H:i', strtotime($examTimeTable->start_time)) . ' To ' . date('H:i', strtotime($examTimeTable->end_time)) ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px; text-align: center;">Date</td>
            <?php foreach ($studentTimeTable as $examTimeTable) { ?>
                <td style="border: 1px solid black; padding: 8px; text-align: center;">
                    <?= date('Y-m-d', strtotime($examTimeTable->exam_date)) ?>
                </td>
            <?php } ?>
        </tr>
        
        <tr>
            <td style="border: 1px solid black; padding: 8px; text-align: center;">Invigilator</td>
            <?php foreach ($studentTimeTable as $examTimeTable) { ?>
                <td style="border: 1px solid black; padding: 8px; text-align: center;"><?= $examTimeTable->invigilator->name ?? '' ?></td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<div class="row" style="margin-top: 120px;">
    <div class="col-sm-6" style="text-align:left;">
        <p>Class Teacher's Signature</p>
    </div>
    <div class="col-sm-6" style="text-align:right;margin-bottom:300px;margin-top:-130px">
        <img src="themes/school-management/assets/img/newera.png" alt="sign" width="150px">
        <p>Principal's Signature</p>
        <!-- <hr style="text-align: right; position: absolute; top: 30px; width: 15%;"> -->
    </div>
</div>
