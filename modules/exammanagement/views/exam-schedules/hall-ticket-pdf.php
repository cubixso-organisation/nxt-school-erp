<?php

use app\modules\documentgenerator\models\base\IdCardTemplate;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

?>
<?php 
// Check if the campus ID is silver crist before rendering the hall ticket
if ($examHallTicketStudent->campus->id == 71 || $examHallTicketStudent->campus->id == 81):
 ?>


<!-- Header Section -->
<div style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px; width: 100%;">
    <!-- Logo Section -->
    <div style="margin-right: 20px;">
        <?php if (isset($examHallTicketStudent->campus->school_logo) && !empty($examHallTicketStudent->campus->school_logo)) : ?>
            <img src="<?= $examHallTicketStudent->campus->school_logo ?>" alt="School Logo" style="max-width: 190px; max-height: 190px;">
        <?php else : ?>
            No Logo
        <?php endif; ?>
    </div>

    <!-- School Name Section -->
    <div style="text-align: center; margin-top:-140px">
    <h1 style="margin: 0; font-size: 46px;margin-left:70px"><b><?= $examHallTicketStudent->campus->name_of_the_educational_Institution ?? "" ?></b></h1>
    <h4 style="margin: 5px 0;font-size:20px">RECOGNISED BY THE GOVT. OF T.S.</h4>

    <table style="width: 100%; margin: 0px 0;">
    <tr>
        <td style="text-align: right; width: 50%; margin-left: 150px;">
            <?php if ($examHallTicketStudent->campus->id == 71): ?>
                <b>RAHMATH COLONY, BANDLAGUDA <br>CHANDRAYANGUTTA, HYDERABAD.</b>
            <?php elseif ($examHallTicketStudent->campus->id == 81): ?>
                <b>Beside Bajaj Electronics, Hashamabad,<br> Chandrayangutta, Hyderabad-05.</b>
            <?php endif; ?>
        </td>
        <td style="border-left: 2px solid black; padding-left: 10px; text-align: left; width: 50%;">
            <?php if ($examHallTicketStudent->campus->id == 71): ?>
                <b>Phone: 7799770123</b><br>
                <b>Email: schoolsilvercrest@gmail.com</b>
            <?php elseif ($examHallTicketStudent->campus->id == 81): ?>
                <b>Phone: 7799770277</b><br>
                <b>Email: schoolsilvercrest@gmail.com</b>
            <?php endif; ?>
        </td>
    </tr>
</table>


    <h1 style="margin: 5px 0;font-family: 'Times New Roman', serif;"> <b>HALL TICKET</b> </h1>
    <h2> <b><?= !empty($studentTimeTable) ? $studentTimeTable[0]->exam->name_of_exam ?? "" : "" ?> EXAMINATION</b></h2>
</div>

</div>

<!-- Student Information and Image Section -->
<!-- Student Details and Image Row -->
<div style="margin-top: 20px; width: 100%;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Student Details Table - 70% Width -->
            <td style="width: 70%; vertical-align: top; padding: 10px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 30%;"><b>Name</b></td>
                        <td style="width: 70%;">: <?= $examHallTicketStudent->student_name ?? "" ?></td>
                        <td style="width: 30%;"><b>Father Name</b></td>
                        <td style="width: 70%;">: <?= $examHallTicketStudent->parent->name_of_the_father ?? "" ?></td>
                    </tr>
                    <tr>
                        <td><b>Admission No</b></td>
                        <td>: <?= $examHallTicketStudent->admission_number ?? "" ?></td>
                        <td><b>Roll No</b></td>
                        <td>: <?= $examHallTicketStudent->rool_number ?? "" ?></td>
                    </tr>
                    <tr>
                    <td><b>Class</b></td>
                    <td>: <?= $examHallTicketStudent->studentClass->title ?? "" ?> </td>
                        <td><b>Section</b></td>
                        <td>: <?= $examHallTicketStudent->section->section_name ?? "" ?></td>
                        
                    </tr>
                    
                </table>
            </td>

            <!-- Student Image - 30% Width -->
            <td style="width: 30%; text-align: center; vertical-align: top; padding: 10px;">
                <?php if (isset($examHallTicketStudent->profile_photo) && !empty($examHallTicketStudent->profile_photo)) : ?>
                    <img src="<?= $examHallTicketStudent->profile_photo ?>" alt="Student Image" style="width: 100px; height: 100px; border: 1px solid #000;">
                <?php else : ?>
                    <div style="width: 90px; height: 90px; border: 1px solid #000; display: flex; align-items: center; justify-content: center;">No Image</div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>






