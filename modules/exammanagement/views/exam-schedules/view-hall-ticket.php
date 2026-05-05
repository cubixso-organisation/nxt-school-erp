<?php

use kartik\helpers\Html;
use yii\helpers\Url;

?>
<!-- "Download All" Button -->
<!-- "Download All" Button -->
<?= Html::button('Download All', ['class' => 'btn btn-success', 'id' => 'download-all']) ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">
                <?= Html::checkbox('select_all', false, ['id' => 'select-all']) ?> Select All
            </th>
            <th scope="col">Student Name</th>
            <th scope="col">Class</th>
            <th scope="col">Section</th>
            <th scope="col">Roll No</th>
            <th scope="col">Admission No</th>
            <th scope="col">Session</th>
            <th scope="col">Generate PDF</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $serial = 1;
        foreach ($examHallTicket as $hallTicket) { ?>
            <tr>
                <td><?= $serial ?></td>
                <td>
                    <?= Html::checkbox('selected[]', false, ['class' => 'checkbox-item', 'value' => $hallTicket->id]) ?>
                </td>
                <td><?= $hallTicket->student_name ?></td>
                <td><?= $hallTicket->studentClass->title ?></td>
                <td><?= $hallTicket->section->section_name??"" ?></td>
                <td><?= $hallTicket->rool_number ?></td>
                <td><?= $hallTicket->admission_number ?></td>
                <td><?= $hallTicket->academicYear->title ?></td>
                <td>
                    <?php
                    if (!empty($post)) {
                        echo Html::a(
                            'Generate Hall Ticket',
                            [
                                'generate-hall-ticket-pdf',
                                'academic_year' => $post['ExamSchedules']['session_id'],
                                'exam_id' => $post['ExamSchedules']['exam_id'],
                                'class' => $post['ExamSchedules']['class_id'],
                                'id' => $hallTicket->id
                            ], 
                            ['class' => 'btn btn-primary', 'target' => '_blank']
                        );
                    } else {
                        echo Html::a(
                            'Generate Hall Ticket',
                            ['generate-hall-ticket-pdf', 'id' => $hallTicket->id],
                            ['class' => 'btn btn-primary', 'target' => '_blank']
                        );
                    }
                    ?>
                </td>
            </tr>
            <?php $serial++; ?>
        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        // "Select All" functionality
        $('#select-all').on('click', function() {
            var isChecked = $(this).is(':checked');
            $('.checkbox-item').prop('checked', isChecked);
        });

        // Handle "Download All" button click
        $('#download-all').on('click', function() {
            var selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                // Create form for submission
                var form = $('<form>', {
                    'action': '<?= Url::to(['generate-all-hall-tickets-pdf']) ?>',
                    'method': 'POST'
                });

                // Add CSRF token to the form
                var csrfToken = '<?= Yii::$app->request->csrfToken ?>';
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '<?= Yii::$app->request->csrfParam ?>',
                    'value': csrfToken
                }));

                // Add selected student IDs to the form
                selectedIds.forEach(function(id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'selected[]',
                        'value': id
                    }));
                });

                // Add additional exam details to the form
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'exam_id',
                    'value': '<?= $post['ExamSchedules']['exam_id'] ?? '' ?>' // Use the correct value
                }));
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'academic_year',
                    'value': '<?= $post['ExamSchedules']['session_id'] ?? '' ?>'
                }));
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'class',
                    'value': '<?= $post['ExamSchedules']['class_id'] ?? '' ?>'
                }));

                // Submit the form
                form.appendTo('body').submit();
            } else {
                alert('Please select at least one student.');
            }
        });
    });
</script>



