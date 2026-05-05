<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\User;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\LibraryMembers */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="library-members-form">

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

    <?= $form->field($model, 'campus_id')->textInput(['value' => User::getCampusId(), 'readonly' => true]) ?>

    <?= $form->field($model, 'member_type')->widget(\kartik\widgets\Select2::classname(), [
        'data' => $model->getLibraryMemberType(),
        'options' => ['placeholder' => Yii::t('app', 'Member Type'), 'id' => 'member_type'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);  ?>

    <?= $form->field($model, 'member_id')->widget(DepDrop::classname(), [
        // 'data' => \yii\helpers\ArrayHelper::map(SubCategories::find()->where(['id' => $model->sub_category_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['id' => 'subcat-id'],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
        'pluginOptions' => [
            'placeholder' => 'Select...',
            'depends' => ['member_type'],
            'url' => \yii\helpers\Url::to('get-user'),
        ],
    ]);

    ?>
    <?= $form->field($model, 'admission_no')->textInput(['maxlength' => true, 'placeholder' => 'Admission No']) ?>

    <?= $form->field($model, 'library_card_no')->textInput(['maxlength' => true, 'placeholder' => 'Library Card No']) ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Department Name']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) ?>


    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // Assuming "issuebooks-book_id" is the correct ID for your book_id field
        $("#librarymembers-member_type").on('change', function() {
            var selectedMember = $(this).val();
            // console.log(selectedBookId);

            // Use AJAX to fetch data based on the selected book_id
            $.ajax({
                url: 'get-data', // Replace with the actual endpoint URL
                type: 'GET',
                data: {
                    member_type: selectedMember
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);

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