<!-- Dynamic Exam Schedule Table -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr>
            <?php foreach ($studentTimeTable as $exam) : ?>
                <th style="border: 1px solid black; padding: 8px; text-align: center;"><?= $exam->subject->subject_name ?? '' ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php foreach ($studentTimeTable as $exam) : ?>
                <td style="border: 1px solid black; padding: 8px; text-align: center;">
                    <?= Yii::$app->formatter->asDate($exam->exam_date, 'php:d M Y') ?>
                </td>
            <?php endforeach; ?>
        </tr>
        <tr>
    <?php foreach ($studentTimeTable as $exam) : ?>
        <td style="border: 1px solid black; padding: 5px; text-align: center; background-color: #fff; height:35px; font-size: 12px; color: #aaa;">
            Teacher Sign
        </td>
    <?php endforeach; ?>
</tr>

    </tbody>
</table>

<!-- Footer with Static Instructions -->
<!-- <div style="margin-top: 20px;">
    <ol style="font-size: 14px;">
        <li>All the exams are on the same time i.e., 10:00 AM to 12:30 PM</li>
        <li>School will run half day. Students should report to school 15 minutes before the exam starts.</li>
        <li>Clear your fee dues. Ignore if paid.</li>
    </ol>
</div> -->

<!-- Signature Section -->
 
<div class="row" style="margin-top: 50px;">
    <?php
    // Fetch the signature for the specific campus
    $sig = IdCardTemplate::find()->where(['campus_id' => $examHallTicketStudent->campus->id])->one();
    ?>
   
    <div style="text-align: right; width: 100%;">
        <?php if (isset($sig->signature) && !empty($sig->signature)) : ?>
            <!-- Display the signature as an image -->
            <img src="<?= $sig->signature ?>" alt="Principal's Signature" style="max-width: 150px; height: auto;">
        <?php endif; ?>
        <p><b>Principal's Signature</b></p>
    </div>
</div>
<div style="text-align: left; width: 100%;margin-top:-30px">
        
        <p><b>Teacher's Signature</b></p>
    </div>
