<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Admisson No</th>
            <th scope="col">Student Name</th>
            <th scope="col">Class</th>
            <th scope="col">Dob</th>
            <th scope="col">Mobile Number</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>

        <?php

        use yii\helpers\Url;

        foreach ($studentDetails as $studentDetail) { ?>
            <tr>

                <td><?= $studentDetail->admission_number ?></td>
                <td><?= $studentDetail->student_name ?></td>
                <td><?= $studentDetail->studentClass->title ?></td>
                <td><?= $studentDetail->date_of_birth ?></td>
                <td><?= $studentDetail->phone_number ?></td>
                <td>
                    <a href="<?= Url::toRoute(['generate-pdf', 'studentDetailId' => $studentDetail->id, 'certificateId' => $certificate_id]) ?>" target="_blank">
                        <div class="btn btn-primary btn-sm">Generate</div>
                    </a>
                </td>

            </tr>
        <?php } ?>
    </tbody>
</table>