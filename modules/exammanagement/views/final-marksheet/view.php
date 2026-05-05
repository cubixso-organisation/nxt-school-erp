<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;
use app\modules\admin\models\base\ExamsResult;
use app\modules\admin\models\Subjects;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\FinalMarksheet */

// $this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Final Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

function findExamResult($examId, $subjectId, $studentId)
{
    return ExamsResult::find()
        ->where(['exam_id' => $examId, 'subject_id' => $subjectId, 'student_id' => $studentId])
        ->one();
}
$subjects = Subjects::find()
    ->distinct()
    ->leftJoin('exam_schedules', 'subjects.id = exam_schedules.subject_id')
    ->where(['exam_schedules.class_id' => $model->class_id])
    ->all();

?>

<style>
    th.exam-name {
        text-align: center;

    }
</style>
<div class="final-marksheet-view">

    <div class="card">
        <h5 class="p-3 text-center">Student Marksheet</h5>

        <?php if (!empty($exams)) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="exam-name">Subject</th>
                        <?php foreach ($exams as $exam) : ?>
                            <th colspan="4" class="exam-name"><?= $exam->exam->name_of_exam ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($exams as $exam) : ?>
                            <th>Total Marks</th>
                            <th>Marks Scored</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject) : ?>
                        <tr>
                            <td><?= $subject->subject_name ?></td>
                            <?php foreach ($exams as $exam) : ?>
                                <?php
                                $result = findExamResult($exam->exam_id, $subject->id, $model->student_id);
                                ?>
                                <td><?= $result ? $result->total_marks : '-' ?></td>
                                <td><?= $result ? $result->marks_scored : '-' ?></td>
                                <td><?= $result ? $result->pecentage . '%' : '-' ?></td>
                                <td><?= $result ? $result->grade : '-' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h4 text-align="center">No Exams Found</h4>
        <?php } ?>

    </div>
</div>