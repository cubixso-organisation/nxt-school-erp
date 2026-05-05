<?php

use yii\helpers\Html;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Final Marksheet</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .profile {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .marks-table th,
        .marks-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .marks-table th {
            background-color: #f2f2f2;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .attendance-table th {
            background-color: #f2f2f2;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Adjust column widths as needed */
            grid-gap: 20px;
            /* Adjust gap between charts */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= Html::encode($structure['header_image_url']) ?>" alt="Header Image">
        </div>


        <h1>Final Results</h1>
        <div class="profile">


            <div class="text-left">
                <p><strong>Name:</strong> <?= Html::encode($structure['student_details']['student_name']) ?></p>
                <p><strong>Father's Name:</strong> <?= Html::encode($structure['student_details']['father_name']) ?></p>
                <p><strong>Mother's Name:</strong> <?= Html::encode($structure['student_details']['mother_name']) ?></p>
            </div>
            <div class="text-center">
                <p><strong>Gender:</strong> <?= Html::encode($structure['student_details']['gender']) ?></p>
                <p><strong>ID No:</strong> <?= Html::encode($structure['student_details']['id_no']) ?></p>
                <p><strong>Class:</strong> <?= Html::encode($structure['student_details']['class']) ?></p>
            </div>
            <div class="text-end">
                <p><strong>Session:</strong> <?= Html::encode($structure['student_details']['session']) ?></p>
                <p><strong>Date:</strong> <?= Html::encode($structure['student_details']['date']) ?></p>
            </div>


            <div class="profile-image">
                <img src="<?= Html::encode($structure['profile_image']) ?>" alt="Profile Image">
            </div>
        </div>
        <h2>Marks</h2>
        <table class="marks-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <?php foreach ($structure['exams'] as $exam): ?>
                        <th><?= Html::encode($exam) ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>

                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($structure['subjects'] as $subject): ?>
                    <tr>
                        <td><?= Html::encode($subject) ?></td>
                        <?php foreach ($structure['exams'] as $exam): ?>
                            <td>
                                <?= isset($structure['subject_marks'][$subject][$exam])
                                    ? Html::encode($structure['subject_marks'][$subject][$exam]['obtained'])
                                    : 'N/A'
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td><?= Html::encode($structure['total_marks_per_subject'][$subject]) ?></td>
                        <td>
                            <?= isset($structure['subject_marks'][$subject][$exam])
                                ? Html::encode($structure['subject_marks'][$subject][$exam]['grade'])
                                : 'N/A'
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Total Marks Row -->
                <tr>
                    <td><strong>Total Marks</strong></td>
                    <?php foreach ($structure['exams'] as $exam): ?>
                        <td>
                            <strong>
                                <?= isset($structure['total_marks_per_exam'][$exam])
                                    ? Html::encode($structure['total_marks_per_exam'][$exam])
                                    : 'N/A'
                                ?>
                            </strong>
                        </td>

                    <?php endforeach; ?>
                    <td colspan="3"></td> <!-- Empty columns for Total, Max Marks, and Grade -->
                </tr>
            </tbody>
        </table>

        <!-- Attendance Section -->
        <h2>Attendance Report</h2>
        <table class="marks-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Working Days</th>
                    <th>Present Days</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($structure['attendance'] as $attendance): ?>
                    <tr>
                        <td><?= Html::encode($attendance['month']) ?></td>
                        <td><?= Html::encode($attendance['working_days']) ?></td>
                        <td><?= Html::encode($attendance['present_days']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <div class="chart-grid">
            <div class="chart-item">
                <h5>Subject Marks Chart</h5>
                <canvas id="subject-marks-chart"></canvas>
            </div>
            <div class="chart-item">
                <h5>Attendance Chart</h5>
                <canvas id="attendance-chart"></canvas>
            </div>
        </div>

    </div>
    <script>
        // Data for the subject marks chart
        const subjectData = {
            labels: <?= json_encode(array_keys($structure['subjects'])) ?>,
            datasets: [{
                label: 'Total Marks',
                data: <?= json_encode(array_values($structure['total_marks_per_subject'])) ?>
            }]
        };

        // Data for the attendance chart
        const attendanceData = {
            labels: <?= json_encode(array_keys($structure['attendance'])) ?>,
            datasets: [{
                label: 'Present Days',
                data: <?= json_encode(array_map(function ($attendance) {
                            return $attendance['present_days'];
                        }, $structure['attendance'])) ?>
            }]
        };

        // Initialize the subject marks chart
        const ctx1 = document.getElementById('subject-marks-chart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: subjectData,
            options: {
                // Customize chart options as needed
            }
        });

        // Initialize the attendance chart
        const ctx2 = document.getElementById('attendance-chart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: attendanceData,
            options: {
                // Customize chart options as needed
            }
        });
    </script>
</body>

</html>