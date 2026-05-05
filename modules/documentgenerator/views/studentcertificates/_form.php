<?php

use app\models\User;
use kartik\file\FileInput as FileFileInput;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\Studentcertificates */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="studentcertificates-form">

    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form-inline',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true,
                'formConfig' => ['showErrors' => true],
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <?= $form->errorSummary($model); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'certificate_name')->textInput(['maxlength' => true, 'placeholder' => 'Certificate Name']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'header_left_text')->textInput(['rows' => 6]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'header_center_text')->textInput(['rows' => 6]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'header_right_text')->textInput(['rows' => 6]) ?>
                </div>
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'footer_center_text')->textInput(['rows' => 6]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'center_sig')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*', "id" => 'center_sig_id'],
                                'pluginOptions' => [
                                    'previewFileType' => 'image',
                                    'initialPreview' => [$model->center_sig],
                                    'initialPreviewAsData' => true,
                                    'overwriteInitial' => true,
                                    'showUpload' => false,
                                ],
                            ])->label('Center Signature (*Only Png)'); ?>
                        </div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'footer_left_text')->textInput(['rows' => 6]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'left_sig')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*', "id" => 'left_sig_id'],
                                'pluginOptions' => [
                                    'previewFileType' => 'image',
                                    'initialPreview' => [$model->left_sig],
                                    'initialPreviewAsData' => true,
                                    'overwriteInitial' => true,
                                    'showUpload' => false,
                                ],
                            ])->label('Left Signature (*Only Png)'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'footer_right_text')->textInput(['rows' => 6]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'right_sig')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*', "id" => 'right_sig_id'],
                                'pluginOptions' => [
                                    'previewFileType' => 'image',
                                    'initialPreview' => [$model->right_sig],
                                    'initialPreviewAsData' => true,
                                    'overwriteInitial' => true,
                                    'showUpload' => false,
                                ],
                            ])->label('Left Signature (*Only Png)'); ?>
                        </div>
                    </div>
                </div>

            </div>


            <?php /* <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'header_height')->textInput(['placeholder' => 'Header Height'])->label('Certificate Design') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'footer_height')->textInput(['placeholder' => 'Footer Height'])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'body_height')->textInput(['placeholder' => 'Body Height'])->label(false) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'body_width')->textInput(['placeholder' => 'Body Width'])->label(false) ?>
                </div>
            </div>
            */ ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'student_image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*', "id" => 'student_image_id'],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'initialPreview' => [$model->student_image],
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => true,
                            'showUpload' => false,
                        ],
                    ]); ?>
                    <?= $form->field($model, 'body_text')->textarea(['rows' => 6]) ?>
                    <span class="text-primary">[name] [dob] [present_address] [guardian] [created_at] [admission_no] [roll_no] [class] [section] [gender] [admission_date] [category] [cast] [father_name] [mother_name] [email] [phone] </span>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'background_image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*', "id" => 'backround_image_id'],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'initialPreview' => [$model->background_image],
                            'initialPreviewAsData' => true,
                            'overwriteInitial' => true,
                            'showUpload' => false,
                        ],
                    ])->label('Background Image (1100X850px)'); ?>
                    <?= $form->field($model, 'template_type')->dropDownList($model->getTemplateType()) ?>

                </div>

            </div>
            <div class="row">
                <div class="col-md-2">

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" data-toggle="modal" id="previewButton" data-target="#exampleModal">
                        Preview
                    </button>
                </div>


            </div>


            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<?php

$url = Url::toRoute(['preview']);

?>

<!-- Bootstrap Modal for Preview -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Certificate content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#previewButton').on('click', function() {
            // Create FormData object to handle file uploads
            var formData = new FormData();
            formData.append('StudentCertificates[student_image]', $('#student_image_id')[0].files[0]);
            formData.append('StudentCertificates[background_image]', $('#backround_image_id')[0].files[0]);
            console.log(formData);
            // Make an AJAX request to get the preview content
            $.ajax({
                url: '<?= $url ?>', // Replace with your controller/action URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Update the modal content with the preview
                    $('#previewContent').html(response);

                    // Show the modal
                    $('#exampleModal').modal('show');
                }
            });
        });
    });
</script>