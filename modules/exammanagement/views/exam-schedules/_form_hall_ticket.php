<?php

use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\ExamSchedules */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="exam-schedules-form">

    <?php $form = ActiveForm::begin([
        'id' => 'create-time-table-form',
        'options' => ['enctype' => 'multipart/form-data'],
        'action' => 'javascript:void(0)',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['style' => 'display:none', 'value' => (new User())->getCampusId()]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'session_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->Label('Choose Academic Year'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'exam_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'name_of_exam'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Exams')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->Label('Choose Exam'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'class-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->Label('Class'); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'submit_data']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <div id="student-table-container"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script>
    $(document).ready(function() {

        // Handle form submission with Ajax
        $("#create-time-table-form").on('submit', function(e) {


            e.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // Make an Ajax request to fetch student data
            $.ajax({
                url: 'get-exam-data', // Update with your actual controller/action
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Render the table with the fetched data
                    $("#student-table-container").html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script> -->
<script>
    $(document).ready(function() {

        // Handle form submission with Ajax
        $("#create-time-table-form").on('submit', function(e) {


            e.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // Make an Ajax request to fetch student data
            $.ajax({
                url: 'get-student-data', // Update with your actual controller/action
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Render the table with the fetched data
                    $("#student-table-container").html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>