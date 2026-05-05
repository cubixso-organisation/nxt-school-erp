<?php

use app\modules\admin\models\ExamsResult;

?>

<body>
    <div class="marksheet">

        <h3 class="text-center"><?= $examWiseMarksheet->exam->name_of_exam ?? "" ?> ( <?= $examWiseMarksheet->session->title ?? "" ?> )</h3>

        <div class="contenter">

            <div class="left-item">
                <p class="text-left">Adm. No. : <?= $examWiseMarksheet->student->admission_number ?? "" ?></p>
                <p class="text-left">Student Name : <?= $examWiseMarksheet->student->student_name ?? "" ?></p>
                <p class="text-left">Father's Name : <?= $examWiseMarksheet->class->title ?? "" ?> </p>
                <p class="text-left">Mother's Name : <?= $examWiseMarksheet->section->section_name ?? "" ?></p>
                <p class="text-left">Gender. : <?= $examWiseMarksheet->student->rool_number ?? "" ?></p>
            </div>

            <div class="middle-item">
                <p class="text-left"> Id No.. <?= $examWiseMarksheet->exam->name_of_exam ?? "" ?></p>
                <p class="text-left"> Class. <?= $examWiseMarksheet->exam->name_of_exam ?? "" ?></p>
                <p class="text-left">Academic Year: <?= $examWiseMarksheet->exam->id ?? "" ?> </p>
                <p class="text-left">Date Of Issue: <?= $examWiseMarksheet->exam->id ?? "" ?> </p>
            </div>

            <div class="middle-item-right">
                <p class="text-left"> <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" srcset=""></p>
            </div>
        </div>

        <div class="contenter">
            <div class="left-item">
                <table class="marksheet-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="vertical-text">Performance</th>
                            <th>Written Work</th>
                            <th>Project Work</th>
                            <th>Slip Test</th>
                            <th>Total</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examResults as $examResult) { ?>
                            <tr>
                                <td><?= $examResult->subject->subject_name ?? "" ?></td>
                                <td><?= $examResult->total_marks ?? "-" ?></td>
                                <td><?= $examResult->marks_scored ?? "-" ?></td>
                                <td>
                                    <?php if ($examResult->marks_type == ExamsResult::marks_type_grade) {
                                        echo $examResult->grade ?? "-";
                                    } else {
                                        echo $examResult->cgpa ?? "-";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td><b> Grand Total </b></td>
                            <td><?= $examWiseMarksheet->total_marks ?? "" ?></td>
                            <td><?= $examWiseMarksheet->total_obtained_marks ?? "" ?></td>
                            <td>
                                <?php if ($examWiseMarksheet->marks_type == ExamsResult::marks_type_grade) {
                                    echo $examWiseMarksheet->total_grade ?? "-";
                                } else {
                                    echo $examWiseMarksheet->total_cgpa ?? "-";
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Chart Section -->
            <div class="right-item">
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                    <img id="chartImg" />
                </div>
            </div>
        </div>

        <table class="marksheet-table">
            <thead>
                <tr>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <td><?= $examWiseMarksheet->total_percentage ?? "" ?>%</td>
            </tbody>
        </table>

        <div class="footer" style="">
            <div class="left-item">..................................... Class Teacher Signature</div>
            <div class="right-item">..................................... Parent Signature</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Exam Scores',
                    data: [12, 19, 3, 5, 2, 3, 7], // Replace with actual data
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Convert chart to image after rendering
        barChart.canvas.toDataURL('image/png', function(imgURL) {
            document.getElementById('chartImg').src = imgURL;
            document.getElementById('barChart').style.display = 'none';
        });
    </script>
</body>

</html>