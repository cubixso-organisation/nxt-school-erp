<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\PayFeesSearch */
/* @var $form yii\widgets\ActiveForm */

$user = new User();
$campusId = $user->getCampusId();
$campusId = $campusId !== null ? $campusId : $user->getCampusesByUser(\Yii::$app->user->identity->id);
?>

<?php if ($campusId == 79 || $campusId == 77 || $campusId == 67) { ?>
    <div class="form-pay-fees-search">

        <?php $form = ActiveForm::begin([
            'action' => ['assign-fee-details'],
            'method' => 'get',
        ]); ?>

        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'class_id')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                        ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['is_agent' => null])
                        ->orderBy('id')->asArray()->all(), 'id', 'title'),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Choose Student class'),
                        'id' => 'student-class-id',
                        'disabled' => !$model->isNewRecord ? true : false
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>

            <?php
            if (!$model->isNewRecord) {
                $class_section = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->orderBy('id')->asArray()->all(), 'id', 'section_name');
            } else {
                $class_section = [];
            }
            ?>

            <div class="col-md-4">
                <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                    'data' => $class_section,
                    'options' => ['id' => 'class-section-id', 'disabled' => !$model->isNewRecord ? true : false],
                    'pluginOptions' => [
                        'depends' => ['student-class-id'],
                        'placeholder' => 'Select...',
                        'url' => Url::to(['/admin/fee-structures/class-section-data'])
                    ]
                ]); ?>
            </div>

            <?php
            if (!$model->isNewRecord) {
                $student_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->orderBy('id')->asArray()->all(), 'id', 'student_name');
            } else {
                $student_data = [];
            }
            ?>

            <div class="col-md-4">
                <?= $form->field($model, 'student_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2, // <-- Add this line
                    'options' => [
                        'id' => 'student_id',
                        'multiple' => false,
                        'disabled' => !$model->isNewRecord ? true : false,
                        'placeholder' => 'Select or Search Student',
                    ],
                    'pluginOptions' => [
                        'depends' => ['class-section-id'],
                        'placeholder' => 'Select or Search Student',
                        'url' => Url::to(['/admin/student-details/student-data-by-class-section-by-parent']),
                        'initialize' => true,
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>


        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php } else { ?>

    <div class="form-pay-fees-search">

        <?php $form = ActiveForm::begin([
            'action' => ['assign-fee-details'],
            'method' => 'get',
        ]); ?>

        <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'student_id')->widget(Select2::classname(), [
                    'options' => [
                        'placeholder' => Yii::t('app', 'Search Student by Name'),
                        'id' => 'student-name-search'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'ajax' => [
                            'url' => Url::to(['/admin/pay-fees/search-students-by-name']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {name: params.term}; }')
                        ],
                        // Custom messages for Select2
                        'language' => [
                            'errorLoading' => Yii::t('app', 'Enter student name'), // Change the error message
                        ],
                    ],
                    'pluginEvents' => [
                        "select2:select" => new JsExpression('function(e) { 
            var studentId = e.params.data.id;
            $.ajax({
                url: "' . Url::to(['/admin/pay-fees/get-student-details']) . '",
                data: {id: studentId},
                success: function(data) {
                    $("#student-class").val(data.class_id);
                    $("#student-section").val(data.section_id);
                    $("#student-class-name").text(data.class_name);
                    $("#student-section-name").text(data.section_name);
                    $("#student-father-name").text(data.father_name);
                    $("#student-phone-number").text(data.phone_number);
                },
                error: function() {
                    console.error("Error retrieving student details.");
                }
            });
        }'),
                    ],
                ])->label('Student Name'); ?>

            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Father Name</label>
                    <div id="student-father-name" class="form-control" readonly></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Phone Number</label>
                    <div id="student-phone-number" class="form-control" readonly></div>
                </div>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'class_id')->hiddenInput(['id' => 'student-class'])->label(false) ?>
                <div class="form-group">
                    <label>Class</label>
                    <div id="student-class-name" class="form-control" readonly></div>
                </div>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'section_id')->hiddenInput(['id' => 'student-section'])->label(false) ?>
                <div class="form-group">
                    <label>Section</label>
                    <div id="student-section-name" class="form-control" readonly></div>
                </div>
            </div>
        </div>

        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php } ?>