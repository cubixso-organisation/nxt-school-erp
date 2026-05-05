<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\IssueBooks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="issue-books-form">

    <?php
    // var_dump(User::getCampusesByUser(Yii::$app->user->identity->id));exit;

    $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'library_member_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(
                    \app\modules\librarymanagement\models\LibraryMembers::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->all(),
                    'id',
                    function ($model) {
                        return $model->name . ' (' . $model->member_id . ')';
                    }
                ),
                'options' => ['placeholder' => Yii::t('app', 'Library Members Id')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>


        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'library_id')->textInput(['maxlength' => true, 'placeholder' => 'Library', 'readonly' => true]) ?>
        </div>

    </div>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'book_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(
                    \app\modules\librarymanagement\models\LibraryBooks::find()
                        ->where(['>', 'available', 0])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]) // Exclude books where available is 0
                        ->orderBy('id')
                        ->asArray()
                        ->all(),
                    'id',
                    'book_title'
                ),
                'options' => ['placeholder' => Yii::t('app', 'Choose Library books')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'author')->textInput(['maxlength' => true, 'placeholder' => 'Author', 'readonly' => true]) ?>

        </div>


    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'serial_no')->textInput(['maxlength' => true, 'placeholder' => 'Serial No', 'readonly' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true, 'placeholder' => 'Subject', 'readonly' => true]) ?>
        </div>


    </div>

    <div class="row">


        <div class="col-sm-6">
            <?= $form->field($model, 'status')->widget(\kartik\widgets\Select2::classname(), [
                'data' => $model->getStateOptions(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose Status'),
                    'value' =>  $model->status,


                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumResultsForSearch' => -1,
                ],
            ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'note')->textInput(['maxlength' => true, 'placeholder' => 'Note']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'librarian_user_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(
                    \app\models\User::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['user_role' => User::ROLE_LIBRARIAN])->all(),
                    'id',
                    'username'
                ),
                'options' => ['placeholder' => Yii::t('app', 'Issued By')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);  ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'due_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Due Date'),
                        'autoclose' => true
                    ]
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'issued_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Issue Date'),
                        'autoclose' => true
                    ]
                ],
            ]); ?>
        </div>


    </div>


    <?php if ($model->isNewRecord) { ?><?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Issue Book') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // Assuming "issuebooks-book_id" is the correct ID for your book_id field
        $("#issuebooks-book_id").on('change', function() {
            var selectedBookId = $(this).val();

            // Use AJAX to fetch data based on the selected book_id
            $.ajax({
                url: 'get-data', // Replace with the actual endpoint URL
                type: 'GET',
                data: {
                    book_id: selectedBookId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    var authorData = response.author;
                    var subjectData = response.subject;
                    var serialNoData = response.serial_no;

                    // Set the value of the "author" field
                    $("#issuebooks-subject_code").val(subjectData);
                    $("#issuebooks-author").val(authorData);
                    $("#issuebooks-serial_no").val(serialNoData);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Assuming "issuebooks-book_id" is the correct ID for your book_id field
        $("#issuebooks-library_member_id").on('change', function() {
            var selectedMemberId = $(this).val();
            console.log(selectedMemberId);
            // Use AJAX to fetch data based on the selected book_id
            $.ajax({
                url: 'get-member-data',
                type: 'GET',
                data: {
                    library_member_id: selectedMemberId
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