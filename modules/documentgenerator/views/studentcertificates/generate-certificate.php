<?php

use app\models\User;
use app\modules\admin\models\base\ClassRooms;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\Studentcertificates */
/* @var $form ActiveForm */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Certificate'), 'url' => ['generate-certificate']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                        </div>
                        <div class="box-body">
                            <div class="generate-certificate">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'generate-certificate-form',
                                    'options' => ['enctype' => 'multipart/form-data'],
                                    'action' => 'javascript:void(0)',
                                ]); ?>

                                <div class="row mt-5">
                                    <div class="col-sm-4">
                                        <?= $form->field($model, 'title')->widget(\kartik\widgets\Select2::classname(), [
                                            'data' => $classRoomTitles,
                                            'options' => ['placeholder' => 'Select Class Room'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]); ?>
                                    </div>

                                    <div class="col-sm-4">
                                        <?= $form->field($model, 'section_name')->widget(DepDrop::classname(), [
                                            'options' => ['id' => 'Section'],
                                            'type' => DepDrop::TYPE_SELECT2,
                                            'select2Options' => ['pluginOptions' => ['allowClear' => true, 'multiple' => false, 'closeOnSelect' => true]],
                                            'pluginOptions' => [
                                                'placeholder' => 'Select...',
                                                'depends' => ['studentcertificates-title'],
                                                'url' => \yii\helpers\Url::to('get-sections'), // Update with your actual controller/action
                                            ],
                                        ]); ?>

                                    </div>

                                    <div class="col-sm-4 mb-5">
                                        <?= $form->field($model, 'certificate_name')->widget(\kartik\widgets\Select2::classname(), [
                                            'data' => \yii\helpers\ArrayHelper::map(
                                                \app\modules\documentgenerator\models\Studentcertificates::find()->where(['campus_id' => (new User)->getCampusId()])->all(),
                                                'id',
                                                'certificate_name'
                                            ),
                                            'options' => ['placeholder' => 'Select Certificate'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                            ],
                                        ]); ?>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'submit_data']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="student-table-container"></div>
    </div>
    <!-- generate-certificate -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $("#studentcertificates-class_room_title").on('change', function() {
                // Trigger the change event to make the DepDrop widget fetch sections
                $("#Section").trigger('change');
            });

            // Handle form submission with Ajax
            $("#generate-certificate-form").on('submit', function(e) {


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