<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\StudentMeritMarks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="student-merit-marks-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
        } elseif (Yii::$app->user->identity->user_role == User::role_teacher) {

            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
        }
    }
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'child_merit_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\childassessment\models\ChildMerit::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Child merit')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'student_details_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'student_name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student details')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
       
        <?= $form->field($model, 'max_marks')->label('Full Marks')->textInput(['maxlength' => true, 'placeholder' => 'Full Marks', 'readonly' => true]) ?>
            


        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'marks_scored')->textInput(['placeholder' => 'Marks Scored']) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Teacher details')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

        </div>
    </div>


    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        // Assuming "issuebooks-book_id" is the correct ID for your book_id field
        $("#studentmeritmarks-child_merit_id").on('change', function() {
            var selectedMeritType = $(this).val();
            console.log(selectedMeritType);
            // Use AJAX to fetch data based on the selected Merit Type
            $.ajax({
                url: 'get-merit-data',
                type: 'GET',
                data: {
                    child_merit_id: selectedMeritType
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    var libraryCard = response.libraryCardNo;
                    var libraryMemberId = response.libraryMemberId;

                    // Set the value of the "author" field
                    $("#issuebooks-library_id").val(libraryCard);


                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>