<?php elseif ($examHallTicketStudent->campus->id == 51): ?>
    <div style="border: 1px solid black; padding: 10px; font-family: Arial, sans-serif;">

     <!-- Header Section -->
     <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <img src="<?= $examHallTicketStudent->campus->school_logo ?? 'default-logo.png' ?>" alt="School Logo" style="max-width: 80px;">
        <div style="text-align: center; flex: 1; margin-left: -80px;margin-top:-70px">
            <h3><?= !empty($studentTimeTable) ? $studentTimeTable[0]->exam->name_of_exam ?? "" : "" ?></h3>
            <h4 style="margin: 5px 0; font-size: 16px;"><b><?= $examHallTicketStudent->campus->name_of_the_educational_Institution ?? '' ?></b></h4>
        </div>
    </div>

    <!-- Student Details -->
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%; padding: 5px;"><b>STUDENT NAME:</b></td>
                <td style="width: 25%; padding: 5px;"><?= $examHallTicketStudent->student_name ?? '' ?></td>
                <td style="width: 25%; padding: 5px;"><b>HALLTICKET NUMBER:</b></td>
                <td style="width: 25%; padding: 5px;"><?= $examHallTicketStudent->admission_number ?? '' ?></td>
            </tr>
            <tr>
                <td style="padding: 5px;"><b>S/O or D/O:</b></td>
                <td style="padding: 5px;"><?= $examHallTicketStudent->parent->name_of_the_father ?? '' ?></td>
                <td style="padding: 5px;"><b>CLASS:</b></td>
                <td style="padding: 5px;"><?= $examHallTicketStudent->studentClass->title ?? '' ?></td>
            </tr>
            <tr>
                <td style="padding: 5px;"><b>SECTION:</b></td>
                <td style="padding: 5px;"><?= $examHallTicketStudent->section->section_name ?? '' ?></td>
            </tr>
        </table>
    </div>

    <!-- Exam Schedule -->
    <div style="text-align: center; margin-bottom: 10px;">
        <h4 style="margin: 0; font-size: 16px;"><b>EXAM SCHEDULE</b></h4>
    </div>
    <table style="width: 100%; border: 1px solid black; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">EXAM DATE</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">TIME & SUBJECT</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">TEACHER SIGNATURE</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">TIME & SUBJECT</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">TEACHER SIGNATURE</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalSubjects = count($studentTimeTable);
        $leftSubjects = array_slice($studentTimeTable, 0, 3);
        $rightSubjects = array_slice($studentTimeTable, 3, 3);

        for ($i = 0; $i < max(count($leftSubjects), count($rightSubjects)); $i++): 
            $leftExam = $leftSubjects[$i] ?? null;
            $rightExam = $rightSubjects[$i] ?? null;
        ?>
            <tr>
                <td style="border: 1px solid black; padding: 5px; text-align: center;">
                    <?= $leftExam ? date('d-m-Y', strtotime($leftExam->exam_date)) : '' ?>
                </td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;">
                    <?php if ($leftExam): ?>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
                            <span><?= $leftExam->start_time . ' - ' . $leftExam->end_time ?></span>
                            <span style="border-left: 1px solid black; height: 1em; margin: 0 5px;"></span>
                            <span><?= $leftExam->subject->subject_name ?></span>
                        </div>
                    <?php endif; ?>
                </td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;">&nbsp;</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;">
                    <?php if ($rightExam): ?>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
                            <span><?= $rightExam->start_time . ' - ' . $rightExam->end_time ?></span>
                            <span style="border-left: 1px solid black; height: 1em; margin: 0 5px;"></span>
                            <span><?= $rightExam->subject->subject_name ?? 'N/A' ?></span>
                        </div>
                    <?php endif; ?>
                </td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;">&nbsp;</td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>


    <!-- Footer Section -->
    <div style="margin-top: 20px;">
        <p style="text-align: center;">Supervised by <?= $examHallTicketStudent->campus->name_of_the_educational_Institution ?? '' ?>, <?= $examHallTicketStudent->campus->address ?? '' ?></p>
        <p style="text-align: left;"><b>REMARKS:</b></p>
        <div class="row" style="margin-top: 20px;">
    <?php
    // Fetch the signature for the specific campus
    $sig = IdCardTemplate::find()->where(['campus_id' => $examHallTicketStudent->campus->id])->one();
    ?>
   
    <div style="text-align: right; width: 100%;">
        <?php if (isset($sig->signature) && !empty($sig->signature)) : ?>
            <!-- Display the signature as an image -->
            <img src="<?= $sig->signature ?>" alt="Principal's Signature" style="max-width: 150px; height: auto;">
        <?php endif; ?>
        <p><b>Principal's Signature</b></p>
    </div>
</div>
<div style="text-align: left; width: 100%;margin-top:-30px">
        
        <p><b>Teacher's Signature</b></p>
    </div>
    </div>
    <hr style="border: 1.5px solid black; margin: 10px 0;">
<p style="text-align:center">Education is the passport to the future,for tomorrow belongs to those who prepare it for today</p>

