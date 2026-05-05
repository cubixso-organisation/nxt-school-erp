<?php

use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\base\StudentClass;
use app\modules\admin\models\base\TeacherDetails;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\AcademicYears;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OnlineAssessment */
/* @var $questionModels array */

$campusId = User::getCampusesByUser(Yii::$app->user->identity->id); // Get logged-in user's campus ID
?>

<div class="online-assessment-form">

    <?php $form = ActiveForm::begin([
        'id' => 'online-assessment-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']],
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <!-- Hidden Campus ID Field -->
    <?= $form->field($model, 'campus_id')->hiddenInput(['value' => $campusId])->label(false); ?>

    <!-- Title and other inputs -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Enter Title']) ?>
        </div>

        <!-- Academic Year Dropdown (Filtered by Campus ID) -->
        <div class="col-md-6">
            <?= $form->field($model, 'academic_year_id')->dropDownList(
                ArrayHelper::map(AcademicYears::find()->where(['campus_id' => $campusId])->all(), 'id', 'title'),
                ['prompt' => 'Select Academic Year']
            ) ?>
        </div>

          <!-- Subject Dropdown (Filtered by Campus ID) -->
          <div class="col-md-6">
            <?= $form->field($model, 'subject_id')->dropDownList(
                ArrayHelper::map(Subjects::find()->where(['campus_id' => $campusId])->all(), 'id', 'subject_name'),
                ['prompt' => 'Select Subject']
            ) ?>
        </div>
        <!-- Section Dropdown -->
        <div class="col-md-6">
        <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])
            ->orderBy('id')->asArray()->all(), 'id', function ($model) {
            $section_name = $model['section_name'];
            $student_class_id  = $model['student_class_id'];
            $student_class = StudentClass::find()->where(['id' => $student_class_id])->one();
            $class_and_section = $student_class->title . '-' . $section_name;
            return $class_and_section;
        }),
        'options' => ['placeholder' => Yii::t('app', 'Choose Class sections'), 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
        </div>
        
        

        <!-- Duration and Total Marks -->
        <div class="col-md-6">
            <?= $form->field($model, 'duration')->textInput(['placeholder' => 'Enter Duration']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'total_marks')->textInput(['placeholder' => 'Enter Total Marks']) ?>
        </div>
    </div>

    <!-- Questions Section -->
    <h5>Questions</h5>
    <div class="card">
        <div class="card-body p-2" id="questions-container">
            <?php if (!empty($questionModels)): ?>
                <?php foreach ($questionModels as $index => $questionModel): ?>
                    <div class="question-item mb-2">
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($questionModel, "[$index]type")->dropDownList([
                                    'MCQ' => 'Multiple Choice',
                                    'True/False' => 'True/False',
                                    'Text' => 'Text'
                                ], ['prompt' => 'Select Type', 'class' => 'form-control question-type'])->label('Type') ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($questionModel, "[$index]marks")->textInput(['placeholder' => 'Marks', 'class' => 'form-control'])->label('Marks') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($questionModel, "[$index]question_text")->textarea(['rows' => 2, 'placeholder' => 'Enter Question', 'class' => 'form-control'])->label('Question') ?>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-question">Remove</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 options-section">
                                <!-- MCQ Options -->
                                <div class="mcq-options">
                                    <label>Options:</label>
                                    <div class="row">
                                        <?php for ($i = 0; $i < 4; $i++): ?>
                                            <div class="col-md-6">
                                                <div class="option d-flex align-items-center mb-1">
                                                    <input type="text" name="questions[<?= $index ?>][options][<?= $i ?>][text]" placeholder="Option <?= $i+1 ?>" class="form-control mr-2">
                                                    <label class="mb-0">
                                                        <input type="radio" name="questions[<?= $index ?>][correct_option]" value="<?= $i ?>"> Correct
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <!-- True/False Options -->
                                <div class="true-false-options d-flex">
                                    <label class="mr-3">Correct Answer:</label>
                                    <div>
                                        <label class="mr-2"><input type="radio" name="questions[<?= $index ?>][correct_option]" value="True"> True</label>
                                        <label><input type="radio" name="questions[<?= $index ?>][correct_option]" value="False"> False</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No questions added yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hidden Template for New Questions -->
    <div id="question-template" style="display: none;">
        <div class="question-item mb-2">
            <div class="row">
                <div class="col-md-3">
                    <select name="questions[{index}][type]" class="form-control question-type">
                        <option value="">Select Type</option>
                        <option value="MCQ">Multiple Choice</option>
                        <option value="True/False">True/False</option>
                        <option value="Text">Text</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="questions[{index}][marks]" placeholder="Marks" class="form-control">
                </div>
                <div class="col-md-6">
                    <textarea name="questions[{index}][question_text]" rows="2" placeholder="Enter Question" class="form-control"></textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-question">Remove</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 options-section">
                    <!-- MCQ Options -->
                    <div class="mcq-options">
                        <label>Options:</label>
                        <div class="row">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <div class="col-md-6">
                                    <div class="option d-flex align-items-center mb-1">
                                        <input type="text" name="questions[{index}][options][<?= $i ?>][text]" placeholder="Option <?= $i+1 ?>" class="form-control mr-2">
                                        <label class="mb-0"><input type="radio" name="questions[{index}][correct_option]" value="<?= $i ?>"> Correct</label>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <!-- True/False Options -->
                    <div class="true-false-options d-flex">
                        <label class="mr-3">Correct Answer:</label>
                        <div>
                            <label class="mr-2"><input type="radio" name="questions[{index}][correct_option]" value="True"> True</label>
                            <label><input type="radio" name="questions[{index}][correct_option]" value="False"> False</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Question Button -->
    <div class="form-group mt-3">
        <button type="button" id="add-question" class="btn btn-success btn-sm">Add Question</button>
    </div>

    <!-- Status Dropdown -->
    <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
    </div>

    <!-- Submit Button -->
    <div class="form-group col-md-12">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Register JavaScript for dynamic behavior
$this->registerJs("
    // Initialize question index based on existing questions
    var questionIndex = " . (!empty($questionModels) ? count($questionModels) : 0) . ";

    // Add new question
    $('#add-question').click(function() {
        var template = $('#question-template').html().replace(/{index}/g, questionIndex);
        $('#questions-container').append(template);
        questionIndex++;
    });

    // Handle question type change
    $(document).on('change', '.question-type', function() {
        var type = $(this).val();
        var questionItem = $(this).closest('.question-item');
        var optionsSection = questionItem.find('.options-section');
        var mcqOptions = questionItem.find('.mcq-options');
        var tfOptions = questionItem.find('.true-false-options');
        // Hide all options by default
        optionsSection.hide();
        mcqOptions.hide();
        tfOptions.hide();

        // Show the appropriate section based on type
        if (type == 'MCQ') {
            optionsSection.show();
            mcqOptions.show();
        } else if (type == 'True/False') {
            optionsSection.show();
            tfOptions.show();
        }
    });

    // Remove question
    $(document).on('click', '.remove-question', function() {
        $(this).closest('.question-item').remove();
    });

    // Trigger change event for existing questions on page load
    $(document).ready(function() {
        $('.question-type').each(function() {
            $(this).trigger('change');
        });
    });
");
?>

<style>
    #questions-container {
        padding: 0.5rem;
    }
    .question-item {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .option input[type='text'] {
        flex-grow: 1;
        margin-right: 0.5rem;
    }
    .true-false-options {
        gap: 1rem;
    }
    label {
        font-size: 0.9rem;
    }
    /* Ensure all options are hidden by default */
    .options-section,
    .mcq-options,
    .true-false-options {
        display: none;
    }
</style>
