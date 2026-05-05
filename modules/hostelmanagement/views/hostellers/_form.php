<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\User;
use app\modules\hostelmanagement\models\Hostellers;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Hostellers */
/* @var $form yii\widgets\ActiveForm */

?>
<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <div class="alert alert-danger">
        <?php echo Yii::$app->session->getFlash('error'); ?>
    </div>
<?php endif; ?>
<div class="hostellers-form">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>
    <?= $form->errorSummary($model); ?>
    <div class="row grid-margin stretch-card">
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <?php
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
                } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                    // Your code for the Chef Warden condition goes here
                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
                }
            }
            ?>
        </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'warden_id', ['template' => '{input}'])->textInput(['value' => User::getUserId(), 'style' => 'display:none']);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'onboarded_by', ['template' => '{input}'])->textInput(['value' => User::getUserId(), 'style' => 'display:none']);  ?> </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>

        <?= $form->field($model, 'student_ids')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(
        \app\modules\admin\models\StudentDetails::find()
            ->orderBy('id')
            ->where([
                'or',
                ['campus_id' => User::getCampusId()],
                ['campus_id' => User::getUserCampusId()],
            ])
            ->andWhere(['not in', 'user_id', Hostellers::find()->select('student_id')])
            ->asArray()
            ->all(),
        'user_id',
        'student_name'
    ),
    'options' => ['placeholder' => Yii::t('app', 'Choose Student details'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'hostel_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->orderBy('id')->where([
                    'or',
                    ['campus_id' => User::getCampusId()],
                    ['campus_id' => User::getUserCampusId()],
                ])->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Hostels'), 'id' => 'hostel_id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);  ?>
        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'room_id')->widget(DepDrop::classname(), [
                // 'data' => \yii\helpers\ArrayHelper::map(SubCategories::find()->where(['id' => $model->sub_category_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['id' => 'room_id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['hostel_id'],
                    'url' => \yii\helpers\Url::to('get-data'),
                ],
            ]);

            ?>
        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'joining_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                                                                'saveFormat' => 'php:Y-m-d',
                                                                'ajaxConversion' => true,
                                                                'options' => [
                                                                    'pluginOptions' => [
                                                                        'placeholder' => Yii::t('app', 'Choose Joining Date'),
                                                                        'autoclose' => true
                                                                    ]
                                                                ],
                                                            ]);  ?> </div>
        <!-- <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'room_id')->textInput(['placeholder' => 'Room'])  ?> </div> -->
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'address')->textInput([
                'maxlength' => true,
                'placeholder' => 'Address',
                'id' => 'address',
                'readOnly' => true,  // Add this line to make the field read-only
            ]) ?>
        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'aadhar_number')->textInput([
                'maxlength' => true,
                'placeholder' => 'aadhar number',
                'id' => 'aadhar_number',
                'readOnly' => true,  // Add this line to make the field read-only
            ]) ?>
        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?php

                                                            echo $form->field($model, 'status')->dropDownList($model->getStateOptions());

                                                            ?> </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?php
            // Check if the model is not new before displaying the input field

            ?>
        </div>

    </div>
    <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    

    $('#student_id').change(function() {
        $.ajax({
            url: 'get-student-data',
            type: 'GET',
            data: {
                student_id: $(this).val()
            },
            success: function(data) {
                // Set the retrieved 'aadhar_number' value to the 'aadhar_number' input field
                $('#aadhar_number').val(data.national_Identification_number);
                $('#address').val(data.permanent_address);
            },
            error: function() {
                console.error('Error fetching data:', error);
            }
        });
    });
</script>