</div>
<?php else: ?>
    <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px; width: 100%;">
    <!-- Logo Section -->
    <div style="margin-right: 20px;">
        <?php if (isset($examHallTicketStudent->campus->school_logo) && !empty($examHallTicketStudent->campus->school_logo)) : ?>
            <img src="<?= $examHallTicketStudent->campus->school_logo ?>" alt="School Logo" style="max-width: 100px; max-height: 100px;">
        <?php else : ?>
            No Logo
        <?php endif; ?>
    </div>

    <!-- School Name Section -->
    <div style="text-align: center;">
        <h2 style="margin: 0; font-size: 28px;"><b><?= $examHallTicketStudent->campus->name_of_the_educational_Institution ?? "" ?></b></h2>
        <h4 style="margin: 5px 0;"><b><?= $examHallTicketStudent->campus->address ?? "" ?></b></h4>
        <h4 style="margin: 5px 0;"><b><?= $examHallTicketStudent->academicYear->title ?? "" ?></b></h4>
    </div>
</div>


<div style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 20%;"><b>Name</b></td>
            <td style="width: 30%;">: <?= $examHallTicketStudent->student_name ?? "" ?></td>
            <td style="width: 20%;"><b>Admission No</b></td>
            <td style="width: 30%;">: <?= $examHallTicketStudent->admission_number ?? "" ?></td>
        </tr>
        <tr>
            <td><b>Father Name</b></td>
            <td>: <?= $examHallTicketStudent->parent->name_of_the_father ?? "" ?></td>
            <td><b>Roll No</b></td>
            <td>: <?= $examHallTicketStudent->rool_number ?? "" ?></td>
        </tr>
        <tr>
            <td><b>Section</b></td>
            <td>: <?= $examHallTicketStudent->section->section_name ?? "" ?></td>
            <td><b>Exam</b></td>
            <td>: <?= !empty($studentTimeTable) ? $studentTimeTable[0]->exam->name_of_exam ?? "" : "" ?></td>
        </tr>
    </table>
</div>





<!-- Student Information in Two Columns -->


<!-- Exam Schedule Table with Borders -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px; text-align: center;">Subject Name</th>
            <th style="border: 1px solid black; padding: 8px; text-align: center;">Exam Date</th>
            <th style="border: 1px solid black; padding: 8px; text-align: center;">Room No.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Initialize arrays for subjects, dates, and room numbers
        $subjects = [];
        $dates = [];
        $room_no = [];

        // Collect data for each row
        foreach ($studentTimeTable as $examTimeTable) {
            $subjects[] = $examTimeTable->subject->subject_name;
            $dates[] = date('Y-m-d', strtotime($examTimeTable->exam_date)); // Format date as YYYY-MM-DD
            $room_no[] = $examTimeTable->room_no;
        }
        ?>

        <?php if (!empty($subjects) && !empty($dates)) : ?>
            <!-- Display subject names, dates, and room numbers -->
            <?php foreach (array_map(null, $subjects, $dates, $room_no) as [$subject, $date, $room]) { ?>
                <tr>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;"><?= $subject ?></td>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;"><?= $date ?></td>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;"><?= $room ?></td>
                </tr>
            <?php } ?>
        <?php else : ?>
            <tr>
                <td colspan="3" style="border: 1px solid black; padding: 8px; text-align: center;">
                    <h3>No exams found!</h3>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Footer with Signature -->
<div class="row" >
    <?php
    // Fetch the signature for the specific campus
    $sig = IdCardTemplate::find()->where(['campus_id' => $examHallTicketStudent->campus->id])->one();
    ?>
    <div style="text-align: right; width: 100%;">
        <?php if (isset($sig->signature) && !empty($sig->signature)) : ?>
            <!-- Display the signature as an image -->
            <img src="<?= $sig->signature ?>" alt="Principal's Signature" style="max-width: 150px; height: auto;">
        <?php endif; ?>
        <p><b>Principal's Signature</b></p>
    </div>
</div>

<?php endif; ?>

