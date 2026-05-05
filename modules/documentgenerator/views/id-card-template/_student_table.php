<?php

use yii\helpers\Url;

?>

<!-- Generate button placed above the table -->
<div>
    <button id="generate-btn" class="btn btn-primary btn-sm">Generate</button>
</div>

<form id="generate-form" method="post" action="<?= Url::toRoute(['generate-pdf']) ?>" target="_blank">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"> <!-- CSRF token for POST request -->
    <input type="hidden" name="selected_ids" id="selected-ids">
    <input type="hidden" name="template_id" id="template-id">
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><input type="checkbox" id="select-all"></th> <!-- Checkbox to select all -->
            <th scope="col">Admisson No</th>
            <th scope="col">Student Name</th>
            <th scope="col">Class</th>
            <th scope="col">Dob</th>
            <th scope="col">Mobile Number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($studentDetails as $studentDetail) { ?>
            <tr>
                <td><input type="checkbox" class="row-select" value="<?= $studentDetail->id ?>"></td> <!-- Checkbox for each row -->
                <td><?= $studentDetail->admission_number ?></td>
                <td><?= $studentDetail->student_name ?></td>
                <td><?= $studentDetail->studentClass->title ?></td>
                <td><?= $studentDetail->date_of_birth ?></td>
                <td><?= $studentDetail->phone_number ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    // Select/Deselect all checkboxes
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('.row-select');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    };

    // Handle Generate button click
    document.getElementById('generate-btn').onclick = function() {
        var selectedIds = [];
        var checkboxes = document.querySelectorAll('.row-select:checked');
        for (var checkbox of checkboxes) {
            selectedIds.push(checkbox.value);
        }

        var templateId = document.getElementById('student-table-container').getAttribute('data-templateid');

        if (selectedIds.length > 0) {
            // Set the selected IDs in the hidden input field
            document.getElementById('selected-ids').value = selectedIds.join(",");
            document.getElementById('template-id').value = templateId;
            // Submit the form
            document.getElementById('generate-form').submit();
        } else {
            alert("Please select at least one student.");
        }
    };
</script>